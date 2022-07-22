<?php

namespace Clickbar\Magellan\IO\Generator;

use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\MultiLineString;
use Clickbar\Magellan\Geometries\MultiPoint;
use Clickbar\Magellan\Geometries\MultiPolygon;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;

abstract class BaseGenerator
{
    public function generate(Geometry $geometry)
    {
        if ($geometry instanceof Point) {
            return $this->generatePoint($geometry);
        }

        if ($geometry instanceof LineString) {
            return $this->generateLineString($geometry);
        }

        if ($geometry instanceof Polygon) {
            return $this->generatePolygon($geometry);
        }

        if ($geometry instanceof MultiLineString) {
            return $this->generateMultiLineString($geometry);
        }

        if ($geometry instanceof MultiPolygon) {
            return $this->generateMultiPolygon($geometry);
        }

        if ($geometry instanceof MultiPoint) {
            return $this->generateMultiPoint($geometry);
        }
        if ($geometry instanceof GeometryCollection) {
            return $this->generateGeometryCollection($geometry);
        }

        // TODO: Propper error
        throw new \RuntimeException('Unknown class');
    }

    abstract public function generatePoint(Point $point): mixed;

    abstract public function generateLineString(LineString $lineString): mixed;

    abstract public function generateMultiLineString(MultiLineString $multiLineString): mixed;

    abstract public function generatePolygon(Polygon $polygon): mixed;

    abstract public function generateMultiPolygon(MultiPolygon $multiPolygon): mixed;

    abstract public function generateMultiPoint(MultiPoint $multiPoint): mixed;

    abstract public function generateGeometryCollection(GeometryCollection $geometryCollection): mixed;

    abstract public function toPostgisGeometrySql(Geometry $geometry, string $schema): mixed;

    abstract public function toPostgisGeographySql(Geometry $geometry, string $schema): mixed;
}
