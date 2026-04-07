<?php

namespace Clickbar\Magellan\IO\Parser\Geojson;

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\IO\Coordinate;
use Clickbar\Magellan\IO\Parser\BaseParser;

class GeojsonParser extends BaseParser
{
    public function parse($input): Geometry
    {
        return $this->parseWithSrid($input, 4326);
    }

    public function parseWithSrid($input, int $srid): Geometry
    {
        if (is_string($input)) {
            $input = json_decode($input, true);
        }

        $this->assertValidGeojson($input);

        $type = $input['type'];

        return match ($type) {
            'Feature' => $this->parseWithSrid($input['geometry'], $srid),
            'LineString' => $this->parseLineString($input['coordinates'], $srid),
            'MultiLineString' => $this->parseMultiLineString($input['coordinates'], $srid),
            'MultiPoint' => $this->parseMultiPoint($input['coordinates'], $srid),
            'MultiPolygon' => $this->parseMultiPolygon($input['coordinates'], $srid),
            'Point' => $this->parsePoint($input['coordinates'], $srid),
            'Polygon' => $this->parsePolygon($input['coordinates'], $srid),
            'GeometryCollection' => $this->parseGeometryCollection($input, $srid),
            'FeatureCollection' => throw new \RuntimeException('Invalid GeoJSON: The type FeatureCollection is not supported'),
            default => throw new \RuntimeException("Invalid GeoJSON: Invalid GeoJSON type $type"),
        };
    }

    protected function parseGeometryCollection(array $geometryCollectionData, int $srid): Geometry
    {
        $geometries = $geometryCollectionData['geometries'];
        $geometries = array_map(fn (array $geometry) => $this->parseWithSrid($geometry, $srid), $geometries);

        return $this->factory->createGeometryCollection(Dimension::DIMENSION_2D, $srid, $geometries);
    }

    protected function parsePoint(array $coordinates, int $srid): Geometry
    {
        $dimension = Dimension::DIMENSION_2D;
        $coordinate = ! empty($coordinates) ? new Coordinate($coordinates[0], $coordinates[1]) : null;
        if (count($coordinates) === 3) {
            $coordinate->setZ($coordinates[2]);
            $dimension = Dimension::DIMENSION_3DZ;
        }

        return $this->factory->createPoint($dimension, $srid, $coordinate);
    }

    protected function parseLineString(array $coordinates, int $srid): Geometry
    {
        $points = array_map(fn (array $coords) => $this->parsePoint($coords, $srid), $coordinates);

        return $this->factory->createLineString(Dimension::DIMENSION_2D, $srid, $points);
    }

    public function parseMultiLineString(array $coordinates, int $srid = 4326): Geometry
    {
        $lines = array_map(fn (array $coords) => $this->parseLineString($coords, $srid), $coordinates);

        return $this->factory->createMultiLineString(Dimension::DIMENSION_2D, $srid, $lines);
    }

    public function parsePolygon(array $coordinates, int $srid = 4326): Geometry
    {
        $lines = array_map(fn (array $coords) => $this->parseLineString($coords, $srid), $coordinates);

        return $this->factory->createPolygon(Dimension::DIMENSION_2D, $srid, $lines);
    }

    public function parseMultiPoint(array $coordinates, int $srid = 4326): Geometry
    {
        $points = array_map(fn (array $coords) => $this->parsePoint($coords, $srid), $coordinates);

        return $this->factory->createMultiPoint(Dimension::DIMENSION_2D, $srid, $points);
    }

    public function parseMultiPolygon(array $coordinates, int $srid = 4326): Geometry
    {
        $polygons = array_map(fn (array $coords) => $this->parsePolygon($coords, $srid), $coordinates);

        return $this->factory->createMultiPolygon(Dimension::DIMENSION_2D, $srid, $polygons);
    }

    // ************************************************ Assertions ***************************************************

    protected function assertValidGeojson($input): void
    {
        if (! is_array($input)) {
            throw new \RuntimeException('Invalid GeoJSON: The GeoJSON parser expects either a string or array as input');
        }

        if (! isset($input['type'])) {
            throw new \RuntimeException('Invalid GeoJSON: Missing type');
        }

        if (isset($input['coordinates']) && ! is_array($input['coordinates'])) {
            throw new \RuntimeException('Invalid GeoJSON: The coordinates must be an array');
        }
    }
}
