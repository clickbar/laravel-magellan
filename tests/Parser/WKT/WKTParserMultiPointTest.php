<?php

use Clickbar\Magellan\Geometries\MultiPoint;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKTParser::class);
});

test('can parse 2D WKT MultiPoint', function () {
    $multiPointWKT = 'MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
})->group('WKT MultiPoint');

test('can parse 2D WKT MultiPoint with SRID', function () {
    $multiPointWKT = 'SRID=4326;MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiPoint)->geometryHasSrid(4326);
    expect($multiPoint->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getY())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getX())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getY())->toBe(48.12345);
})->group('WKT MultiPoint');

test('can parse 3DZ WKT MultiPoint', function () {
    $multiPointWKT = 'MULTIPOINT Z (8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_3DZ);
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
})->group('WKT MultiPoint');

test('can parse 3DZ WKT MultiPoint with SRID', function () {
    $multiPointWKT = 'SRID=4326;MULTIPOINT Z (8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint)->geometryHasDimension(Dimension::DIMENSION_3DZ);
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
})->group('WKT MultiPoint');

test('can parse 3DM WKT MultiPoint', function () {
    $multiPointWKT = 'MULTIPOINT M (8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)';

    $multiPoint = $this->parser->parse($multiPointWKT);

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
})->group('WKT MultiPoint');

test('can parse 3DM WKT MultiPoint with SRID', function () {
    $multiPointWKT = 'SRID=4326;MULTIPOINT M (8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)';

    $multiPoint = $this->parser->parse($multiPointWKT);

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
})->group('WKT MultiPoint');

test('can parse 4D WKT MultiPoint', function () {
    $multiPointWKT = 'MULTIPOINT ZM (8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 49.12345 30 32,6.12345 48.12345 40 42)';

    $multiPoint = $this->parser->parse($multiPointWKT);

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
})->group('WKT MultiPoint');

test('can parse 4D WKT MultiPoint with SRID', function () {
    $multiPointWKT = 'SRID=4326;MULTIPOINT ZM (8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 49.12345 30 32,6.12345 48.12345 40 42)';

    $multiPoint = $this->parser->parse($multiPointWKT);

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
})->group('WKT MultiPoint');
