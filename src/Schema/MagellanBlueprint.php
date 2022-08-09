<?php

namespace Clickbar\Magellan\Schema;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Fluent;

/**
 * @package Clickbar\Magellan\Schema
 * @mixin \Illuminate\Database\Schema\Blueprint
 */
class MagellanBlueprint
{
    /**
     * Add a point column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanPoint(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('point', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a point column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanPointz(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('pointz', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multipoint column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultipoint(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('multipoint', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a polygon column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanPolygon(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('polygon', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multipolygon column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultipolygon(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('multipolygon', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multipolygonz column on the table
     *
     * @param $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultipolygonz(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('multipolygonz', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a linestring column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanLinestring(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('linestring', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a linestringz column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanLinestringz(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('linestringz', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multilinestring column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultilinestring(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('multilinestring', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a geography column on the table
     *
     * @param string $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanGeography(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('geography', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a geometry column on the table
     *
     * @param string $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function magellanGeometry(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('geometry', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a geometrycollection column on the table
     *
     * @param      $column
     * @param null $srid
     * @param int $dimensions
     * @param bool $typmod
     * @return Fluent
     */
    public function magellanGeometrycollection(): \Closure
    {
        return function ($column, $srid = 4326) {
            $postgisType = 'GEOMETRY';

            return $this->addColumn('geometrycollection', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database.
     *
     * @return Fluent
     */
    public function magellanEnablePostgis(): \Closure
    {
        return function () {
            return $this->addCommand('enablePostgis');
        };
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database if it doesn't already exist.
     *
     * @return Fluent
     */
    public function magellanEnablePostgisIfNotExists(): \Closure
    {
        return function () {
            return $this->addCommand('enablePostgisIfNotExists');
        };
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database.
     *
     * @return Fluent
     */
    public function magellanDisablePostgis(): \Closure
    {
        return function () {
            return $this->addCommand('disablePostgis');
        };
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database if it exists.
     *
     * @return Fluent
     */
    public function magellanDisablePostgisIfExists(): \Closure
    {
        return function () {
            return $this->addCommand('disablePostgisIfExists');
        };
    }
}
