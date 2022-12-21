<?php

namespace Clickbar\Magellan\Schema;

use Illuminate\Support\Facades\DB;

/**
 * Utility class for enabling and disabling PostGIS extension.
 * Since Laravel does not support adding functions to the Schema Builder yet.
 * And we do not want to override the Connection and Builder classes, to maintain
 * compatibility with other database extension packages.
 */
class MagellanSchema
{
    public static function enablePostgis(?string $connection = null): void
    {
        DB::connection($connection)->statement('CREATE EXTENSION postgis;');
    }

    public static function enablePostgisIfNotExists(?string $connection = null): void
    {
        DB::connection($connection)->statement('CREATE EXTENSION IF NOT EXISTS postgis;');
    }

    public static function disablePostgis(?string $connection = null): void
    {
        DB::connection($connection)->statement('DROP EXTENSION postgis;');
    }

    public static function disablePostgisIfExists(?string $connection = null): void
    {
        DB::connection($connection)->statement('DROP EXTENSION IF EXISTS postgis;');
    }
}
