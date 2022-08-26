<?php

use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKTParser::class);
});

test('can parse 2D WKT GeometryCollection', function () {
    $geometryCollectionWKT = 'GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_2D);

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
})->group('WKT GeometryCollection');

test('can parse 2D WKT GeometryCollection with SRID', function () {
    $geometryCollectionWKT = 'SRID=4326;GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_2D);
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
})->group('WKT GeometryCollection');

test('can parse 3DZ WKT GeometryCollection', function () {
    $geometryCollectionWKT = 'GEOMETRYCOLLECTION Z (POINT Z (8.12345 50.12345 10),LINESTRING Z (8.12345 50.12345 10,9.12345 51.12345 20),POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DZ);

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
})->group('WKT GeometryCollection');

test('can parse 3DZ WKT GeometryCollection with SRID', function () {
    $geometryCollectionWKT = 'SRID=4326;GEOMETRYCOLLECTION Z (POINT Z(8.12345 50.12345 10),LINESTRING Z(8.12345 50.12345 10,9.12345 51.12345 20),POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);
    expect($geometryCollection)->geometryHasDimension(Dimension::DIMENSION_3DZ);
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
})->group('WKT GeometryCollection');

test('can parse 3DM WKT GeometryCollection', function () {
    $geometryCollectionWKT = 'GEOMETRYCOLLECTION M (POINT M (8.12345 50.12345 10),LINESTRING M (8.12345 50.12345 10,9.12345 51.12345 20),POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

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
})->group('WKT GeometryCollection');

test('can parse 3DM WKT GeometryCollection with SRID', function () {
    $geometryCollectionWKT = 'SRID=4326;GEOMETRYCOLLECTION M (POINT M(8.12345 50.12345 10),LINESTRING M(8.12345 50.12345 10,9.12345 51.12345 20),POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

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
})->group('WKT GeometryCollection');

test('can parse 4D WKT GeometryCollection', function () {
    $geometryCollectionWKT = 'GEOMETRYCOLLECTION ZM (POINT ZM (8.12345 50.12345 10 12),LINESTRING ZM (8.12345 50.12345 10 12,9.12345 51.12345 20 22),POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

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
})->group('WKT GeometryCollection');

test('can parse 4D WKT GeometryCollection with SRID', function () {
    $geometryCollectionWKT = 'SRID=4326;GEOMETRYCOLLECTION ZM (POINT ZM (8.12345 50.12345 10 12),LINESTRING ZM (8.12345 50.12345 10 12,9.12345 51.12345 20 22),POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

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
})->group('WKT GeometryCollection');
