<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator();
});

test('can generate empty 2D WKT LineString', function () {
    $lineString = LineString::make([]);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING EMPTY');
})->group('WKT LineString');

test('can generate empty 3DZ WKT LineString', function () {
    $lineString = LineString::make([], dimension: Dimension::DIMENSION_3DZ);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING Z EMPTY');
})->group('WKT LineString');

test('can generate empty 3DM WKT LineString', function () {
    $lineString = LineString::make([], dimension: Dimension::DIMENSION_3DM);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING M EMPTY');
})->group('WKT LineString');

test('can generate empty 4D WKT LineString', function () {
    $lineString = LineString::make([], dimension: Dimension::DIMENSION_4D);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING ZM EMPTY');
})->group('WKT LineString');

test('can generate empty 2D WKT LineString with SRID', function () {
    $lineString = LineString::make([], srid: 4326);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING EMPTY');
})->group('WKT LineString');

test('can generate empty 3DZ WKT LineString with SRID', function () {
    $lineString = LineString::make([], srid: 4326, dimension: Dimension::DIMENSION_3DZ);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING Z EMPTY');
})->group('WKT LineString');

test('can generate empty 3DM WKT LineString with SRID', function () {
    $lineString = LineString::make([], srid: 4326, dimension: Dimension::DIMENSION_3DM);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING M EMPTY');
})->group('WKT LineString');

test('can generate empty 4D WKT LineString with SRID', function () {
    $lineString = LineString::make([], srid: 4326, dimension: Dimension::DIMENSION_4D);
    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING ZM EMPTY');
})->group('WKT LineString');

test('can generate 2D WKT LineString', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING(8.12345 50.12345,9.12345 51.12345)');
})->group('WKT LineString');

test('can generate 2D WKT LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING(8.12345 50.12345,9.12345 51.12345)');
})->group('WKT LineString');

test('can generate 3DZ WKT LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING Z(8.12345 50.12345 10,9.12345 51.12345 20)');
})->group('WKT LineString');

test('can generate 3DZ WKT LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING Z(8.12345 50.12345 10,9.12345 51.12345 20)');
})->group('WKT LineString');

test('can generate 3DM WKT LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING M(8.12345 50.12345 10,9.12345 51.12345 20)');
})->group('WKT LineString');

test('can generate 3DM WKT LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING M(8.12345 50.12345 10,9.12345 51.12345 20)');
})->group('WKT LineString');

test('can generate 4D WKT LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING ZM(8.12345 50.12345 10 12,9.12345 51.12345 20 22)');
})->group('WKT LineString');

test('can generate 4D WKT LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING ZM(8.12345 50.12345 10 12,9.12345 51.12345 20 22)');
})->group('WKT LineString');
