<?php

namespace Clickbar\Postgis;

use Clickbar\Postgis\Commands\PostgisCommand;
use Closure;
use Illuminate\Database\Connection;
use PDO;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class PostgisServiceProvider extends PackageServiceProvider
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
            ->hasCommand(PostgisCommand::class);
    }

    public function registeringPackage()
    {
        Connection::resolverFor('pgsql', function (PDO|Closure $pdo, string $database = '', string $tablePrefix = '', array $config = []) {
            return new PostgisConnection($pdo, $database, $tablePrefix, $config);
        });
    }
}
