<?php

use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;

beforeEach(function () {
    $this->parser = new WKBParser(new GeometryFactory());
});


test('can parse 2D WKB Simple Polygon', function () {
    $polygonWKB = '01030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]));

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
})->group('WKB Polygon');

test('can parse 2D WKB Simple Polygon with SRID', function () {
    $polygonWKB = '0103000020E61000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)])), 4326)

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon)->geometryHasSrid(4326);
})->group('WKB Polygon');

test('can parse 3DZ WKB Simple Polygon', function () {
    $polygonWKB = '01030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345,20), st_makepoint(7.12345, 48.12345,30), st_makepoint(8.12345, 50.12345,10)]))

    $polygon = $this->parser->parse($polygonWKB);

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
})->group('WKB Polygon');

test('can parse 3DZ WKB Simple Polygon with SRID', function () {
    $polygonWKB = '01030000A0E61000000100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345,20), st_makepoint(7.12345, 48.12345,30), st_makepoint(8.12345, 50.12345,10)])), 4326)

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasSrid(4326);
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
})->group('WKB Polygon');

test('can parse 3DM WKB Simple Polygon', function () {
    $polygonWKB = '01030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_makepolygon(st_makeline(ARRAY[st_makepointM(8.12345, 50.12345, 10), st_makepointM(9.12345, 51.12345,20), st_makepointM(7.12345, 48.12345,30), st_makepointM(8.12345, 50.12345,10)]))

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
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
})->group('WKB Polygon');

test('can parse 3DM WKB Simple Polygon with SRID', function () {
    $polygonWKB = '0103000060E61000000100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepointM(8.12345, 50.12345, 10), st_makepointM(9.12345, 51.12345,20), st_makepointM(7.12345, 48.12345,30), st_makepointM(8.12345, 50.12345,10)])), 4326)

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($polygon)->geometryHasSrid(4326);
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
})->group('WKB Polygon');

test('can parse 4D WKB Simple Polygon', function () {
    $polygonWKB = '01030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345,20,22), st_makepoint(7.12345, 48.12345,30,32), st_makepoint(8.12345, 50.12345,10,12)]))

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_4D);
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
})->group('WKB Polygon');

test('can parse 4D WKB Simple Polygon with SRID', function () {
    $polygonWKB = '01030000E0E61000000100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640CAC342AD697E1C407958A835CD0F48400000000000003E400000000000004040E561A1D6343F20407958A835CD0F494000000000000024400000000000002840'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345,20,22), st_makepoint(7.12345, 48.12345,30,32), st_makepoint(8.12345, 50.12345,10,12)])), 4326)

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($polygon)->geometryHasSrid(4326);
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
})->group('WKB Polygon');
