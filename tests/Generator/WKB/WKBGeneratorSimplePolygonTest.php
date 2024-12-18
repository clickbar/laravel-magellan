<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator;
});

test('can generate empty 2D WKB Simple Polygon', function () {
    $polygon = Polygon::make([]);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('010300000000000000');
})->group('WKB Polygon');

test('can generate empty 3DZ WKB Simple Polygon', function () {
    $polygon = Polygon::make([], dimension: Dimension::DIMENSION_3DZ);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('010300008000000000');
})->group('WKB Polygon');

test('can generate empty 3DM WKB Simple Polygon', function () {
    $polygon = Polygon::make([], dimension: Dimension::DIMENSION_3DM);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('010300004000000000');
})->group('WKB Polygon');

test('can generate empty 4D WKB Simple Polygon', function () {
    $polygon = Polygon::make([], dimension: Dimension::DIMENSION_4D);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000C000000000');
})->group('WKB Polygon');

test('can generate empty 2D WKB Simple Polygon with SRID', function () {
    $polygon = Polygon::make([], srid: 4326);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E610000000000000');
})->group('WKB Polygon');

test('can generate empty 3DZ WKB Simple Polygon with SRID', function () {
    $polygon = Polygon::make([], srid: 4326, dimension: Dimension::DIMENSION_3DZ);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000A0E610000000000000');
})->group('WKB Polygon');

test('can generate empty 3DM WKB Simple Polygon with SRID', function () {
    $polygon = Polygon::make([], srid: 4326, dimension: Dimension::DIMENSION_3DM);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000060E610000000000000');
})->group('WKB Polygon');

test('can generate empty 4D WKB Simple Polygon with SRID', function () {
    $polygon = Polygon::make([], srid: 4326, dimension: Dimension::DIMENSION_4D);
    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000E0E610000000000000');
})->group('WKB Polygon');

test('can generate 2D WKB Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB Polygon');

test('can generate 2D WKB Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E61000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB Polygon');

test('can generate 3DZ WKB Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 48.12345, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 3DZ WKB Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000A0E61000000100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 3DM WKB Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 48.12345, null, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 3DM WKB Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000060E61000000100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 4D WKB Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 48.12345, 30, 32);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840');
})->group('WKB Polygon');

test('can generate 4D WKB Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30, 32);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000E0E61000000100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840');
})->group('WKB Polygon');
