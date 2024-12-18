<?php

namespace Clickbar\Magellan\Schema\Grammars;

use Clickbar\Magellan\Exception\UnsupportedPostgisTypeException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Fluent;

class MagellanGrammarHelper
{
    // TODO: Consider using enum
    public static $allowed_geom_types = ['GEOGRAPHY', 'GEOMETRY'];

    /*
     * HELPERS
     */

    /**
     * @throws UnsupportedPostgisTypeException
     */
    public static function assertValidPostgisType(Fluent $column)
    {
        $postgisType = $column->get('postgisType', '');

        if (! is_string($postgisType) || ! in_array(strtoupper($postgisType), self::$allowed_geom_types)) {
            $implodedValidTypes = implode(', ', self::$allowed_geom_types);

            throw new UnsupportedPostgisTypeException("Postgis type '$postgisType' is not a valid postgis type. Valid types are $implodedValidTypes");
        }

        $srid = $column->get('srid', null);

        if (filter_var($srid, FILTER_VALIDATE_INT) === false) {
            throw new UnsupportedPostgisTypeException("The given SRID '$srid' is not valid. Only integers are allowed");
        }
    }

    /**
     * @param  string  $geometryType  The type of the geometry (Point, LineString, GeometryCollection, Geometry).
     *                                Default value is geometry, which allows all types.
     *                                HINT: Geometry in that case doesn't have an influence on being spatial or not
     */
    public static function createTypeDefinition(Fluent $column, string $geometryType = 'geometry'): string
    {
        self::assertValidPostgisType($column);

        $schema = Config::get('magellan.schema', 'public');
        $type = strtoupper(strval($column->get('postgisType', '')));
        $srid = $column->get('srid');

        $params = collect([$geometryType, $srid])
            ->filter()
            ->implode(',');

        return "$schema.$type($params)";
    }

    public static function createBoxTypeDefinition(Fluent $column, $boxType): string
    {
        // Assert bounding box type
        //self::assertValidPostgisType($column);

        $schema = Config::get('magellan.schema', 'public');

        return $schema.'.'.$boxType;
    }
}
