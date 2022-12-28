<?php

namespace Clickbar\Magellan;

use Clickbar\Magellan\Commands\UpdatePostgisColumns;
use Clickbar\Magellan\Eloquent\Builder\BuilderMacros;
use Clickbar\Magellan\Eloquent\Builder\PostgisOverlayMacros;
use Clickbar\Magellan\Eloquent\Builder\TestMixin;
use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;
use Clickbar\Magellan\IO\GeometryModelFactory;
use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;
use Clickbar\Magellan\Schema\Grammars\MagellanGrammar;
use Clickbar\Magellan\Schema\MagellanBlueprint;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MagellanServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-magellan')
            ->hasConfigFile()
            ->hasMigration('enable_postgis_extension')
            ->hasCommand(UpdatePostgisColumns::class);
    }

    public function registeringPackage()
    {
        PostgresGrammar::mixin(new MagellanGrammar());
        Blueprint::mixin(new MagellanBlueprint());

        $this->registerBuilderMixin(new BuilderMacros());
        $this->registerBuilderMixin(new PostgisOverlayMacros());
        $this->registerBuilderMixin(new TestMixin());

        $this->app->singleton(GeometryModelFactory::class, function ($app) {
            return new GeometryFactory();
        });

        $this->app->singleton(GeojsonParser::class, function ($app) {
            return new GeojsonParser($app->make(GeometryModelFactory::class));
        });

        $this->app->singleton(WKTParser::class, function ($app) {
            return new WKTParser($app->make(GeometryModelFactory::class));
        });

        $this->app->singleton(WKBParser::class, function ($app) {
            return new WKBParser($app->make(GeometryModelFactory::class));
        });

        // Register custom Doctrine types for PostGIS
        if (class_exists('Doctrine\DBAL\Connection')) {
            DB::registerDoctrineType(\Clickbar\Magellan\DBAL\Types\GeometryType::class, 'geometry', 'geometry');
            DB::registerDoctrineType(\Clickbar\Magellan\DBAL\Types\GeographyType::class, 'geography', 'geography');
        }

        // TODO: Move to Facade and export for users to use this on any DB connection
        DB::beforeExecuting(function ($query, &$bindings, $connection) {
            $generator = new WKBGenerator();

            foreach ($bindings as $key => $value) {
                if ($value instanceof Geometry) {
                    $bindings[$key] = $generator->generate($value);
                }
            }
        });
    }

    private function registerBuilderMixin($mixin)
    {
        // See https://github.com/laravel/framework/issues/21950#issuecomment-437887175
        Builder::mixin($mixin);
        EloquentBuilder::mixin($mixin);
    }
}
