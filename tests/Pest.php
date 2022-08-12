<?php

use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\MultiLineString;
use Clickbar\Magellan\Geometries\MultiPoint;
use Clickbar\Magellan\Geometries\MultiPolygon;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Checks if a geometry has a dimension and that it's everywhere set correctly.
 *
 * @param $dimension Dimension
 */
expect()->extend('geometryHasDimension', function (Dimension $dimension) {
    if ($this->value instanceof Point) {
        expect($this->value->getDimension())->toBe($dimension);
    }

    if ($this->value instanceof LineString) {
        foreach ($this->value->getPoints() as $point) {
            expect($point)->geometryHasDimension($dimension);
        }
    }

    if ($this->value instanceof MultiLineString) {
        foreach ($this->value->getLineStrings() as $lineString) {
            expect($lineString)->geometryHasDimension($dimension);
        }
    }

    if ($this->value instanceof MultiPoint) {
        foreach ($this->value->getPoints() as $point) {
            expect($point)->geometryHasDimension($dimension);
        }
    }

    if ($this->value instanceof Polygon) {
        foreach ($this->value->getLineStrings() as $lineString) {
            expect($lineString)->geometryHasDimension($dimension);
        }
    }

    if ($this->value instanceof MultiPolygon) {
        assert($this->value->getDimension() === $dimension);

        foreach ($this->value->getPolygons() as $polygon) {
            expect($polygon)->geometryHasDimension($dimension);
        }
    }

    if ($this->value instanceof GeometryCollection) {
        expect($this->value->getDimension())->toBe($dimension);

        foreach ($this->value->getGeometries() as $geometry) {
            expect($geometry)->geometryHasDimension($dimension);
        }
    }
});

/**
 * Checks if a geometry has a Srid and that it's everywhere set correctly.
 *
 * @param $srid int
 */
expect()->extend('geometryHasSrid', function (int $srid) {
    assert($this->value instanceof Geometry);

    if ($this->value instanceof Point) {
        assert($this->value->getSrid() === $srid);
    }

    if ($this->value instanceof LineString) {
        assert($this->value->getSrid() === $srid);
        assert(count($this->value->getPoints()) >= 2);

        foreach ($this->value->getPoints() as $point) {
            expect($point)->geometryHasSrid($srid);
        }
    }

    if ($this->value instanceof MultiLineString) {
        assert($this->value->getSrid() === $srid);

        foreach ($this->value->getLineStrings() as $lineString) {
            expect($lineString)->geometryHasSrid($srid);
        }
    }

    if ($this->value instanceof MultiPoint) {
        assert($this->value->getSrid() === $srid);

        foreach ($this->value->getPoints() as $point) {
            expect($point)->geometryHasSrid($srid);
        }
    }

    if ($this->value instanceof Polygon) {
        assert($this->value->getSrid() === $srid);

        foreach ($this->value->getLineStrings() as $lineString) {
            expect($lineString)->geometryHasSrid($srid);
        }
    }

    if ($this->value instanceof MultiPolygon) {
        assert($this->value->getSrid() === $srid);

        foreach ($this->value->getPolygons() as $polygon) {
            expect($polygon)->geometryHasSrid($srid);
        }
    }

    if ($this->value instanceof GeometryCollection) {
        assert($this->value->getSrid() === $srid);

        foreach ($this->value->getGeometries() as $geometry) {
            expect($geometry)->geometryHasSrid($srid);
        }
    }
});
