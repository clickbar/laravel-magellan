<?php

namespace Clickbar\Magellan\Data\Geometries;

use Clickbar\Magellan\IO\Coordinate;
use Clickbar\Magellan\IO\GeometryModelFactory;

class GeometryFactory implements GeometryModelFactory
{
    public function createPoint(Dimension $dimension, ?int $srid, ?Coordinate $coordinate): Point
    {
        if ($coordinate === null) {
            return Point::makeEmpty($srid, $dimension);
        }

        return Point::make($coordinate->x, $coordinate->y, $coordinate->z, $coordinate->m, $srid);
    }

    public function createLineString(Dimension $dimension, ?int $srid, array $points): LineString
    {
        return LineString::make($points, $srid, $dimension);
    }

    public function createLinearRing(Dimension $dimension, ?int $srid, array $points): LineString
    {
        return LineString::make($points, $srid, $dimension);
    }

    public function createPolygon(Dimension $dimension, ?int $srid, array $linearRings): Polygon
    {
        return Polygon::make($linearRings, $srid, $dimension);
    }

    public function createMultiPoint(Dimension $dimension, ?int $srid, array $points): MultiPoint
    {
        return MultiPoint::make($points, $srid, $dimension);
    }

    public function createMultiLineString(Dimension $dimension, ?int $srid, array $lineStrings): MultiLineString
    {
        return MultiLineString::make($lineStrings, $srid, $dimension);
    }

    public function createMultiPolygon(Dimension $dimension, ?int $srid, array $polygons): MultiPolygon
    {
        return MultiPolygon::make($polygons, $srid, $dimension);
    }

    public function createGeometryCollection(Dimension $dimension, ?int $srid, array $geometries): GeometryCollection
    {
        return GeometryCollection::make($geometries, $srid, $dimension);
    }
}
