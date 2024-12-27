<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiLineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator();
});

test('can generate empty 2D WKT MultiLineString', function () {
    $multiLineString = MultiLineString::make([]);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING EMPTY');
})->group('WKT MultiLineString');

test('can generate empty 3DZ WKT MultiLineString', function () {
    $multiLineString = MultiLineString::make([], dimension: Dimension::DIMENSION_3DZ);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING Z EMPTY');
})->group('WKT MultiLineString');

test('can generate empty 3DM WKT MultiLineString', function () {
    $multiLineString = MultiLineString::make([], dimension: Dimension::DIMENSION_3DM);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING M EMPTY');
})->group('WKT MultiLineString');

test('can generate empty 4D WKT MultiLineString', function () {
    $multiLineString = MultiLineString::make([], dimension: Dimension::DIMENSION_4D);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING ZM EMPTY');
})->group('WKT MultiLineString');

test('can generate empty 2D WKT MultiLineString with SRID', function () {
    $multiLineString = MultiLineString::make([], srid: 4326);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING EMPTY');
})->group('WKT MultiLineString');

test('can generate empty 3DZ WKT MultiLineString with SRID', function () {
    $multiLineString = MultiLineString::make([], srid: 4326, dimension: Dimension::DIMENSION_3DZ);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING Z EMPTY');
})->group('WKT MultiLineString');

test('can generate empty 3DM WKT MultiLineString with SRID', function () {
    $multiLineString = MultiLineString::make([], srid: 4326, dimension: Dimension::DIMENSION_3DM);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING M EMPTY');
})->group('WKT MultiLineString');

test('can generate empty 4D WKT MultiLineString with SRID', function () {
    $multiLineString = MultiLineString::make([], srid: 4326, dimension: Dimension::DIMENSION_4D);
    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING ZM EMPTY');
})->group('WKT MultiLineString');

test('can generate 2D WKT MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 49.12345);
    $point4 = Point::make(6.12345, 48.12345);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))');
})->group('WKT MultiLineString');

test('can generate 2D WKT MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))');
})->group('WKT MultiLineString');

test('can generate 3DZ WKT MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 49.12345, 30);
    $point4 = Point::make(6.12345, 48.12345, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING Z((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))');
})->group('WKT MultiLineString');

test('can generate 3DZ WKT MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING Z((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))');
})->group('WKT MultiLineString');

test('can generate 3DM WKT MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 49.12345, null, 30);
    $point4 = Point::make(6.12345, 48.12345, null, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING M((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))');
})->group('WKT MultiLineString');

test('can generate 3DM WKT MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, null, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, null, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING M((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))');
})->group('WKT MultiLineString');

test('can generate 4D WKT MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 49.12345, 30, 32);
    $point4 = Point::make(6.12345, 48.12345, 40, 42);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22),(7.12345 49.12345 30 32,6.12345 48.12345 40 42))');
})->group('WKT MultiLineString');

test('can generate 4D WKT MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30, 32);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40, 42);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22),(7.12345 49.12345 30 32,6.12345 48.12345 40 42))');
})->group('WKT MultiLineString');
