<?php

namespace Clickbar\Magellan\IO\Generator\Geojson;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Data\Geometries\GeometryCollection;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiLineString;
use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Exception\MissingGeodeticSRIDException;
use Clickbar\Magellan\IO\Generator\BaseGenerator;

class GeojsonGenerator extends BaseGenerator
{
    private function getPointCoordinatesArray(Point $point): array
    {
        if ($point->isEmpty()) {
            return [];
        }

        if (! $point->isGeodetic()) {
            throw new MissingGeodeticSRIDException(message: 'GeoJSON only supports geodetic coordinates. Make sure to use points with geodetic SRIDs that are listed in the geodetic_srids config or SRID=0.');
        }

        $coordinates = [$point->getLongitude(), $point->getLatitude()];

        if ($point->getAltitude() !== null) {
            $coordinates[] = $point->getAltitude();
        }

        return $coordinates;
    }

    /**
     * @param  Point[]  $points
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
            'geometries' => array_map(fn (Geometry $geometry) => $this->generate($geometry), $geometryCollection->getGeometries()),
        ];
    }

    public function toPostgisGeometrySql(Geometry $geometry, string $schema): mixed
    {
        return sprintf("%s.st_geomfromgeojson('%s')", $schema, json_encode($this->generate($geometry)));
    }

    public function toPostgisGeographySql(Geometry $geometry, string $schema): mixed
    {
        return sprintf("%s.st_geomfromgeojson('%s')::geography", $schema, json_encode($this->generate($geometry)));
    }
}
