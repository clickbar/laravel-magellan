<?php

use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;

beforeEach(function () {
    $this->parser = new WKBParser(new GeometryFactory());
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

test('can parse 2D WKB GeometryCollection with SRID', function () {
    $geometryCollectionWKB = '0107000020E6100000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]))]), 4326)

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

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
})->group('WKB GeometryCollection');
