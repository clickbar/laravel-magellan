<?php

namespace Clickbar\Magellan;

use Clickbar\Magellan\Commands\AddPostgisColumns;
use Clickbar\Magellan\Commands\UpdatePostgisColumns;
use Clickbar\Magellan\Eloquent\Builder\BuilderMacros;
use Clickbar\Magellan\Eloquent\Builder\BuilderUtilsMacro;
use Clickbar\Magellan\Eloquent\Builder\PostgisBoundingBoxBuilderMacros;
use Clickbar\Magellan\Eloquent\Builder\PostgisGeometryProcessingBuilderMacros;
use Clickbar\Magellan\Eloquent\Builder\PostgisMeasurementBuilderMacros;
use Clickbar\Magellan\Geometries\GeometryFactory;
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
            ->hasCommand(UpdatePostgisColumns::class)
            ->hasCommand(AddPostgisColumns::class);
    }

    public function registeringPackage()
    {
        PostgresGrammar::mixin(new MagellanGrammar());
        Blueprint::mixin(new MagellanBlueprint());

        $this->registerBuilderMixin(new BuilderMacros());
        $this->registerBuilderMixin(new BuilderUtilsMacro());
        $this->registerBuilderMixin(new PostgisMeasurementBuilderMacros());
        $this->registerBuilderMixin(new PostgisBoundingBoxBuilderMacros());
        $this->registerBuilderMixin(new PostgisGeometryProcessingBuilderMacros());

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
    }

    private function registerBuilderMixin($mixin)
    {
        // See https://github.com/laravel/framework/issues/21950#issuecomment-437887175

        Builder::mixin($mixin);
        EloquentBuilder::mixin($mixin);
    }
}
