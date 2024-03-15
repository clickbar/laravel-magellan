<?php

namespace Clickbar\Magellan\Commands;

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
use Symfony\Component\Finder\SplFileInfo;

use function Termwind\render;
use function Termwind\renderUsing;

class UpdatePostgisColumns extends Command
{
    public $signature = 'magellan:update-postgis-columns {--table=*}';

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

        /** @phpstan-ignore-next-line PHPStan does not get the type annotation right */
        if (is_string($this->option('table'))) {
            $postgisTables = collect($postgisTables)
                ->only($this->option('table'))
                ->toArray();
        }

        foreach ($postgisTables as $tableName => $columns) {
            $this->components->info("Updating Model for table $tableName");

            $modelInformation = $this->getModelInformationForTable($tableName);
            if (! $modelInformation) {
                $this->components->warn('Cannot find model for table '.$tableName);

                continue;
            }

            $modelReflectionClass = new ReflectionClass($modelInformation->getModelClassName());

            // Load the code
            $filePath = $modelReflectionClass->getFileName();
            $modelCodeLines = Str::of($this->files->get($filePath))->explode(PHP_EOL);

            // Check if the model is missing the postgis columns trait
            $postgisScopeAdded = false;
            if (! $this->modelUsesPostgisTrait($modelInformation->getModelClassName())) {
                // --> Trait is missing
                $this->components->warn('> '.$modelReflectionClass->getShortName().' does not use the HasPostgisColumns trait, the trait will be added');
                $this->addPostgisColumnsTrait($modelCodeLines, $modelReflectionClass);
                $postgisScopeAdded = true;
            } else {
                $this->components->info('> '.$modelReflectionClass->getShortName().' already uses the HasPostgisColumns trait. We will not add it again.');
            }

            // Check if the model already has the postgis columns
            $overwrite = false;
            if ($modelReflectionClass->hasProperty('postgisColumns')) {
                $currentPostgisColumnsInterval = $this->getCurrentPostgisColumnsLineInterval($modelCodeLines);

                if ($currentPostgisColumnsInterval === null) {
                    $this->components->error('> '.'Unable to detect current $postgisColumns. Please delete the property and rerrun the command');

                    continue;
                }

                $modelInstance = invade($modelInformation->getInstance());

                // @phpstan-ignore-next-line We know that the property exists because of the if statement above
                $currentColumnsArray = $modelInstance->postgisColumns;

                if (! collect($columns)->every(fn ($column) => Arr::get($currentColumnsArray, $column->getColumn()) == $column->toArray())) {
                    $this->components->warn('> '.'The $postgisColumns array does not contain all the columns/information from the DB. The columns will be added/replaced.');
                } else {
                    $this->components->info('> '.'The $postgisColumns array contains all the columns from the DB. No changes needed.');

                    continue;
                }

                $start = $currentPostgisColumnsInterval[0];
                $end = $currentPostgisColumnsInterval[1];

                $currentCode = $modelCodeLines->slice($start, $end - $start + 1)->join(PHP_EOL);

                $this->output->writeln('Current code:');
                $this->printCode($currentCode, $start + 1);
                $this->output->writeln('');

                $this->output->writeln('New code:');
                $this->printCode(collect($this->buildPostgisColumnCodeLines($columns))->join(PHP_EOL), $start + 1);

                $confirmOverride = $this->components->confirm('Override the above displayed code?', true);
                if (! $confirmOverride) {
                    continue;
                }

                $overwrite = true;
                $modelCodeLines->splice($start, $end - $start + 1);
                $startLine = $start;
            } else {
                $startLine = $this->getAfterLastTraitOrClassPosition($modelCodeLines, $modelReflectionClass, $postgisScopeAdded);
            }

            $this->insertPostgisColumns($modelCodeLines, $startLine, $columns, $overwrite);

            $this->files->put($filePath, $modelCodeLines->join(PHP_EOL));
            $this->components->info('> '.'$postgisColumns added to model');
        }

        return self::SUCCESS;
    }

    private function printCode(string $code, int $startLine): void
    {
        renderUsing($this->output);

        render('<code start-line="'.($startLine - 2).'">'.htmlentities('<?php'.PHP_EOL.'    // ...'.PHP_EOL.$code).'</code>');
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

    private function modelUsesPostgisTrait(string $classWithNamespace): bool
    {
        return in_array(HasPostgisColumns::class, class_uses_recursive($classWithNamespace), true);
    }

    private function addPostgisColumnsTrait(Collection $modelCodeLines, ReflectionClass $reflectionClass): void
    {
        $traitImportLine = 'use '.HasPostgisColumns::class.';';
        $traitUseLine = $this->addInset(1, 'use HasPostgisColumns;');

        /*
         * Import Statement
         */

        // Find the first use import statement
        $classStartLine = $modelCodeLines->search(fn (string $line) => Str::contains($line, "class {$reflectionClass->getShortName()}"));
        $firstImportUseLine = $modelCodeLines->search(fn (string $line) => Str::contains($line, 'use '));

        if ($firstImportUseLine === false || $firstImportUseLine > $classStartLine) {
            // --> No use import statement found --> insert it right before the class name
            $importLinePosition = $classStartLine;
        } else {
            $importLinePosition = $firstImportUseLine;
        }

        $modelCodeLines->splice($importLinePosition, 0, [$traitImportLine]);

        /*
         * Use Statement
         */

        // Search for the last use statement
        $traitUsePosition = $this->getAfterLastTraitOrClassPosition($modelCodeLines, $reflectionClass);
        $modelCodeLines->splice($traitUsePosition, 0, [$traitUseLine]);
    }

    /**
     * Searchs within the model code start and end line of the $postgisColumns property.
     */
    private function getCurrentPostgisColumnsLineInterval(Collection $modelCodeLines): ?array
    {
        $openBracketCount = 0;
        $startLine = $modelCodeLines->search(fn ($line) => Str::contains($line, '$postgisColumns'));
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

        if (! empty($modelReflectionClass->getTraitNames())) {
            // --> Determine the insert position after the last use statement
            $lastUsedTraitName = Str::of(collect($modelReflectionClass->getTraitNames())->last())
                ->afterLast('\\');
            $startLine = $modelCodeLines->search(fn (string $line) => Str::contains($line, "use $lastUsedTraitName;"));
        }

        // Fallback in case of no traits or failed discovery of start line based on traits
        if ($startLine === 0) {
            // --> insert directly after the class name
            $startLine = $modelCodeLines->search(fn (string $line) => Str::contains($line, "class {$modelReflectionClass->getShortName()}"));
        }

        /*
       Check if we have added the postgis trait before.
       If we had so, the found last trait will be the one before our postgis trait.
       */
        if ($postgisTraitAdded) {
            $startLine++;
        }

        return $startLine + 1;
    }

    /**
     * @param  bool  $overwrite  In case of overwrite, we don't need to add a blank upfront
     */
    private function insertPostgisColumns(Collection $lines, int $startLine, array $columns, bool $overwrite)
    {
        $postgisColumnsCodeLines = $this->buildPostgisColumnCodeLines($columns);
        $linesToWrite = $postgisColumnsCodeLines;

        if (! $overwrite) {
            $linesToWrite = Arr::prepend($postgisColumnsCodeLines, '');
            $linesToWrite[] = '';
        }

        $lines->splice($startLine, 0, $linesToWrite);
    }

    /**
     * Builds the necessary lines for the $postgisColumns property based on the postgis views for geometry and geography.
     *
     * @param  PostgisColumnInformation[]  $columns
     */
    private function buildPostgisColumnCodeLines(array $columns): array
    {
        $postgisColumnLines = [];
        $postgisColumnLines[] = $this->addInset(1, 'protected array $postgisColumns = [');

        /** @var PostgisColumnInformation $column */
        foreach ($columns as $column) {
            $postgisColumnLines[] = $this->addInset(2, "'{$column->getColumn()}' => [");
            $postgisColumnLines[] = $this->addInset(3, "'type' => '{$column->getGeometryType()}',");
            $postgisColumnLines[] = $this->addInset(3, "'srid' => {$column->getSrid()},");
            $postgisColumnLines[] = $this->addInset(2, '],');
        }

        $postgisColumnLines[] = $this->addInset(1, '];');

        return $postgisColumnLines;
    }

    private function addInset(int $level, string $line): string
    {
        return Str::repeat(' ', $level * 4).$line;
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

        return $appNamespace.$class;
    }
}
