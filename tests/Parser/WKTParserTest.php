<?php

use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\MultiLineString;
use Clickbar\Magellan\Geometries\MultiPoint;
use Clickbar\Magellan\Geometries\MultiPolygon;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;

beforeEach(function () {
    $this->parser = new WKTParser(new GeometryFactory());
});



test('can parse 2D WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
})->group('WKT MultiPolygon');

test('can parse 2D WKT Simple MultiPolygon with SRID', function () {
    $multiPolygonWKT = 'SRID=4326;MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT MultiPolygon');

test('can parse 3DZ WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON Z (((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
})->group('WKT MultiPolygon');


test('can parse 3DZ WKT Simple MultiPolygon with SRID', function () {
    $multiPolygonWKT = 'SRID=4326;MULTIPOLYGON Z (((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT MultiPolygon');

test('can parse 2D WKT GeometryCollection', function () {
    $geometryCollectionWKT = 'GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

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
})->group('WKT GeometryCollection');

test('can parse 2D WKT GeometryCollection with SRID', function () {
    $geometryCollectionWKT = 'SRID=4326;GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getSrid())->toBe(4326);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getSrid())->toBe(4326);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getSrid())->toBe(4326);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT GeometryCollection');
