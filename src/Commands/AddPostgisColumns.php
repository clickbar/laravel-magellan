<?php

namespace Clickbar\Postgis\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use ReflectionClass;

class AddPostgisColumns extends Command
{
    public $signature = 'magellan:add-postgis-columns {model}';

    public $description = 'Adds the $$postgisColumns array to the given model';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $modelReflectionClass = $this->getModelReflectionClass();
        if (! $modelReflectionClass) {
            return self::FAILURE;
        }

        // Load the code
        $filePath = $modelReflectionClass->getFileName();
        $modelCodeLines = Str::of($this->files->get($filePath))->explode(PHP_EOL);

        // Check if the model already has the postgis columns
        if ($modelReflectionClass->hasProperty('postgisColumns')) {
            $this->warn('Model already has the postgis columns');

            $currentPostgisColumnsInterval = $this->getCurrentPostgisColumnsLineInterval($modelCodeLines);
            if ($currentPostgisColumnsInterval === null) {
                $this->error('Unable to detect current $postgisColumns. Please delete the property and rerrun the command');

                return self::FAILURE;
            }

            $start = $currentPostgisColumnsInterval[0];
            $end = $currentPostgisColumnsInterval[1];

            $currentCode = $modelCodeLines->slice($start, $end - $start + 1)->join(PHP_EOL);
            $this->info($currentCode);

            $confirmOverride = $this->confirm('Override the above displayed code?');
            if (! $confirmOverride) {
                return self::SUCCESS;
            }

            $modelCodeLines->splice($start, $end - $start + 1);
            $startLine = $start;
        } else {
            $startLine = $this->getStartLine($modelCodeLines, $modelReflectionClass);
        }

        $modelInstance = $modelReflectionClass->newInstance();
        $this->insertPostgisColumns($modelCodeLines, $startLine, $modelInstance);

        $this->files->put($filePath, $modelCodeLines->join(PHP_EOL));
        $this->info('$postgisColumns added to model');

        return self::SUCCESS;
    }

    /**
     * Searchs within the model code start and end line of the $postgisColumns property.
     * @param Collection $modelCodeLines
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
     * @param Collection $modelCodeLines
     * @param ReflectionClass $modelReflectionClass
     * @return int
     */
    private function getStartLine(Collection $modelCodeLines, ReflectionClass $modelReflectionClass): int
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

        return $startLine + 1;
    }

    private function insertPostgisColumns(Collection $lines, int $startLine, Model $model)
    {
        $postgisColumnsCodeLines = $this->buildPostgisColumnCodeLines($model);
        $lines->splice($startLine, 0, ['', ...$postgisColumnsCodeLines, '']);
    }

    /**
     * Builds the necessary lines for the $postgisColumns property based on the postgis views for geometry and geography.
     * @param Model $model
     * @return array
     */
    private function buildPostgisColumnCodeLines(Model $model): array
    {
        $table = $model->getTable();
        $geographyColumns = DB::table('geography_columns')->where('f_table_name', $table)->get();
        $geometryColumns = DB::table('geometry_columns')->where('f_table_name', $table)->get();

        $postgisColumnLines = [];
        $postgisColumnLines[] = 'protected array $postgisColumns = [';
        foreach ($geographyColumns as $geographyColumn) {
            $postgisColumnLines[] = "'{$geographyColumn->f_geography_column}' => [";
            $postgisColumnLines[] = "'type' => 'geography',";
            $postgisColumnLines[] = "'srid' => {$geographyColumn->srid},";
            $postgisColumnLines[] = "],";
        }
        foreach ($geometryColumns as $geometryColumn) {
            $postgisColumnLines[] = "'{$geometryColumn->f_geometry_column}' => [";
            $postgisColumnLines[] = "'type' => 'geometry',";
            $postgisColumnLines[] = "'srid' => {$geometryColumn->srid},";
            $postgisColumnLines[] = "],";
        }
        $postgisColumnLines[] = '];';

        return $postgisColumnLines;
    }

    /**
     * Gets the reflection class for the given model
     * In case of missing namespace appeds the default namespace.
     * @return ReflectionClass|null
     */
    private function getModelReflectionClass(): ?ReflectionClass
    {
        $modelClass = $this->argument('model');

        if (! class_exists($modelClass)) {
            // try to add default model prefix
            $modelClass = 'App\\Models\\' . $modelClass;
            if (! class_exists($modelClass)) {
                $this->error('Model class not found');

                return null;
            }
        }

        $reflectionClass = new ReflectionClass($modelClass);

        if (! $reflectionClass->isSubclassOf('Illuminate\Database\Eloquent\Model')) {
            $this->error('Model class is not a subclass of Model');

            return null;
        }

        return $reflectionClass;
    }
}
