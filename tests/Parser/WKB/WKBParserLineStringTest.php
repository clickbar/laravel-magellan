<?php

use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;

beforeEach(function () {
    $this->parser = new WKBParser(new GeometryFactory());
});


test('can parse 2D WKB LineString', function () {
    $lineStringWKB = '010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940'; // st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345))

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
})->group('WKB LineString');

test('can parse 2D WKB LineString With SRID', function () {
    $lineStringWKB = '0102000020E610000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940'; // st_setsrid(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), 4326)

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);
    expect($lineString->getSrid())->toBe(4326);
})->group('WKB LineString');

test('can parse 3DZ WKB LineString', function () {
    $lineStringWKB = '010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440'; // st_makeline(st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20))

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
})->group('WKB LineString');

test('can parse 3DZ WKB LineString with SRID', function () {
    $lineStringWKB = '01020000A0E610000002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440'; // st_makeline(st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20))

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);
    expect($lineString->getSrid())->toBe(4326);
})->group('WKB LineString');

test('can parse 3DM WKB LineString', function () {
    $lineStringWKB = '010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440'; // st_makeline(st_makepointM(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20))

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getDimension())->toBe(Dimension::DIMENSION_3DM);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getM())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getM())->toBe(20.0);
})->group('WKB LineString');

test('can parse 3DM WKB LineString with SRID', function () {
    $lineStringWKB = '0102000060E610000002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440'; // st_setsrid(st_makeline(st_makepointM(8.12345, 50.12345, 10), st_makepointm(9.12345, 51.12345, 20)), 4326)

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getM())->toBe(10.0);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getM())->toBe(20.0);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);
    expect($lineString->getSrid())->toBe(4326);
})->group('WKB LineString');

test('can parse 4D WKB LineString', function () {
    $lineStringWKB = '01020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640'; // st_makeline(st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22))

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getDimension())->toBe(Dimension::DIMENSION_4D);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[0]->getM())->toBe(12.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
    expect($lineString->getPoints()[1]->getM())->toBe(22.0);
})->group('WKB LineString');

test('can parse 4D WKB LineString with SRID', function () {
    $lineStringWKB = '01020000E0E610000002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000034400000000000003640'; // st_setsrid(st_makeline(st_makepoint(8.12345, 50.12345, 10, 12), st_makepoint(9.12345, 51.12345, 20, 22)), 4326)

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getDimension())->toBe(Dimension::DIMENSION_4D);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[0]->getM())->toBe(12.0);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
    expect($lineString->getPoints()[1]->getM())->toBe(22.0);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);
    expect($lineString->getSrid())->toBe(4326);
})->group('WKB LineString');
