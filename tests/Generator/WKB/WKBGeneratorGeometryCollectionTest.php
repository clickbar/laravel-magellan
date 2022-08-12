<?php

use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});

test('can generate 2D WKB GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB GeometryCollection');

test('can generate 2D WKB GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000020E6100000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB GeometryCollection');

test('can generate 3DZ WKB GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 48.12345, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000080030000000101000080E561A1D6343F20407958A835CD0F49400000000000002440010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB GeometryCollection');

test('can generate 3DZ WKB GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('01070000A0E6100000030000000101000080E561A1D6343F20407958A835CD0F49400000000000002440010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB GeometryCollection');

test('can generate 3DM WKB GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 48.12345, null, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000040030000000101000040E561A1D6343F20407958A835CD0F49400000000000002440010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB GeometryCollection');

test('can generate 3DM WKB GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 30);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000060E6100000030000000101000040E561A1D6343F20407958A835CD0F49400000000000002440010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB GeometryCollection');

test('can generate 4D WKB GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 48.12345, 30, 32);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('01070000C00300000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840');
})->group('WKB GeometryCollection');

test('can generate 4D WKB GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30, 32);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('01070000E0E61000000300000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840');
})->group('WKB GeometryCollection');
