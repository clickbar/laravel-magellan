<?php

namespace Clickbar\Postgis\IO\Generator\WKT;

use Clickbar\Postgis\Geometries\Geometry;
use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\GeometryHelper;
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
        $string = GeometryHelper::stringifyFloat($point->getX()) . ' ' . GeometryHelper::stringifyFloat($point->getY());
        if ($point->getDimension()->has3Dimensions()) {
            $string .= ' ' . GeometryHelper::stringifyFloat($point->getZ());
        }

        return $string;
    }

    /**
     * @param Point[] $points
     * @return string
     */
    private function generatePointsCoordinateString(array $points): string
    {
        return implode(',', array_map(fn (Point $point) => $this->generatePointCoordinateString($point), $points));
    }

    private function generateLineStringCoordinateString(LineString $lineString): string
    {
        return $this->generatePointsCoordinateString($lineString->getPoints());
    }

    private function generateMultiLineStringCoordinateString(MultiLineString $multiLineString): string
    {
        return implode(',', array_map(function (LineString $linestring) {
            return sprintf('(%s)', $this->generateLineStringCoordinateString($linestring));
        }, $multiLineString->getLineStrings()));
    }

    private function apply3dIfNeeded(string $type, Geometry $geometry): string
    {
        if ($geometry->getDimension()->has3Dimensions()) {
            return "$type Z";
        }

        return $type;
    }

    public function generate(Geometry $geometry)
    {
        $wktWithoutSrid = parent::generate($geometry);

        if (! $geometry->hasSrid()) {
            return $wktWithoutSrid;
        }

        return sprintf('SRID=%d;%s', $geometry->getSrid(), $wktWithoutSrid);
    }

    public function generatePoint(Point $point): mixed
    {
        $wktType = $this->apply3dIfNeeded('POINT', $point);

        return sprintf('%s(%s)', $wktType, $this->generatePointCoordinateString($point));
    }

    public function generateLineString(LineString $lineString): mixed
    {
        $wktType = $this->apply3dIfNeeded('LINESTRING', $lineString);

        return sprintf('%s(%s)', $wktType, $this->generateLineStringCoordinateString($lineString));
    }

    public function generateMultiLineString(MultiLineString $multiLineString): mixed
    {
        $wktType = $this->apply3dIfNeeded('MULTILINESTRING', $multiLineString);

        return sprintf('%s(%s)', $wktType, $this->generateMultiLineStringCoordinateString($multiLineString));
    }

    public function generatePolygon(Polygon $polygon): mixed
    {
        $wktType = $this->apply3dIfNeeded('POLYGON', $polygon);

        return sprintf('%s(%s)', $wktType, $this->generateMultiLineStringCoordinateString($polygon));
    }

    public function generateMultiPolygon(MultiPolygon $multiPolygon): mixed
    {
        $wktType = $this->apply3dIfNeeded('MULTIPOLYGON', $multiPolygon);

        $polygonCoordinateStrings = implode(',', array_map(function (Polygon $polygon) {
            return sprintf('(%s)', $this->generateMultiLineStringCoordinateString($polygon));
        }, $multiPolygon->getPolygons()));

        return sprintf('%s(%s)', $wktType, $polygonCoordinateStrings);
    }

    public function generateMultiPoint(MultiPoint $multiPoint): mixed
    {
        $wktType = $this->apply3dIfNeeded('MULTIPOINT', $multiPoint);

        return sprintf('%s(%s)', $wktType, $this->generatePointsCoordinateString($multiPoint->getPoints()));
    }

    public function generateGeometryCollection(GeometryCollection $geometryCollection): mixed
    {
        $geometryWktStrings = implode(',', array_map(
            function (Geometry $geometry) {
                return parent::generate($geometry);
            },
            $geometryCollection->getGeometries()
        ));

        return sprintf('GEOMETRYCOLLECTION(%s)', $geometryWktStrings);
    }

    public function toPostgisGeometrySql(Geometry $geometry, string $schema): mixed
    {
        return sprintf("%s.ST_GeomFromEWKT('%s')", $schema, $this->generate($geometry));
    }

    public function toPostgisGeographySql(Geometry $geometry, string $schema): mixed
    {
        return sprintf("%s.ST_GeogFromText('%s')", $schema, $this->generate($geometry));
    }
}
