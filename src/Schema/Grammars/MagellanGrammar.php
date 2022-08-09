<?php

namespace Clickbar\Magellan\Schema\Grammars;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Fluent;

/**
 * @package Clickbar\Magellan\Schema\Grammars
 * @mixin \Illuminate\Database\Schema\Grammars\PostgresGrammar
 */
class MagellanGrammar
{
    /*
     *  Types
     */

    public function typePoint(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POINT');
        };
    }

    public function typePointZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POINTZ');
        };
    }

    public function typeMultiPoint(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTIPOINT');
        };
    }

    public function typePolygon(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POLYGON');
        };
    }

    public function typePolygonZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POLYGONZ');
        };
    }

    public function typeMultiPolygon(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTIPOLYGON');
        };
    }

    public function typeMultiPolygonZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTIPOLYGONZ');
        };
    }

    public function typeLinestring(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'LINESTRING');
        };
    }

    public function typeLinestringZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'LINESTRINGZ');
        };
    }

    public function typeMultiLinestring(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTILINESTRING');
        };
    }

    public function typeMultiLinestringZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTILINESTRINGZ');
        };
    }

    public function typeGeography(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'GEOGRAPHY');
        };
    }

    public function typeGeometry(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'GEOMETRY');
        };
    }

    public function typeGeometrycollection(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'GEOMETRYCOLLECTION');
        };
    }

    /*
     *  COMPILE Statements
     */

    /**
     * Adds a statement to add a geometrycollection geometry column
     *
     * @return \Closure
     */
    public function compileGeometrycollection(): \Closure
    {
        return function (Blueprint $blueprint, Fluent $command) {
            $command->type = 'GEOMETRYCOLLECTION';

            return $this->compileGeometry($blueprint, $command);
        };
    }

    /**
     * Adds a statement to create the postgis extension
     *
     * @return \Closure
     */
    public function compileEnablePostgis(): \Closure
    {
        return function () {
            return 'CREATE EXTENSION postgis;';
        };
    }

    /**
     * Adds a statement to create the postgis extension, if it doesn't already exist
     *
     * @return \Closure
     */
    public function compileEnablePostgisIfNotExists(): \Closure
    {
        return function () {
            return 'CREATE EXTENSION IF NOT EXISTS postgis;';
        };
    }

    /**
     * Adds a statement to drop the postgis extension
     *
     * @return \Closure
     */
    public function compileDisablePostgis(): \Closure
    {
        return function () {
            return 'DROP EXTENSION postgis;';
        };
    }

    /**
     * Adds a statement to drop the postgis extension, if it exists
     *
     * @return \Closure
     */
    public function compileDisablePostgisIfExists(): \Closure
    {
        return function () {
            return 'DROP EXTENSION IF EXISTS postgis;';
        };
    }
}
