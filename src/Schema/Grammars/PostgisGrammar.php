<?php

namespace Clickbar\Postgis\Schema\Grammars;

use Clickbar\Postgis\Exception\UnsupportedPostgisTypeException;
use Illuminate\Database\Schema\Grammars\PostgresGrammar;
use Illuminate\Support\Fluent;

class PostgisGrammar extends PostgresGrammar
{
    // TODO: Consider using enum
    public static $allowed_geom_types = ['GEOGRAPHY', 'GEOMETRY'];

    /*
     *  Types
     */

    public function typePoint(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'POINT');
    }

    public function typePointZ(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'POINTZ');
    }

    public function typeMultipoint(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'MULTIPOINT');
    }

    public function typePolygon(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'POLYGON');
    }

    public function typeMultipolygon(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'MULTIPOLYGON');
    }

    public function typeMultiPolygonZ(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'MULTIPOLYGONZ');
    }

    public function typeLinestring(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'LINESTRING');
    }

    public function typeLinestringZ(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'LINESTRINGZ');
    }

    public function typeMultilinestring(Fluent $column): string
    {
        return $this->createTypeDefinition($column, 'MULTILINESTRING');
    }

    public function typeGeography(Fluent $column): string
    {
        return 'GEOGRAPHY';
    }

    public function typeGeometry(Fluent $column): string
    {
        return 'GEOMETRY';
    }

    /*
     *  COMPILE Statements
     */

    /**
     * Adds a statement to create the postgis extension
     *
     * @return string
     */
    public function compileEnablePostgis(): string
    {
        return 'CREATE EXTENSION postgis';
    }

    /**
     * Adds a statement to create the postgis extension, if it doesn't already exist
     *
     * @return string
     */
    public function compileEnablePostgisIfNotExists(): string
    {
        return 'CREATE EXTENSION IF NOT EXISTS postgis';
    }

    /**
     * Adds a statement to drop the postgis extension
     *
     * @return string
     */
    public function compileDisablePostgis(): string
    {
        return 'DROP EXTENSION postgis';
    }

    /**
     * Adds a statement to drop the postgis extension, if it exists
     *
     * @return string
     */
    public function compileDisablePostgisIfExists(): string
    {
        return 'DROP EXTENSION IF EXISTS postgis';
    }

    /*
     * HELPERS
     */

    /**
     * @param Fluent $column
     *
     * @throws UnsupportedPostgisTypeException
     */
    protected function assertValidPostgisType(Fluent $column)
    {
        if (! in_array(strtoupper($column->postgisType), self::$allowed_geom_types)) {
            $implodedValidTypes = implode(', ', self::$allowed_geom_types);

            throw new UnsupportedPostgisTypeException("Postgis type '$column->postgisType' is not a valid postgis type. Valid types are $implodedValidTypes");
        }

        if (! filter_var($column->srid, FILTER_VALIDATE_INT)) {
            throw new UnsupportedPostgisTypeException("The given SRID '$column->srid' is not valid. Only integers are allowed");
        }

        if (strtoupper($column->postgisType) === 'GEOGRAPHY' && $column->srid != 4326) {
            throw new UnsupportedPostgisTypeException('Error with validation of srid! SRID of GEOGRAPHY must be 4326)');
        }
    }

    private function createTypeDefinition(Fluent $column, $geometryType): string
    {
        $this->assertValidPostgisType($column);

        $schema = config('postgis.schema', 'public'); // TODO: Add config
        $type = strtoupper($column->postgisType);

        return $schema . '.' . $type . '(' . $geometryType . ', ' . $column->srid . ')';
    }
}
