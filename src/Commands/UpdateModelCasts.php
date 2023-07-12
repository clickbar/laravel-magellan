<?php

namespace Clickbar\Magellan\Commands;

use Clickbar\Magellan\Cast\GeographyCast;
use Clickbar\Magellan\Cast\GeometryCast;
use Clickbar\Magellan\Commands\Utils\ModelInformation;
use Clickbar\Magellan\Commands\Utils\PostgisColumnInformation;
use Clickbar\Magellan\Commands\Utils\TableColumnsCollection;
use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Finder\SplFileInfo;
use function Termwind\render;
use function Termwind\renderUsing;

class UpdateModelCasts extends Command
{
    public $signature = 'magellan:update-casts {--table=*}';

    public $description = 'Adds the $$postgisColumns array and trait to all models that have postgis columns in the DB.';

    protected Filesystem $files;

    /** @var ModelInformation[] */
    protected array $modelInformation;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $this->loadModelInformation();
        $postgisTableColumns = $this->getPostgisColumnInformation();

        $postgisTables = $postgisTableColumns->getColumns();

        $tableFromOption = 'points'; //$this->option('table'); TODO: FIX ME
        /** @phpstan-ignore-next-line PHPStan does not get the type annotation right */
        if (is_string($tableFromOption)) {
            $postgisTables = collect($postgisTables)
                ->only($tableFromOption)
                ->toArray();
        }

        foreach ($postgisTables as $tableName => $columns) {
            $this->components->info("Updating Model for table $tableName");

            $modelInformation = $this->getModelInformationForTable($tableName);
            if (!$modelInformation) {
                $this->components->warn('Cannot find model for table ' . $tableName);

                continue;
            }

            $modelReflectionClass = new ReflectionClass($modelInformation->getModelClassName());

            // Load the code
            $filePath = $modelReflectionClass->getFileName();
            $modelCodeLines = Str::of($this->files->get($filePath))->explode(PHP_EOL);

            $usedCasters = collect($columns)
                ->map(fn(PostgisColumnInformation $columnInfo) => $columnInfo->getCasterClass())
                ->unique()
                ->toArray();

            $this->addMissingCastImportsColumnsTrait($modelCodeLines, $modelReflectionClass, $usedCasters);

            // Check if the model already has the $cast array with all the casts
            $overwrite = false;
            $currentCastsCodeLines = [];
            if ($this->hasCastsArray($modelReflectionClass)) {
                $currentCastsInterval = $this->getCurrentCastsLineInterval($modelCodeLines);

                if ($currentCastsInterval === null) {
                    $this->components->error('> ' . 'Unable to detect current $casts. Please delete the property and rerun the command');

                    continue;
                }

                $modelInstance = invade($modelInformation->getInstance());

                // @phpstan-ignore-next-line We know that the property exists because of the if statement above
                $currentCasts = $modelInstance->casts;

                if (!collect($columns)->every(fn($column) => Arr::get($currentCasts, $column->getColumn()) == $column->toCastValue())) {
                    $this->components->warn('> ' . 'The $postgisColumns array does not contain all the columns/information from the DB. The columns will be added/replaced.');
                } else {
                    $this->components->info('> ' . 'The $postgisColumns array contains all the columns from the DB. No changes needed.');
                    continue;
                }

                $start = $currentCastsInterval[0];
                $end = $currentCastsInterval[1];

                $currentCastsCodeLines = $modelCodeLines->slice($start, $end - $start + 1)->toArray();
                $currentCode = implode(PHP_EOL, $currentCastsCodeLines);

                $this->output->writeln('Current code:');
                $this->printCode($currentCode, $start + 1);
                $this->output->writeln('');


                $this->output->writeln('New code:');
                $this->printCode(collect($this->buildCastsCodeLines($columns, $currentCastsCodeLines))->join(PHP_EOL), $start + 1);

                $confirmOverride = $this->components->confirm('Override the above displayed code?', true);
                if (!$confirmOverride) {
                    continue;
                }

                $overwrite = true;
                $modelCodeLines->splice($start, $end - $start + 1);
                $startLine = $start;
            } else {
                $startLine = $this->getAfterLastTraitOrClassPosition($modelCodeLines, $modelReflectionClass);
            }

            $this->insertCastsCodeLines($modelCodeLines, $startLine, $columns, $overwrite, $currentCastsCodeLines);

            $this->files->put($filePath, $modelCodeLines->join(PHP_EOL));
            $this->components->info('> ' . '$postgisColumns added to model');
        }

        return self::SUCCESS;
    }

    private function printCode(string $code, int $startLine): void
    {
        renderUsing($this->output);

        render('<code start-line="' . ($startLine - 2) . '">' . htmlentities('<?php' . PHP_EOL . '    // ...' . PHP_EOL . $code) . '</code>');
    }

    private function loadModelInformation()
    {
        $modelInformation = [];

        $potentialModelClassFiles = collect();

        foreach (config('magellan.model_directories', ['Models']) as $directory) {
            $potentialModelClassFiles->push(...$this->files->allFiles(app_path($directory)));
        }

        foreach ($potentialModelClassFiles as $potentialModelClassFile) {
            $modelClassName = $this->getFullyQualifiedClassNameFromFile($potentialModelClassFile);

            try {
                $modelInstance = new $modelClassName();
            } catch (\Throwable $e) {
                continue;
            }

            if ($modelInstance instanceof Model) {
                $modelInformation[] = new ModelInformation(
                    $modelClassName,
                    $potentialModelClassFile->getRelativePath(),
                    $modelInstance->getTable(),
                );
            }
        }

        $this->modelInformation = $modelInformation;
    }

    private function getModelInformationForTable(string $table): ?ModelInformation
    {
        return collect($this->modelInformation)
            ->first(function (ModelInformation $modelInformation) use ($table) {
                return $modelInformation->getTableName() === $table;
            });
    }

    private function getPostgisColumnInformation(): TableColumnsCollection
    {
        $tableColumns = new TableColumnsCollection();

        $geographyColumns = DB::table('geography_columns')->get();
        $geometryColumns = DB::table('geometry_columns')->get();

        $this->appendColumns($geographyColumns, 'geography', $tableColumns);
        $this->appendColumns($geometryColumns, 'geometry', $tableColumns);

        return $tableColumns;
    }

    private function appendColumns(Collection $columns, string $type, TableColumnsCollection &$tableColumns)
    {
        $columnKey = "f_{$type}_column";
        foreach ($columns as $column) {
            $tableColumns->add($column->f_table_name, new PostgisColumnInformation(
                $type,
                $column->type,
                $column->srid,
                $column->$columnKey,
                $column->coord_dimension,
            ));
        }
    }


    private function hasCastsArray(ReflectionClass $modelReflectionClass): bool
    {
        /*
         * Since hasProperty also checks the base class, this check would always return true,
         * since the model class has the casts array defined
         */

        return
            collect($modelReflectionClass->getProperties())
                ->filter(
                    function (ReflectionProperty $prop) use ($modelReflectionClass) {
                        return
                            $prop->getDeclaringClass()->getName() === $modelReflectionClass->getName()
                            && $prop->getName() == 'casts';
                    })
                ->isNotEmpty();
    }


    private function addMissingCastImportsColumnsTrait(Collection $modelCodeLines, ReflectionClass $reflectionClass, array $usedCasters): void
    {

        $usedCastersUseLines = array_map(fn($casterClass) => "use $casterClass;", $usedCasters);

        /*
         * Import Statement
         */

        // Find the first use import statement
        $firstImportUseLine = $modelCodeLines->search(fn(string $line) => Str::contains($line, 'use '));

        // check if the use statement is already there
        $missingCasterUseLines = array_filter($usedCastersUseLines, fn($casterLine) => $modelCodeLines->every(fn($line) => !Str::contains($line, $casterLine)));
        if (!empty($missingCasterUseLines)) {
            $modelCodeLines->splice($firstImportUseLine, 0, $missingCasterUseLines);
        }
    }

    /**
     * Searchs within the model code start and end line of the $casts property.
     */
    private function getCurrentCastsLineInterval(Collection $modelCodeLines): ?array
    {
        $openBracketCount = 0;
        $startLine = $modelCodeLines->search(fn($line) => Str::contains($line, '$casts'));
        if (Str::contains($modelCodeLines->get($startLine), '[')) {
            $openBracketCount = 1;
        }

        // Loop over all following rows and count the brackets until the array is closed
        $i = $startLine + 1;
        for (; $i < $modelCodeLines->count(); $i++) {
            $line = $modelCodeLines->get($i);
            if (Str::contains($line, '[')) {
                $openBracketCount++;
            }
            if (Str::contains($line, ']')) {
                $openBracketCount--;
            }
            if ($openBracketCount === 0) {
                return [$startLine, $i];
            }
        }

        return null;
    }

    /**
     * Discovers the starting line for inserting the $postgisColumns property.
     * Tries to find the line after the last Trait use or as default the first line after the class declaration.
     */
    private function getAfterLastTraitOrClassPosition(Collection $modelCodeLines, ReflectionClass $modelReflectionClass, bool $postgisTraitAdded = false): int
    {
        $startLine = 0;

        if (!empty($modelReflectionClass->getTraitNames())) {
            // --> Determine the insert position after the last use statement
            $lastUsedTraitName = Str::of(collect($modelReflectionClass->getTraitNames())->last())
                ->afterLast('\\');
            $startLine = $modelCodeLines->search(fn(string $line) => Str::contains($line, "use $lastUsedTraitName;"));
        }

        // Fallback in case of no traits or failed discovery of start line based on traits
        if ($startLine === 0) {
            // --> insert directly after the class name
            $startLine = $modelCodeLines->search(fn(string $line) => Str::contains($line, "class {$modelReflectionClass->getShortName()}"));
        }

        return $startLine + 2;
    }

    /**
     * @param bool $overwrite In case of overwrite, we don't need to add a blank upfront
     */
    private function insertCastsCodeLines(Collection $lines, int $startLine, array $columns, bool $overwrite, array $currentCastsLines)
    {
        $newCastsCodeLines = $this->buildCastsCodeLines($columns, $currentCastsLines);
        $linesToWrite = $newCastsCodeLines;

        if (!$overwrite) {
            $linesToWrite = Arr::prepend($newCastsCodeLines, '');
            $linesToWrite[] = '';
        }

        $lines->splice($startLine, 0, $linesToWrite);
    }

    /**
     * Builds the necessary lines for the $postgisColumns property based on the postgis views for geometry and geography.
     *
     * @param PostgisColumnInformation[] $columns
     */
    private function buildCastsCodeLines(array $columns, array $currentCastsLines): array
    {
        // Filter out all lines containing the name of the postgis columns that should be added
        // The protected $casts and the ] line will remain as first and last line
        $remainingCurrentCastsLines = array_filter($currentCastsLines, fn($codeLine) => collect($columns)->every(fn($columnInfo) => !Str::contains($codeLine, $columnInfo->getColumn())));
        // Remove the first and last line, because we will write it again
        // --> only the key => value of other casts should remain
        array_pop($remainingCurrentCastsLines);
        array_shift($remainingCurrentCastsLines);

        $remainingCurrentCastsLines = array_map(fn($line) => trim($line), $remainingCurrentCastsLines);

        $castsArrayLines = [];
        $castsArrayLines[] = $this->addInset(1, 'protected $casts = [');

        // Add the remaining ones
        foreach ($remainingCurrentCastsLines as $remainingCurrentCastsLine) {
            $castsArrayLines[] = $this->addInset(2, $remainingCurrentCastsLine);
        }

        /** @var PostgisColumnInformation $column */
        foreach ($columns as $column) {
            $castsArrayLines[] = $this->addInset(2, $column->toCastLineCode());
        }

        $castsArrayLines[] = $this->addInset(1, '];');

        return $castsArrayLines;
    }

    private function addInset(int $level, string $line): string
    {
        return Str::repeat(' ', $level * 4) . $line;
    }

    /**
     * Get the fully qualified class name from the given file.
     *
     * This is taken from https://github.com/facade/ignition/blob/f47e26a84b6738cd4a24c25af70d7cb32ada1ab6/src/Support/ComposerClassMap.php#L70
     * Which is licened under MIT.
     */
    protected function getFullyQualifiedClassNameFromFile(SplFileInfo $file): string
    {
        $class = trim(str_replace(app_path(), '', $file->getRealPath()), DIRECTORY_SEPARATOR);

        $appNamespace = app()->getNamespace();

        $class = str_replace(
            [DIRECTORY_SEPARATOR, 'App\\'],
            ['\\', $appNamespace],
            ucfirst(Str::replaceLast('.php', '', $class))
        );

        return $appNamespace . $class;
    }
}
