<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator();
});

test('can generate 2D WKT Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345))');
})->group('WKT Polygon');

test('can generate 2D WKT Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345))');
})->group('WKT Polygon');

test('can generate 3DZ WKT Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 48.12345, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))');
})->group('WKT Polygon');

test('can generate 3DZ WKT Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))');
})->group('WKT Polygon');

test('can generate 3DM WKT Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 48.12345, null, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))');
})->group('WKT Polygon');

test('can generate 3DM WKT Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))');
})->group('WKT Polygon');

test('can generate 4D WKT Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 48.12345, 30, 32);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12))');
})->group('WKT Polygon');

test('can generate 4D WKT Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30, 32);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12))');
})->group('WKT Polygon');
