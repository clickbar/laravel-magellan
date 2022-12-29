<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKTParser::class);
});

test('can parse 2D WKT LineString', function () {
    $lineStringWKT = 'LINESTRING(8.12345 50.12345,9.12345 51.12345)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
})->group('WKT LineString');

test('can parse 2D WKT LineString with SRID', function () {
    $lineStringWKT = 'SRID=4326;LINESTRING(8.12345 50.12345,9.12345 51.12345)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($lineString)->geometryHasSrid(4326);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
})->group('WKT LineString');

test('can parse 3DZ WKT LineString', function () {
    $lineStringWKT = 'LINESTRING Z (8.12345 50.12345 10,9.12345 51.12345 20)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
})->group('WKT LineString');

test('can parse 3DZ WKT LineString with SRID', function () {
    $lineStringWKT = 'SRID=4326;LINESTRING Z (8.12345 50.12345 10,9.12345 51.12345 20)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($lineString)->geometryHasSrid(4326);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
})->group('WKT LineString');

test('can parse 3DM WKT LineString', function () {
    $lineStringWKT = 'LINESTRING M (8.12345 50.12345 10,9.12345 51.12345 20)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getM())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getM())->toBe(20.0);
})->group('WKT LineString');

test('can parse 3DM WKT LineString with SRID', function () {
    $lineStringWKT = 'SRID=4326;LINESTRING M (8.12345 50.12345 10,9.12345 51.12345 20)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($lineString)->geometryHasSrid(4326);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getM())->toBe(10.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getM())->toBe(20.0);
})->group('WKT LineString');

test('can parse 4D WKT LineString', function () {
    $lineStringWKT = 'LINESTRING ZM (8.12345 50.12345 10 12,9.12345 51.12345 20 22)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[0]->getM())->toBe(12.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
    expect($lineString->getPoints()[1]->getM())->toBe(22.0);
})->group('WKT LineString');

test('can parse 4D WKT LineString with SRID', function () {
    $lineStringWKT = 'SRID=4326;LINESTRING ZM (8.12345 50.12345 10 12,9.12345 51.12345 20 22)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($lineString)->geometryHasSrid(4326);
    expect($lineString->getPoints()[0]->getX())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getY())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getZ())->toBe(10.0);
    expect($lineString->getPoints()[0]->getM())->toBe(12.0);
    expect($lineString->getPoints()[1]->getX())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getY())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getZ())->toBe(20.0);
    expect($lineString->getPoints()[1]->getM())->toBe(22.0);
})->group('WKT LineString');
