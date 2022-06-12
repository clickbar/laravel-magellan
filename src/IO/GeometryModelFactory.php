<?php

namespace Clickbar\Postgis\IO;

use Clickbar\Postgis\GeometriesOld\GeometryCollection;
use Clickbar\Postgis\GeometriesOld\LineString;
use Clickbar\Postgis\GeometriesOld\MultiLineString;
use Clickbar\Postgis\GeometriesOld\MultiPoint;
use Clickbar\Postgis\GeometriesOld\MultiPolygon;
use Clickbar\Postgis\GeometriesOld\Point;
use Clickbar\Postgis\GeometriesOld\Polygon;

interface GeometryModelFactory
{
    /**
     * Creates a Point for the given dimension, optional SRID and Coordinate.
     *
     * If the Coordinate argument is `null`, it should create an empty point.
     */
    public function createPoint(
        Dimension $dimension,
        ?int $srid,
        ?Coordinate $coordinates,
    ): Point;

    /**
     * Creates a LineString for the given dimension, optional SRID and Points.
     *
     * The `$points` arguments yields Points created through the createPoint()
     * method from this Factory.
     *
     * If the iterable does not yield any Points, it should create an empty
     * LineString.
     */
    public function createLineString(
        Dimension $dimension,
        ?int $srid,
        iterable $points,
    ): LineString;

    /**
     * Creates a LinearRing for the given dimension, optional SRID and Points.
     *
     * The `$points` arguments yields Points created through the createPoint()
     * method from this Factory.
     *
     * If the iterable does not yield any Points, it should create an empty
     * LinearRing.
     *
     * Note, that a LinearRing represents a closed LineString. If the
     * implementation does not provide a dedicated implementation for a
     * LinearRing, it can return a LineString instead.
     */
    public function createLinearRing(
        Dimension $dimension,
        ?int $srid,
        iterable $points,
    ): LineString;

    /**
     * Creates a Polygon for the given dimension, optional SRID and
     * LinearRings.
     *
     * The `$linearRings` arguments yields LinearRings created through the
     * createLinearRing() method from this Factory.
     *
     * If the iterable does not yield any LinearRings, it should create an empty
     * Polygon.
     */
    public function createPolygon(
        Dimension $dimension,
        ?int $srid,
        iterable $linearRings,
    ): Polygon;

    /**
     * Creates a MultiPoint for the given dimension, optional SRID and
     * Points.
     *
     * The `$points` arguments yields Points created through the createPoint()
     * method from this Factory.
     *
     * If the iterable does not yield any Points, it should create an empty
     * MultiPoint.
     */
    public function createMultiPoint(
        Dimension $dimension,
        ?int $srid,
        iterable $points,
    ): MultiPoint;

    /**
     * Creates a MultiLineString for the given dimension, optional SRID and
     * LineStrings.
     *
     * The `$lineString` arguments yields LineStrings created through the
     * createLineString() method from this Factory.
     *
     * If the iterable does not yield any LineStrings, it should create an empty
     * MultiLineString.
     */
    public function createMultiLineString(
        Dimension $dimension,
        ?int $srid,
        iterable $lineStrings,
    ): MultiLineString;

    /**
     * Creates a MultiPolygon for the given dimension, optional SRID and
     * Polygons.
     *
     * The `$polygons` arguments yields Polygons created through the
     * createPolygon() method from this Factory.
     *
     * If the iterable does not yield any Polygons, it should create an empty
     * MultiPolygon.
     */
    public function createMultiPolygon(
        Dimension $dimension,
        ?int $srid,
        iterable $polygons,
    ): MultiPolygon;

    /**
     * Creates a GeometryCollection for the given dimension, optional SRID and
     * geometries.
     *
     * The `$geometries` arguments yields geometries created through any of the
     * create*() methods from this Factory.
     *
     * If the iterable does not yield any geometries, it should create an empty
     * GeometryCollection.
     */
    public function createGeometryCollection(
        Dimension $dimension,
        ?int $srid,
        iterable $geometries,
    ): GeometryCollection;
}
