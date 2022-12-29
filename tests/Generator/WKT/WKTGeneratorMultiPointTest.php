<?php

use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator();
});

test('can generate 2D WKT MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 49.12345);
    $point4 = Point::make(6.12345, 48.12345);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)');
})->group('WKT MultiPoint');

test('can generate 2D WKT MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('SRID=4326;MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)');
})->group('WKT MultiPoint');

test('can generate 3DZ WKT MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 49.12345, 30);
    $point4 = Point::make(6.12345, 48.12345, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('MULTIPOINT Z(8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)');
})->group('WKT MultiPoint');

test('can generate 3DZ WKT MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('SRID=4326;MULTIPOINT Z(8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)');
})->group('WKT MultiPoint');

test('can generate 3DM WKT MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 49.12345, null, 30);
    $point4 = Point::make(6.12345, 48.12345, null, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('MULTIPOINT M(8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)');
})->group('WKT MultiPoint');

test('can generate 3DM WKT MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, null, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, null, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('SRID=4326;MULTIPOINT M(8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)');
})->group('WKT MultiPoint');

test('can generate 4D WKT MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 49.12345, 30, 32);
    $point4 = Point::make(6.12345, 48.12345, 40, 42);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('MULTIPOINT ZM(8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 49.12345 30 32,6.12345 48.12345 40 42)');
})->group('WKT MultiPoint');

test('can generate 4D WKT MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30, 32);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40, 42);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('SRID=4326;MULTIPOINT ZM(8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 49.12345 30 32,6.12345 48.12345 40 42)');
})->group('WKT MultiPoint');
