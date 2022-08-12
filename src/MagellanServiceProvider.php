<?php

namespace Clickbar\Magellan;

use Clickbar\Magellan\Commands\AddPostgisColumns;
use Clickbar\Magellan\Commands\PostgisCommand;
use Clickbar\Magellan\Schema\Grammars\MagellanGrammar;
use Clickbar\Magellan\Schema\MagellanBlueprint;
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
            ->name('postgis')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_postgis_table')
            ->hasCommand(PostgisCommand::class)
            ->hasCommand(AddPostgisColumns::class);
    }

    public function registeringPackage()
    {
        PostgresGrammar::mixin(new MagellanGrammar());
        Blueprint::mixin(new MagellanBlueprint());
    }
}
