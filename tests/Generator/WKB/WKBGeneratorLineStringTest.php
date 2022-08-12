<?php

use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});

test('can generate 2D WKB LineString', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940');
})->group('WKB LineString');

test('can generate 2D WKB LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('0102000020E610000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940');
})->group('WKB LineString');

test('can generate 3DZ WKB LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 3DZ WKB LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('01020000A0E610000002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 3DM WKB LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 3DM WKB LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('0102000060E610000002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 4D WKB LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('01020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640');
})->group('WKB LineString');

test('can generate 4D WKB LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('01020000E0E610000002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640');
})->group('WKB LineString');
