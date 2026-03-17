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
use Clickbar\Magellan\IO\Generator\Geojson\GeojsonGenerator as BaseGeojsonGenerator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array generate(Geometry $geometry)
 * @method static array generatePoint(Point $point)
 * @method static array generateLineString(LineString $lineString)
 * @method static array generateMultiLineString(MultiLineString $multiLineString)
 * @method static array generatePolygon(Polygon $polygon)
 * @method static array generateMultiPolygon(MultiPolygon $multiPolygon)
 * @method static array generateMultiPoint(MultiPoint $multiPoint)
 * @method static array generateGeometryCollection(GeometryCollection $geometryCollection)
 * @method static string toPostgisGeometrySql(Geometry $geometry, string $schema)
 * @method static string toPostgisGeographySql(Geometry $geometry, string $schema)
 *
 * @see BaseGeojsonGenerator
 */
class GeojsonGenerator extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseGeojsonGenerator::class;
    }
}
