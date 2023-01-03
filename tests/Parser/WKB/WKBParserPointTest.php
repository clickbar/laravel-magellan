<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKBParser::class);
});

test('can parse 2D WKB Point', function () {
    $pointWKB = '0101000000E561A1D6343F20407958A835CD0F4940'; // st_makepoint(8.12345, 50.12345)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
})->group('WKB Point');

test('can parse 2D EMPTY WKB Point', function () {
    $pointWKB = '0101000000000000000000F87F000000000000F87F';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBe(null);
    expect($point->getM())->toBe(null);
    expect($point)->geometryHasSrid(null);
})->group('WKB Point');

test('can parse 2D WKB Point with SRID', function () {
    $pointWKB = '0101000020E6100000E561A1D6343F20407958A835CD0F4940'; // st_setsrid(st_makepoint(8.12345, 50.12345), 4326)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point)->geometryHasSrid(4326);
})->group('WKB Point');

test('can parse 2D EMPTY WKB Point with SRID', function () {
    $pointWKB = '0101000020e6100000000000000000f87f000000000000f87f';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBe(null);
    expect($point->getM())->toBe(null);
    expect($point)->geometryHasSrid(4326);
})->group('WKB Point');

test('can parse 3DZ WKB Point', function () {
    $pointWKB = '0101000080E561A1D6343F20407958A835CD0F49400000000000002440'; // st_makepoint(8.12345, 50.12345, 10)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
})->group('WKB Point');

test('can parse 3DZ EMPTY WKB Point', function () {
    $pointWKB = '0101000080000000000000F87F000000000000F87F000000000000F87F';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBeNan();
    expect($point->getM())->toBe(null);
    expect($point)->geometryHasSrid(null);
})->group('WKB Point');

test('can parse 3DZ WKB Point with SRID', function () {
    $pointWKB = '01010000A0E6100000E561A1D6343F20407958A835CD0F49400000000000002440'; // st_setsrid(st_makepoint(8.12345, 50.12345, 10), 4326)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
    expect($point)->geometryHasSrid(4326);
})->group('WKB Point');

test('can parse 3DZ EMPTY WKB Point with SRID', function () {
    $pointWKB = '01010000A0E6100000000000000000F87F000000000000F87F000000000000F87F';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBeNan();
    expect($point->getM())->toBe(null);
    expect($point)->geometryHasSrid(4326);
})->group('WKB Point');

test('can parse 3DM WKB Point', function () {
    $pointWKB = '0101000040E561A1D6343F20407958A835CD0F49400000000000002440'; // ST_MakePointM(8.12345, 50.12345,10)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getM())->toBe(10.0);
})->group('WKB Point');

test('can parse 3DM EMPTY WKB Point', function () {
    $pointWKB = '0101000040000000000000F87F000000000000F87F000000000000F87F';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBe(null);
    expect($point->getM())->toBeNan();
    expect($point)->geometryHasSrid(null);
})->group('WKB Point');

test('can parse 3DM WKB Point with SRID', function () {
    $pointWKB = '0101000060E6100000E561A1D6343F20407958A835CD0F49400000000000002440'; // st_setsrid(ST_MakePointM(8.12345, 50.12345,10), 4326)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point)->geometryHasSrid(4326);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getM())->toBe(10.0);
})->group('WKB Point');

test('can parse 3DM EMPTY WKB Point with SRID', function () {
    $pointWKB = '0101000060E6100000000000000000F87F000000000000F87F000000000000F87F';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBe(null);
    expect($point->getM())->toBeNan();
    expect($point)->geometryHasSrid(4326);
})->group('WKB Point');

test('can parse 4D WKB Point', function () {
    $pointWKB = '01010000C0E561A1D6343F20407958A835CD0F494000000000000024400000000000003440'; // st_makepoint(8.12345, 50.12345, 10, 20)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
    expect($point->getM())->toBe(20.0);
})->group('WKB Point');

test('can parse 4D EMPTY WKB Point', function () {
    $pointWKB = '01010000C0000000000000F87F000000000000F87F000000000000F87F000000000000F87F';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBeNan();
    expect($point->getM())->toBeNan();
    expect($point)->geometryHasSrid(null);
})->group('WKB Point');

test('can parse 4D WKB Point with SRID', function () {
    $pointWKB = '01010000E0E6100000E561A1D6343F20407958A835CD0F494000000000000024400000000000003440'; // st_setsrid(st_makepoint(8.12345, 50.12345, 10, 20), 4326)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
    expect($point->getM())->toBe(20.0);
    expect($point)->geometryHasSrid(4326);
})->group('WKB Point');

test('can parse 4D EMPTY WKB Point with SRID', function () {
    $pointWKB = '01010000E0E6100000000000000000F87F000000000000F87F000000000000F87F000000000000F87F';

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point->isEmpty())->toBeTrue();
    expect($point->getZ())->toBeNan();
    expect($point->getM())->toBeNan();
    expect($point)->geometryHasSrid(4326);
})->group('WKB Point');
