<?php

namespace Clickbar\Magellan\Facades;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Data\Geometries\GeometryCollection;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiLineString;
use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator as BaseWKTGenerator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static string generate(Geometry $geometry)
 * @method static string generatePoint(Point $point)
 * @method static string generateLineString(LineString $lineString)
 * @method static string generateMultiLineString(MultiLineString $multiLineString)
 * @method static string generatePolygon(Polygon $polygon)
 * @method static string generateMultiPolygon(MultiPolygon $multiPolygon)
 * @method static string generateMultiPoint(MultiPoint $multiPoint)
 * @method static string generateGeometryCollection(GeometryCollection $geometryCollection)
 * @method static string toPostgisGeometrySql(Geometry $geometry, string $schema)
 * @method static string toPostgisGeographySql(Geometry $geometry, string $schema)
 *
 * @see BaseWKTGenerator
 */
class WKTGenerator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseWKTGenerator::class;
    }
}
