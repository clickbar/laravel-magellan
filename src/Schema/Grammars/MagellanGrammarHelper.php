<?php

namespace Clickbar\Magellan\Schema\Grammars;

use Clickbar\Magellan\Exception\UnsupportedPostgisTypeException;
use Illuminate\Support\Fluent;

class MagellanGrammarHelper
{
    // TODO: Consider using enum
    public static $allowed_geom_types = ['GEOGRAPHY', 'GEOMETRY'];

    /*
     * HELPERS
     */

    /**
     * @param  Fluent  $column
     *
     * @throws UnsupportedPostgisTypeException
     */
    public static function assertValidPostgisType(Fluent $column)
    {
        if (! in_array(strtoupper($column->postgisType), self::$allowed_geom_types)) {
            $implodedValidTypes = implode(', ', self::$allowed_geom_types);

            throw new UnsupportedPostgisTypeException("Postgis type '$column->postgisType' is not a valid postgis type. Valid types are $implodedValidTypes");
        }

        if (filter_var($column->srid, FILTER_VALIDATE_INT) === false) {
            throw new UnsupportedPostgisTypeException("The given SRID '$column->srid' is not valid. Only integers are allowed");
        }

        if (strtoupper($column->postgisType) === 'GEOGRAPHY' && $column->srid != 4326) {
            throw new UnsupportedPostgisTypeException('Error with validation of srid! SRID of GEOGRAPHY must be 4326)');
        }
    }

    public static function createTypeDefinition(Fluent $column, $geometryType): string
    {
        self::assertValidPostgisType($column);

        $schema = config('magellan.schema', 'public');
        $type = strtoupper($column->postgisType);

        return $schema.'.'.$type.'('.$geometryType.', '.$column->srid.')';
    }

    public static function createBoxTypeDefinition(Fluent $column, $boxType): string
    {
        // Assert bounding box type
        //self::assertValidPostgisType($column);

        $schema = config('magellan.schema', 'public');

        return $schema.'.'.$boxType;
    }
}
