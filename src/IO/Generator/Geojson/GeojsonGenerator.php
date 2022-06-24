<?php

namespace Clickbar\Postgis\IO\Generator\Geojson;

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\GeometryInterface;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Generator\BaseGenerator;

class GeojsonGenerator extends BaseGenerator
{
    private function getPointCoordinatesArray(Point $point): array
    {
        $coordinates = [$point->getLongitude(), $point->getLatitude()];

        if ($point->getAltitude() !== null) {
            $coordinates[] = $point->getAltitude();
        }

        return $coordinates;
    }

    /**
     * @param Point[] $points
     * @return array
     */
    private function getPointsCoordinateArray(array $points): array
    {
        return array_map(fn (Point $point) => $this->getPointCoordinatesArray($point), $points);
    }

    public function generatePoint(Point $point): mixed
    {
        return [
            'type' => 'Point',
            'coordinates' => $this->getPointCoordinatesArray($point),
        ];
    }

    public function generateLineString(LineString $lineString): mixed
    {
        return [
            'type' => 'LineString',
            'coordinates' => $this->getPointsCoordinateArray($lineString->getPoints()),
        ];
    }

    public function generateMultiLineString(MultiLineString $multiLineString): mixed
    {
        return [
            'type' => 'MultiLineString',
            'coordinates' => array_map(function (LineString $lineString) {
                return $this->getPointsCoordinateArray($lineString->getPoints());
            }, $multiLineString->getLineStrings()),
        ];
    }

    public function generatePolygon(Polygon $polygon): mixed
    {
        return [
            'type' => 'Polygon',
            'coordinates' => array_map(function (LineString $lineString) {
                return $this->getPointsCoordinateArray($lineString->getPoints());
            }, $polygon->getLineStrings()),
        ];
    }

    public function generateMultiPolygon(MultiPolygon $multiPolygon): mixed
    {
        return [
            'type' => 'MultiPolygon',
            'coordinates' => array_map(function (Polygon $polygon) {
                return array_map(function (LineString $lineString) {
                    return $this->getPointsCoordinateArray($lineString->getPoints());
                }, $polygon->getLineStrings());
            }, $multiPolygon->getPolygons()),
        ];
    }

    public function generateMultiPoint(MultiPoint $multiPoint): mixed
    {
        return [
            'type' => 'MultiPoint',
            'coordinates' => array_map(function (Point $point) {
                return $this->getPointCoordinatesArray($point);
            }, $multiPoint->getPoints()),
        ];
    }

    public function generateGeometryCollection(GeometryCollection $geometryCollection): mixed
    {
        return [
            'type' => 'GeometryCollection',
            'geometries' => array_map(fn (GeometryInterface $geometry) => $this->generate($geometry), $geometryCollection->getGeometries()),
        ];
    }
}
