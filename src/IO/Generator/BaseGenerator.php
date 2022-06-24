<?php

namespace Clickbar\Postgis\IO\Generator;

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\GeometryInterface;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;

abstract class BaseGenerator
{
    public function generate(GeometryInterface $geometry)
    {
        if ($geometry instanceof Point) {
            return $this->generatePoint($geometry);
        }

        if ($geometry instanceof LineString) {
            return $this->generateLineString($geometry);
        }

        if ($geometry instanceof MultiLineString) {
            return $this->generateMultiLineString($geometry);
        }

        if ($geometry instanceof Polygon) {
            return $this->generatePolygon($geometry);
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
}
