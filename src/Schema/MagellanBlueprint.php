<?php

namespace Clickbar\Magellan\Schema;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Fluent;

/**
 * @mixin \Illuminate\Database\Schema\Blueprint
 */
class MagellanBlueprint
{
    /**
     * Add a point column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanPoint(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanPoint', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a point column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanPointz(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanPointz', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multipoint column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultipoint(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanMultipoint', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a polygon column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanPolygon(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanPolygon', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multipolygon column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultipolygon(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanMultipolygon', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multipolygonz column on the table
     *
     * @param $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultipolygonz(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanMultipolygonz', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a linestring column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanLinestring(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanLinestring', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a linestringz column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanLinestringz(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanLinestringz', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a multilinestring column on the table
     *
     * @param    $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanMultilinestring(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanMultilinestring', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a geography column on the table
     *
     * @param  string  $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanGeography(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanGeography', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a geometry column on the table
     *
     * @param  string  $column
     * @param  int|null  $srid
     * @param  string  $postgisType
     * @return ColumnDefinition
     */
    public function magellanGeometry(): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOGRAPHY') {
            return $this->addColumn('magellanGeometry', $column, compact('postgisType', 'srid'));
        };
    }

    /**
     * Add a geometrycollection column on the table
     *
     * @param    $column
     * @param  null  $srid
     * @param  int  $dimensions
     * @param  bool  $typmod
     * @return Fluent
     */
    public function magellanGeometrycollection(): \Closure
    {
        return function ($column, $srid = 4326) {
            $postgisType = 'GEOMETRY';

            return $this->addColumn('magellanGeometrycollection', $column, compact('postgisType', 'srid'));
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
