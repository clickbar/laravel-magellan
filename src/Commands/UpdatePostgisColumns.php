<?php

namespace Clickbar\Magellan\Commands;

use Clickbar\Magellan\Commands\Utils\ModelInformation;
use Clickbar\Magellan\Commands\Utils\PostgisColumnInformation;
use Clickbar\Magellan\Commands\Utils\TableColumnsCollection;
use Clickbar\Magellan\Eloquent\HasPostgisColumns;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;

class UpdatePostgisColumns extends Command
{
    public $signature = 'magellan:update-postgis-columns';

    public $description = 'Adds the $$postgisColumns array to the given model';

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

        foreach ($postgisTableColumns->getColumns() as $tableName => $columns) {
            $this->info("Updating Model for table $tableName");

            $modelInformation = $this->getModelInformationForTable($tableName);
            if (! $modelInformation) {
                $this->error('Cannot find model for table '.$tableName);
            }

            $modelReflectionClass = new ReflectionClass($modelInformation->getNamespace());

            // Load the code
            $filePath = $modelReflectionClass->getFileName();
            $modelCodeLines = Str::of($this->files->get($filePath))->explode(PHP_EOL);

            // Check if the model is missing the postgis columns trait
            $postgisScopeAdded = false;
            if (! $this->modelUsesPostgisTrait($modelInformation->getNamespace())) {
                // --> Trait is missing
                $this->warn($modelReflectionClass->getShortName().' does not use the HasPostgisColumns trait, the trait will be added');
                $this->addPostgisColumnsTrait($modelCodeLines, $modelReflectionClass);
                $postgisScopeAdded = true;
            }

            // Check if the model already has the postgis columns
            $overwrite = false;
            if ($modelReflectionClass->hasProperty('postgisColumns')) {
                $this->warn('Model already has the postgis columns');

                $currentPostgisColumnsInterval = $this->getCurrentPostgisColumnsLineInterval($modelCodeLines);
                if ($currentPostgisColumnsInterval === null) {
                    $this->error('Unable to detect current $postgisColumns. Please delete the property and rerrun the command');

                    continue;
                }

                $start = $currentPostgisColumnsInterval[0];
                $end = $currentPostgisColumnsInterval[1];

                $currentCode = $modelCodeLines->slice($start, $end - $start + 1)->join(PHP_EOL);
                $this->info($currentCode);

                $confirmOverride = $this->confirm('Override the above displayed code?', true);
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
            $this->info('$postgisColumns added to model');
        }

        return self::SUCCESS;
    }

    private function loadModelInformation()
    {
        $modelInformation = [];
        $potentialModelClassFiles = $this->files->allFiles(app_path('Models'));
        foreach ($potentialModelClassFiles as $potentialModelClassFile) {
            $namespace = Str::of($potentialModelClassFile->getRelativePathname())
                ->remove('.php')
                ->replace('/', '\\')
                ->prepend('App\\Models\\')
                ->toString();

            $modelInstance = new $namespace();

            if ($modelInstance instanceof Model) {
                $modelInformation[] = new ModelInformation(
                    $namespace,
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
        $geometryColumns = DB::table('geometry_columns')->get()->groupBy('f_table_name');

        $this->appendColumns($geographyColumns, 'geography', $tableColumns);
        $this->appendColumns($geometryColumns, 'geometry', $tableColumns);

        return $tableColumns;
    }

    private function appendColumns(Collection $columns, string $type, TableColumnsCollection &$tableColumns)
    {
        $columnKey = "f_${type}_column";
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
     *
     * @param  Collection  $modelCodeLines
     * @return array|null
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
     *
     * @param  Collection  $modelCodeLines
     * @param  ReflectionClass  $modelReflectionClass
     * @return int
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
     * @param  Collection  $lines
     * @param  int  $startLine
     * @param  array  $columns
     * @param  bool  $overwrite In case of overwrite, we don't need to add a blank upfront
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
     * @param PostgisColumnInformation[]
     * @return array
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
}
