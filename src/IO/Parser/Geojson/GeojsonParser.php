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
        if (is_string($input)) {
            $input = json_decode($input, true);
        }

        if (! is_array($input)) {
            throw new \RuntimeException('Invalid GeoJSON: The GeoJSON parser expects either a string or array as input');
        }

        if (! isset($input['type'])) {
            throw new \RuntimeException('Invalid GeoJSON: Missing type');
        }

        $type = $input['type'];

        return match ($type) {
            'Feature' => $this->parse($input['geometry']),
            'LineString' => $this->parseLineString($input['coordinates']),
            'MultiLineString' => $this->parseMultiLineString($input['coordinates']),
            'MultiPoint' => $this->parseMultiPoint($input['coordinates']),
            'MultiPolygon' => $this->parseMultiPolygon($input['coordinates']),
            'Point' => $this->parsePoint($input['coordinates']),
            'Polygon' => $this->parsePolygon($input['coordinates']),
            'GeometryCollection' => $this->parseGeometryCollection($input),
            'FeatureCollection' => throw new \RuntimeException('Invalid GeoJSON: The type FeatureCollection is not supported'),
            default => throw new \RuntimeException("Invalid GeoJSON: Invalid GeoJSON type $type"),
        };
    }

    protected function parseGeometryCollection(array $geometryCollectionData): Geometry
    {
        $geometries = $geometryCollectionData['geometries'];
        $geometries = array_map(fn (array $geometry) => $this->parse($geometry), $geometries);

        return $this->factory->createGeometryCollection(Dimension::DIMENSION_2D, 4326, $geometries);
    }

    protected function parsePoint(array $coordinates): Geometry
    {
        $dimension = Dimension::DIMENSION_2D;
        $coordinate = ! empty($coordinates) ? new Coordinate($coordinates[0], $coordinates[1]) : null;
        if (count($coordinates) === 3) {
            $coordinate->setZ($coordinates[2]);
            $dimension = Dimension::DIMENSION_3DZ;
        }

        return $this->factory->createPoint($dimension, 4326, $coordinate);
    }

    protected function parseLineString(array $coordinates): Geometry
    {
        $points = array_map(fn (array $coords) => $this->parsePoint($coords), $coordinates);

        return $this->factory->createLineString(Dimension::DIMENSION_2D, 4326, $points);
    }

    public function parseMultiLineString(array $coordinates): Geometry
    {
        $lines = array_map(fn (array $coords) => $this->parseLineString($coords), $coordinates);

        return $this->factory->createMultiLineString(Dimension::DIMENSION_2D, 4326, $lines);
    }

    public function parsePolygon(array $coordinates): Geometry
    {
        $lines = array_map(fn (array $coords) => $this->parseLineString($coords), $coordinates);

        return $this->factory->createPolygon(Dimension::DIMENSION_2D, 4326, $lines);
    }

    public function parseMultiPoint(array $coordinates): Geometry
    {
        $points = array_map(fn (array $coords) => $this->parsePoint($coords), $coordinates);

        return $this->factory->createMultiPoint(Dimension::DIMENSION_2D, 4326, $points);
    }

    public function parseMultiPolygon(array $coordinates): Geometry
    {
        $polygons = array_map(fn (array $coords) => $this->parsePolygon($coords), $coordinates);

        return $this->factory->createMultiPolygon(Dimension::DIMENSION_2D, 4326, $polygons);
    }
}
