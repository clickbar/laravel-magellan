<?php

namespace Clickbar\Postgis\Geometries;

use Clickbar\Postgis\IO\Coordinate;
use Clickbar\Postgis\IO\Dimension;
use Clickbar\Postgis\IO\GeometryModelFactory;

class GeometryFactory implements GeometryModelFactory
{
    public function createPoint(Dimension $dimension, ?int $srid, ?Coordinate $coordinate): Point
    {
        return Point::make($coordinate->x, $coordinate->y, $coordinate->z, $coordinate->m, $srid);
    }

    public function createLineString(Dimension $dimension, ?int $srid, array $points): LineString
    {
        return new LineString($dimension, $points, $srid);
    }

    public function createLinearRing(Dimension $dimension, ?int $srid, array $points): LineString
    {
        return new LineString($dimension, $points, $srid);
    }

    public function createPolygon(Dimension $dimension, ?int $srid, array $linearRings): Polygon
    {
        return new Polygon($dimension, $linearRings, $srid);
    }

    public function createMultiPoint(Dimension $dimension, ?int $srid, array $points): MultiPoint
    {
        return new MultiPoint($dimension, $points, $srid);
    }

    public function createMultiLineString(Dimension $dimension, ?int $srid, array $lineStrings): MultiLineString
    {
        return new MultiLineString($dimension, $lineStrings, $srid);
    }

    public function createMultiPolygon(Dimension $dimension, ?int $srid, array $polygons): MultiPolygon
    {
        return new MultiPolygon($dimension, $polygons, $srid);
    }

    public function createGeometryCollection(Dimension $dimension, ?int $srid, array $geometries): GeometryCollection
    {
        return new GeometryCollection($dimension, $geometries, $srid);
    }
}
