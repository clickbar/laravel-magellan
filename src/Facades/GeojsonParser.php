<?php

namespace Clickbar\Magellan\Facades;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser as BaseGeojsonParser;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Geometry parse(string|array $input)
 * @method static Geometry parseMultiLineString(array $coordinates)
 * @method static Geometry parsePolygon(array $coordinates)
 * @method static Geometry parseMultiPoint(array $coordinates)
 * @method static Geometry parseMultiPolygon(array $coordinates)
 *
 * @see BaseGeojsonParser
 */
class GeojsonParser extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseGeojsonParser::class;
    }
}
