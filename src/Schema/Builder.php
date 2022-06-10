<?php

namespace Clickbar\Postgis\Schema;

use Closure;
use Illuminate\Database\Schema\PostgresBuilder;

class Builder extends PostgresBuilder
{
    protected function createBlueprint($table, Closure $callback = null): Blueprint
    {
        return new Blueprint($table, $callback);
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database.
     *
     * @return bool
     */
    public function enablePostgis()
    {
        return $this->connection->statement(
            $this->grammar->compileEnablePostgis()
        );
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database if it doesn't already exist.
     *
     * @return bool
     */
    public function enablePostgisIfNotExists()
    {
        return $this->connection->statement(
            $this->grammar->compileEnablePostgisIfNotExists()
        );
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database.
     *
     * @return bool
     */
    public function disablePostgis()
    {
        return $this->connection->statement(
            $this->grammar->compileDisablePostgis()
        );
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database if it exists.
     *
     * @return bool
     */
    public function disablePostgisIfExists()
    {
        return $this->connection->statement(
            $this->grammar->compileDisablePostgisIfExists()
        );
    }
}
