<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\GeometryCollection;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKBParser::class);
});

test('can parse 2D WKB GeometryCollection', function () {
    $geometryCollectionWKB = '0107000000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]))])

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
})->group('WKB GeometryCollection');

test('can parse empty 2D WKB GeometryCollection', function () {
    $geometryCollectionWKB = '010700000000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($geometryCollection)->geometryHasSrid(null);
})->group('WKB GeometryCollection');

test('can parse 2D WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '0107000020E6100000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]))]), 4326)

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasSrid(4326);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
})->group('WKB GeometryCollection');

test('can parse empty 2D WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '0107000020E610000000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($geometryCollection)->geometryHasSrid(4326);
})->group('WKB GeometryCollection');

test('can parse 3DZ WKB GeometryCollection', function () {
    $geometryCollectionWKB = '0107000080030000000101000080E561A1D6343F20407958A835CD0F49400000000000002440010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makeline(st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20), st_makepoint(7.12345, 48.12345, 30), st_makepoint(8.12345, 50.12345, 10)]))])

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
})->group('WKB GeometryCollection');

test('can parse empty 3DZ WKB GeometryCollection', function () {
    $geometryCollectionWKB = '010700008000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($geometryCollection)->geometryHasSrid(null);
})->group('WKB GeometryCollection');

test('can parse 3DZ WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '01070000A0E6100000030000000101000080E561A1D6343F20407958A835CD0F49400000000000002440010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makeline(st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20), st_makepoint(7.12345, 48.12345, 30), st_makepoint(8.12345, 50.12345, 10)]))]), 4326)

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasSrid(4326);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
})->group('WKB GeometryCollection');

test('can parse empty 3DZ WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '01070000A0E610000000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($geometryCollection)->geometryHasSrid(4326);
})->group('WKB GeometryCollection');

test('can parse 3DM WKB GeometryCollection', function () {
    $geometryCollectionWKB = '0107000040030000000101000040E561A1D6343F20407958A835CD0F49400000000000002440010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_collect(ARRAY[st_makepointm(8.12345, 50.12345, 10), st_makeline(st_makepointm(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20)), st_makepolygon(st_makeline(ARRAY[st_makepointm(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20), st_makepointm(7.12345, 48.12345, 30), st_makepointm(8.12345, 50.12345, 10)]))])

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DM);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getM())->toBe(10.0);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getM())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getM())->toBe(20.0);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKB GeometryCollection');

test('can parse empty 3DM WKB GeometryCollection', function () {
    $geometryCollectionWKB = '010700004000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($geometryCollection)->geometryHasSrid(null);
})->group('WKB GeometryCollection');

test('can parse 3DM WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '0107000060E6100000030000000101000040E561A1D6343F20407958A835CD0F49400000000000002440010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F4940000000000000344001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_setsrid(st_collect(ARRAY[st_makepointm(8.12345, 50.12345, 10), st_makeline(st_makepointm(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20)), st_makepolygon(st_makeline(ARRAY[st_makepointm(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20), st_makepointm(7.12345, 48.12345, 30), st_makepointm(8.12345, 50.12345, 10)]))]), 4326)

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($geometryCollection)->geometryHasSrid(4326);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getM())->toBe(10.0);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getM())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getM())->toBe(20.0);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKB GeometryCollection');

test('can parse empty 3DM WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '0107000060E610000000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($geometryCollection)->geometryHasSrid(4326);
})->group('WKB GeometryCollection');

test('can parse 4D WKB GeometryCollection', function () {
    $geometryCollectionWKB = '01070000C00300000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makeline(st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22), st_makepoint(7.12345, 48.12345, 30, 32), st_makepoint(8.12345, 50.12345, 10, 12)]))])

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_4D);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
    expect($point->getM())->toBe(12.0);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[0]->getM())->toBe(12.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
    expect($lineString->getPoints()[1]->getM())->toBe(22.0);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(22.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(32.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKB GeometryCollection');

test('can parse empty 4D WKB GeometryCollection', function () {
    $geometryCollectionWKB = '01070000C000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($geometryCollection)->geometryHasSrid(null);
})->group('WKB GeometryCollection');

test('can parse 4D WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '01070000E0E61000000300000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makeline(st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22), st_makepoint(7.12345, 48.12345, 30, 32), st_makepoint(8.12345, 50.12345, 10, 12)]))]), 4326)

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($geometryCollection)->geometryHasSrid(4326);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
    expect($point->getM())->toBe(12.0);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[0]->getM())->toBe(12.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
    expect($lineString->getPoints()[1]->getM())->toBe(22.0);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(22.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(32.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKB GeometryCollection');

test('can parse empty 4D WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '01070000E0E610000000000000';

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection->getGeometries())->toBeEmpty();
    expect($geometryCollection->isEmpty())->toBeTrue();
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($geometryCollection)->geometryHasSrid(4326);
})->group('WKB GeometryCollection');
