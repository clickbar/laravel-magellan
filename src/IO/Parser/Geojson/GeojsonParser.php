<?php

namespace Clickbar\Magellan\IO\Parser\Geojson;

use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\IO\Coordinate;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\BaseParser;

class GeojsonParser extends BaseParser
{
    public function parse($input): Geometry
    {
        if (is_string($input)) {
            $input = json_decode($input, true);
        }

        if ($input['type'] === 'FeatureCollection') {
            $geometries = array_map(fn (array $feature) => $this->parse($feature), $input['features']);

            // TODO: Think about what should happen here
            // FeatureCollection is a speficic GeoJSON format and therefore can't be generalized
            // --> How to handle?
            return $geometries;
        }

        if ($input['type'] === 'Feature') {
            return $this->parseGeometry($input['geometry']);
        }

        return $this->parseGeometry($input);
    }

    protected function parseGeometry(array $geometryData): Geometry
    {
        $type = $geometryData['type'];
        if ($type === 'GeometryCollection') {
            return $this->parseGeomeryCollection($geometryData);
        }

        $parseMethod = "parse$type";

        return $this->$parseMethod($geometryData['coordinates']);
    }

    protected function parseGeomeryCollection(array $geometryCollectionData): Geometry
    {
        $geometries = $geometryCollectionData['geometries'];
        if (empty($geometries)) {
            throw new \RuntimeException('Invalid GeoJSON: GeometryCollection must have at least one geometry');
        }

        $geometries = array_map(fn (array $geometry) => $this->parseGeometry($geometry), $geometries);

        return $this->factory->createGeometryCollection(Dimension::DIMENSION_2D, null, $geometries);
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

        return $this->factory->createLineString(Dimension::DIMENSION_2D, null, $points);
    }

    public function parseMultiLineString(array $coordinates): Geometry
    {
        $lines = array_map(fn (array $coords) => $this->parseLineString($coords), $coordinates);

        return $this->factory->createMultiLineString(Dimension::DIMENSION_2D, null, $lines);
    }

    public function parsePolygon(array $coordinates): Geometry
    {
        $lines = array_map(fn (array $coords) => $this->parseLineString($coords), $coordinates);

        return $this->factory->createPolygon(Dimension::DIMENSION_2D, null, $lines);
    }

    public function parseMultiPoint(array $coordinates): Geometry
    {
        $points = array_map(fn (array $coords) => $this->parsePoint($coords), $coordinates);

        return $this->factory->createMultiPoint(Dimension::DIMENSION_2D, null, $points);
    }

    public function parseMultiPolygon(array $coordinates): Geometry
    {
        $polygons = array_map(fn (array $coords) => $this->parsePolygon($coords), $coordinates);

        return $this->factory->createMultiPolygon(Dimension::DIMENSION_2D, null, $polygons);
    }
}
