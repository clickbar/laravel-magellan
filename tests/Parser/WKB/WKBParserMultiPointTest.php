<?php

use Clickbar\Magellan\Geometries\Dimension;
use Clickbar\Magellan\Geometries\MultiPoint;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKBParser::class);
});

test('can parse 2D WKB MultiPoint', function () {
    $multiPointWKB = '0104000000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)])

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
})->group('WKB MultiPoint');

test('can parse 2D WKB MultiPoint wit SRID', function () {
    $multiPointWKB = '0104000020E6100000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)]), 4326)

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);

    expect($multiPoint->getSRID())->toBe(4326);
})->group('WKB MultiPoint');

test('can parse 3DZ WKB MultiPoint', function () {
    $multiPointWKB = '0104000080040000000101000080E561A1D6343F20407958A835CD0F494000000000000024400101000080E561A1D6343F22407958A835CD8F494000000000000034400101000080CAC342AD697E1C407958A835CD8F48400000000000003E400101000080CAC342AD697E18407958A835CD0F48400000000000004440'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20), st_makepoint(7.12345, 49.12345, 30), st_makepoint(6.12345, 48.12345, 40)])

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getZ())->toBe(30.0);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getZ())->toBe(40.0);
})->group('WKB MultiPoint');

test('can parse 3DZ WKB MultiPoint with SRID', function () {
    $multiPointWKB = '01040000A0E6100000040000000101000080E561A1D6343F20407958A835CD0F494000000000000024400101000080E561A1D6343F22407958A835CD8F494000000000000034400101000080CAC342AD697E1C407958A835CD8F48400000000000003E400101000080CAC342AD697E18407958A835CD0F48400000000000004440'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20), st_makepoint(7.12345, 49.12345, 30), st_makepoint(6.12345, 48.12345, 40)]), 4326)

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasSrid(4326);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getZ())->toBe(30.0);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getZ())->toBe(40.0);
})->group('WKB MultiPoint');

test('can parse 3DM WKB MultiPoint', function () {
    $multiPointWKB = '0104000040040000000101000040E561A1D6343F20407958A835CD0F494000000000000024400101000040E561A1D6343F22407958A835CD8F494000000000000034400101000040CAC342AD697E1C407958A835CD8F48400000000000003E400101000040CAC342AD697E18407958A835CD0F48400000000000004440'; // st_collect(ARRAY[st_makepointm(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20), st_makepointm(7.12345, 49.12345, 30), st_makepointm(6.12345, 48.12345, 40)])

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getM())->toBe(20.0);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getM())->toBe(30.0);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getM())->toBe(40.0);
})->group('WKB MultiPoint');

test('can parse 3DM WKB MultiPoint with SRID', function () {
    $multiPointWKB = '0104000060E6100000040000000101000040E561A1D6343F20407958A835CD0F494000000000000024400101000040E561A1D6343F22407958A835CD8F494000000000000034400101000040CAC342AD697E1C407958A835CD8F48400000000000003E400101000040CAC342AD697E18407958A835CD0F48400000000000004440'; // st_setsrid(st_collect(ARRAY[st_makepointm(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20), st_makepointm(7.12345, 49.12345, 30), st_makepointm(6.12345, 48.12345, 40)]), 4326)

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiPoint)->geometryHasSrid(4326);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getM())->toBe(20.0);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getM())->toBe(30.0);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getM())->toBe(40.0);
})->group('WKB MultiPoint');

test('can parse 4D WKB MultiPoint', function () {
    $multiPointWKB = '01040000C00400000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001010000C0E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001010000C0CAC342AD697E1C407958A835CD8F48400000000000003E40000000000000404001010000C0CAC342AD697E18407958A835CD0F484000000000000044400000000000004540'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22), st_makepoint(7.12345, 49.12345, 30,32), st_makepoint(6.12345, 48.12345, 40,42)])

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPoint->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiPoint->getPoints()[1]->getM())->toBe(22.0);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getZ())->toBe(30.0);
    expect($multiPoint->getPoints()[2]->getM())->toBe(32.0);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getZ())->toBe(40.0);
    expect($multiPoint->getPoints()[3]->getM())->toBe(42.0);
})->group('WKB MultiPoint');

test('can parse 4D WKB MultiPoint with SRID', function () {
    $multiPointWKB = '01040000E0E61000000400000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001010000C0E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001010000C0CAC342AD697E1C407958A835CD8F48400000000000003E40000000000000404001010000C0CAC342AD697E18407958A835CD0F484000000000000044400000000000004540'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22), st_makepoint(7.12345, 49.12345, 30,32), st_makepoint(6.12345, 48.12345, 40,42)]), 4326)

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiPoint)->geometryHasSrid(4326);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPoint->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiPoint->getPoints()[1]->getM())->toBe(22.0);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getZ())->toBe(30.0);
    expect($multiPoint->getPoints()[2]->getM())->toBe(32.0);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getZ())->toBe(40.0);
    expect($multiPoint->getPoints()[3]->getM())->toBe(42.0);
})->group('WKB MultiPoint');
