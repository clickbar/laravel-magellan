<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator;
});

test('can generate empty 2D WKT Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([]);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON EMPTY');
})->group('WKT MultiPolygon');

test('can generate empty 3DZ WKT Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([], dimension: Dimension::DIMENSION_3DZ);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON Z EMPTY');
})->group('WKT MultiPolygon');

test('can generate empty 3DM WKT Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([], dimension: Dimension::DIMENSION_3DM);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON M EMPTY');
})->group('WKT MultiPolygon');

test('can generate empty 4D WKT Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([], dimension: Dimension::DIMENSION_4D);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON ZM EMPTY');
})->group('WKT MultiPolygon');

test('can generate empty 2D WKT Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON EMPTY');
})->group('WKT MultiPolygon');

test('can generate empty 3DZ WKT Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326, dimension: Dimension::DIMENSION_3DZ);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON Z EMPTY');
})->group('WKT MultiPolygon');

test('can generate empty 3DM WKT Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326, dimension: Dimension::DIMENSION_3DM);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON M EMPTY');
})->group('WKT MultiPolygon');

test('can generate empty 4D WKT Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326, dimension: Dimension::DIMENSION_4D);
    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON ZM EMPTY');
})->group('WKT MultiPolygon');

test('can generate 2D WKT Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);
    $point4 = Point::make(10.12345, 50.12345);
    $point5 = Point::make(11.12345, 51.12345);
    $point6 = Point::make(9.12345, 48.12345);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))');
})->group('WKT MultiPolygon');

test('can generate 2D WKT Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $point4 = Point::makeGeodetic(50.12345, 10.12345);
    $point5 = Point::makeGeodetic(51.12345, 11.12345);
    $point6 = Point::makeGeodetic(48.12345, 9.12345);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))');
})->group('WKT MultiPolygon');

test('can generate 3DZ WKT Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 10);
    $point3 = Point::make(7.12345, 48.12345, 10);
    $point4 = Point::make(10.12345, 50.12345, 10);
    $point5 = Point::make(11.12345, 51.12345, 10);
    $point6 = Point::make(9.12345, 48.12345, 10);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON Z(((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))');
})->group('WKT MultiPolygon');

test('can generate 3DZ WKT Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $point4 = Point::makeGeodetic(50.12345, 10.12345, 10);
    $point5 = Point::makeGeodetic(51.12345, 11.12345, 10);
    $point6 = Point::makeGeodetic(48.12345, 9.12345, 10);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON Z(((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))');
})->group('WKT MultiPolygon');

test('can generate 3DM WKT Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 10);
    $point3 = Point::make(7.12345, 48.12345, null, 10);
    $point4 = Point::make(10.12345, 50.12345, null, 10);
    $point5 = Point::make(11.12345, 51.12345, null, 10);
    $point6 = Point::make(9.12345, 48.12345, null, 10);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON M(((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))');
})->group('WKT MultiPolygon');

test('can generate 3DM WKT Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 10);
    $point4 = Point::makeGeodetic(50.12345, 10.12345, null, 10);
    $point5 = Point::makeGeodetic(51.12345, 11.12345, null, 10);
    $point6 = Point::makeGeodetic(48.12345, 9.12345, null, 10);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON M(((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))');
})->group('WKT MultiPolygon');

test('can generate 4D WKT Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 10, 12);
    $point3 = Point::make(7.12345, 48.12345, 10, 12);
    $point4 = Point::make(10.12345, 50.12345, 10, 12);
    $point5 = Point::make(11.12345, 51.12345, 10, 12);
    $point6 = Point::make(9.12345, 48.12345, 10, 12);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON ZM(((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12)),((10.12345 50.12345 10 12,11.12345 51.12345 10 12,9.12345 48.12345 10 12,10.12345 50.12345 10 12)))');
})->group('WKT MultiPolygon');

test('can generate 4D WKT Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10, 12);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10, 12);
    $point4 = Point::makeGeodetic(50.12345, 10.12345, 10, 12);
    $point5 = Point::makeGeodetic(51.12345, 11.12345, 10, 12);
    $point6 = Point::makeGeodetic(48.12345, 9.12345, 10, 12);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON ZM(((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12)),((10.12345 50.12345 10 12,11.12345 51.12345 10 12,9.12345 48.12345 10 12,10.12345 50.12345 10 12)))');
})->group('WKT MultiPolygon');
