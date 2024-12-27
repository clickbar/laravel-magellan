<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});

test('can generate empty 2D WKB Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([]);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('010600000000000000');
})->group('WKB MultiPolygon');

test('can generate empty 3DZ WKB Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([], dimension: Dimension::DIMENSION_3DZ);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('010600008000000000');
})->group('WKB MultiPolygon');

test('can generate empty 3DM WKB Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([], dimension: Dimension::DIMENSION_3DM);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('010600004000000000');
})->group('WKB MultiPolygon');

test('can generate empty 4D WKB Simple MultiPolygon', function () {
    $multiPolygon = MultiPolygon::make([], dimension: Dimension::DIMENSION_4D);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000C000000000');
})->group('WKB MultiPolygon');

test('can generate empty 2D WKB Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('0106000020E610000000000000');
})->group('WKB MultiPolygon');

test('can generate empty 3DZ WKB Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326, dimension: Dimension::DIMENSION_3DZ);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000A0E610000000000000');
})->group('WKB MultiPolygon');

test('can generate empty 3DM WKB Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326, dimension: Dimension::DIMENSION_3DM);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('0106000060E610000000000000');
})->group('WKB MultiPolygon');

test('can generate empty 4D WKB Simple MultiPolygon with SRID', function () {
    $multiPolygon = MultiPolygon::make([], srid: 4326, dimension: Dimension::DIMENSION_4D);
    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000E0E610000000000000');
})->group('WKB MultiPolygon');

test('can generate 2D WKB Simple MultiPolygon', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940');
})->group('WKB MultiPolygon');

test('can generate 2D WKB Simple MultiPolygon with SRID', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('0106000020E61000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940');
})->group('WKB MultiPolygon');

test('can generate 3DZ WKB Simple MultiPolygon', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000800200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 3DZ WKB Simple MultiPolygon with SRID', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000A0E61000000200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 3DM WKB Simple MultiPolygon', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000400200000001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000400100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 3DM WKB Simple MultiPolygon with SRID', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('0106000060E61000000200000001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000400100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 4D WKB Simple MultiPolygon', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000C00200000001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001030000C00100000004000000E561A1D6343F24407958A835CD0F494000000000000024400000000000002840E561A1D6343F26407958A835CD8F494000000000000024400000000000002840E561A1D6343F22407958A835CD0F484000000000000024400000000000002840E561A1D6343F24407958A835CD0F494000000000000024400000000000002840');
})->group('WKB MultiPolygon');

test('can generate 4D WKB Simple MultiPolygon with SRID', function () {
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

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000E0E61000000200000001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001030000C00100000004000000E561A1D6343F24407958A835CD0F494000000000000024400000000000002840E561A1D6343F26407958A835CD8F494000000000000024400000000000002840E561A1D6343F22407958A835CD0F484000000000000024400000000000002840E561A1D6343F24407958A835CD0F494000000000000024400000000000002840');
})->group('WKB MultiPolygon');
