<?php

namespace Clickbar\Postgis\IO\Generator\WKT;

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\GeometryHelper;
use Clickbar\Postgis\Geometries\GeometryInterface;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Generator\BaseGenerator;

class WKTGenerator extends BaseGenerator
{
    private function generatePointCoordinateString(Point $point): string
    {
        $string = GeometryHelper::stringifyFloat($point->getLongitude()) . ' ' . GeometryHelper::stringifyFloat($point->getLatitude());
        if ($point->is3d()) {
            $string .= ' ' . GeometryHelper::stringifyFloat($point->getAltitude());
        }

        return $string;
    }

    private function apply3dIfNeeded(string $type, GeometryInterface $geometry): string
    {
        if ($geometry->getDimension()->has3Dimensions()) {
            return "$type Z";
        }

        return $type;
    }

    public function generatePoint(Point $point): mixed
    {
        $wktType = $this->apply3dIfNeeded('POINT', $point);

        return sprintf('%s(%s)', $wktType, $this->generatePointCoordinateString($point));
    }

    public function generateLineString(LineString $lineString): mixed
    {
    }

    public function generateMultiLineString(MultiLineString $multiLineString): mixed
    {
    }

    public function generatePolygon(Polygon $polygon): mixed
    {
    }

    public function generateMultiPolygon(MultiPolygon $multiPolygon): mixed
    {
    }

    public function generateMultiPoint(MultiPoint $multiPoint): mixed
    {
    }

    public function generateGeometryCollection(GeometryCollection $geometryCollection): mixed
    {
    }
}
