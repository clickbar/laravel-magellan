<?php

namespace Clickbar\Magellan\Geometries;

use Clickbar\Magellan\IO\Coordinate;
use Clickbar\Magellan\IO\GeometryModelFactory;

class GeometryFactory implements GeometryModelFactory
{
    public function createPoint(Dimension $dimension, ?int $srid, ?Coordinate $coordinate): Point
    {
        return Point::make($coordinate->x, $coordinate->y, $coordinate->z, $coordinate->m, $srid);
    }

    public function createLineString(Dimension $dimension, ?int $srid, array $points): LineString
    {
        return LineString::make($points);
    }

    public function createLinearRing(Dimension $dimension, ?int $srid, array $points): LineString
    {
        return LineString::make($points);
    }

    public function createPolygon(Dimension $dimension, ?int $srid, array $linearRings): Polygon
    {
        return Polygon::make($linearRings);
    }

    public function createMultiPoint(Dimension $dimension, ?int $srid, array $points): MultiPoint
    {
        return MultiPoint::make($points);
    }

    public function createMultiLineString(Dimension $dimension, ?int $srid, array $lineStrings): MultiLineString
    {
        return MultiLineString::make($lineStrings);
    }

    public function createMultiPolygon(Dimension $dimension, ?int $srid, array $polygons): MultiPolygon
    {
        return MultiPolygon::make($polygons);
    }

    public function createGeometryCollection(Dimension $dimension, ?int $srid, array $geometries): GeometryCollection
    {
        return GeometryCollection::make($geometries);
    }
}
