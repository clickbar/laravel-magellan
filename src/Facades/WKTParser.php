<?php

namespace Clickbar\Magellan\Facades;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser as BaseWKTParser;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Geometry parse(string $input)
 * @method static Point parsePoint(?string $argument)
 * @method static LineString parseLineString(?string $argument)
 * @method static Polygon parsePolygon(?string $argument)
 * @method static MultiPoint parseMultiPoint(?string $argument)
 *
 * @see BaseWKTParser
 */
class WKTParser extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseWKTParser::class;
    }
}
