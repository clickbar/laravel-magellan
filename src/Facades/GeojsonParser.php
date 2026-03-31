<?php

namespace Clickbar\Magellan\Facades;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser as BaseGeojsonParser;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Geometry parse(string|array $input)
 * @method static Geometry parseMultiLineString(array $coordinates, int $srid)
 * @method static Geometry parsePolygon(array $coordinates, int $srid)
 * @method static Geometry parseMultiPoint(array $coordinates, int $srid)
 * @method static Geometry parseMultiPolygon(array $coordinates, int $srid)
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
