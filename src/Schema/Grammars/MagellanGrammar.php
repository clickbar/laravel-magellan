<?php

namespace Clickbar\Magellan\Schema\Grammars;

use Illuminate\Support\Fluent;

/**
 * @mixin \Illuminate\Database\Schema\Grammars\PostgresGrammar
 */
class MagellanGrammar
{
    /*
     *  Types
     */

    public function typeMagellanPoint(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POINT');
        };
    }

    public function typeMagellanPointZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POINTZ');
        };
    }

    public function typeMagellanMultiPoint(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTIPOINT');
        };
    }

    public function typeMagellanPolygon(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POLYGON');
        };
    }

    public function typeMagellanPolygonZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'POLYGONZ');
        };
    }

    public function typeMagellanMultiPolygon(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTIPOLYGON');
        };
    }

    public function typeMagellanMultiPolygonZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTIPOLYGONZ');
        };
    }

    public function typeMagellanLinestring(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'LINESTRING');
        };
    }

    public function typeMagellanLinestringZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'LINESTRINGZ');
        };
    }

    public function typeMagellanMultiLinestring(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTILINESTRING');
        };
    }

    public function typeMagellanMultiLinestringZ(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'MULTILINESTRINGZ');
        };
    }

    public function typeMagellanGeography(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'GEOGRAPHY');
        };
    }

    public function typeMagellanGeometry(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'GEOMETRY');
        };
    }

    public function typeMagellanGeometrycollection(): \Closure
    {
        return function (Fluent $column) {
            return MagellanGrammarHelper::createTypeDefinition($column, 'GEOMETRYCOLLECTION');
        };
    }

    /*
     *  COMPILE Statements
     */

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
