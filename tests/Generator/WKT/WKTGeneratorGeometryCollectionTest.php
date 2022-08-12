<?php

use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator();
});


test('can generate 2D WKT GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))');
})->group('WKT GeometryCollection');

test('can generate 2D WKT GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('SRID=4326;GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))');
})->group('WKT GeometryCollection');


test('can generate 3DZ WKT GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 48.12345, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('GEOMETRYCOLLECTION Z(POINT Z(8.12345 50.12345 10),LINESTRING Z(8.12345 50.12345 10,9.12345 51.12345 20),POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))');
})->group('WKT GeometryCollection');

test('can generate 3DZ WKT GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('SRID=4326;GEOMETRYCOLLECTION Z(POINT Z(8.12345 50.12345 10),LINESTRING Z(8.12345 50.12345 10,9.12345 51.12345 20),POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))');
})->group('WKT GeometryCollection');


test('can generate 3DM WKT GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 48.12345, null, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('GEOMETRYCOLLECTION M(POINT M(8.12345 50.12345 10),LINESTRING M(8.12345 50.12345 10,9.12345 51.12345 20),POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))');
})->group('WKT GeometryCollection');

test('can generate 3DM WKT GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('SRID=4326;GEOMETRYCOLLECTION M(POINT M(8.12345 50.12345 10),LINESTRING M(8.12345 50.12345 10,9.12345 51.12345 20),POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))');
})->group('WKT GeometryCollection');

test('can generate 4D WKT GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 48.12345, 30, 32);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('GEOMETRYCOLLECTION ZM(POINT ZM(8.12345 50.12345 10 12),LINESTRING ZM(8.12345 50.12345 10 12,9.12345 51.12345 20 22),POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12)))');
})->group('WKT GeometryCollection');

test('can generate 4D WKT GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30, 32);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('SRID=4326;GEOMETRYCOLLECTION ZM(POINT ZM(8.12345 50.12345 10 12),LINESTRING ZM(8.12345 50.12345 10 12,9.12345 51.12345 20 22),POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12)))');
})->group('WKT GeometryCollection');
