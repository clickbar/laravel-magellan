<?php

namespace Clickbar\Postgis\Schema;

use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\Fluent;

class Blueprint extends \Illuminate\Database\Schema\Blueprint
{
    /**
     * Add a point column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function point($column, $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('point', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a point column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function pointz($column,  int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('pointz', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a multipoint column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function multipoint($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('multipoint', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a polygon column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function polygon($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('polygon', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a multipolygon column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function multipolygon($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('multipolygon', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a multipolygonz column on the table
     *
     * @param $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function multipolygonz($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('multipolygonz', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a linestring column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function linestring($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('linestring', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a linestringz column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function linestringz($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('linestringz', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a multilinestring column on the table
     *
     * @param      $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function multilinestring($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('multilinestring', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a geography column on the table
     *
     * @param string $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function geography($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('geography', $column, compact('postgisType', 'srid'));
    }

    /**
     * Add a geometry column on the table
     *
     * @param string $column
     * @param int|null $srid
     * @param string $postgisType
     * @return ColumnDefinition
     */
    public function geometry($column, int|null $srid = 4326, string $postgisType = 'GEOGRAPHY'): ColumnDefinition
    {
        return $this->addColumn('geometry', $column, compact('postgisType', 'srid'));
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
    public function geometrycollection($column, $srid = null)
    {
        $postgisType = 'GEOMETRY';

        return $this->addColumn('geometrycollection', $column, compact('postgisType', 'srid'));
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database.
     *
     * @return Fluent
     */
    public function enablePostgis()
    {
        return $this->addCommand('enablePostgis');
    }

    /**
     * Enable postgis on this database.
     * Will create the extension in the database if it doesn't already exist.
     *
     * @return Fluent
     */
    public function enablePostgisIfNotExists()
    {
        return $this->addCommand('enablePostgisIfNotExists');
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database.
     *
     * @return Fluent
     */
    public function disablePostgis()
    {
        return $this->addCommand('disablePostgis');
    }

    /**
     * Disable postgis on this database.
     * WIll drop the extension in the database if it exists.
     *
     * @return Fluent
     */
    public function disablePostgisIfExists()
    {
        return $this->addCommand('disablePostgisIfExists');
    }
}
