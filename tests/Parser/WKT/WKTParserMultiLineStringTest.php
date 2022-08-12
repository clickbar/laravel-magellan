<?php


use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\Geometries\MultiLineString;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;

beforeEach(function () {
    $this->parser = new WKTParser(new GeometryFactory());
});

test('can parse 2D WKT MultiLineString', function () {
    $multiLineStringWKT = 'MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
})->group('WKT MultiLineString');

test('can parse 2D WKT MultiLineString with SRID', function () {
    $multiLineStringWKT = 'SRID=4326;MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiLineString)->geometryHasSrid(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
})->group('WKT MultiLineString');

test('can parse 3DZ WKT MultiLineString', function () {
    $multiLineStringWKT = 'MULTILINESTRING Z ((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
})->group('WKT MultiLineString');

test('can parse 3DZ WKT MultiLineString with SRID', function () {
    $multiLineStringWKT = 'SRID=4326;MULTILINESTRING Z ((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($multiLineString)->geometryHasSrid(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
})->group('WKT MultiLineString');


test('can parse 3DM WKT MultiLineString', function () {
    $multiLineStringWKT = 'MULTILINESTRING M ((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(40.0);
})->group('WKT MultiLineString');

test('can parse 3DM WKT MultiLineString with SRID', function () {
    $multiLineStringWKT = 'SRID=4326;MULTILINESTRING M ((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiLineString)->geometryHasSrid(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(20.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(40.0);
})->group('WKT MultiLineString');

test('can parse 4D WKT MultiLineString', function () {
    $multiLineStringWKT = 'MULTILINESTRING ZM ((8.12345 50.12345 10 12,9.12345 51.12345 20 22),(7.12345 49.12345 30 32,6.12345 48.12345 40 42))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(22.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(32.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(42.0);
})->group('WKT MultiLineString');

test('can parse 4D WKT MultiLineString with SRID', function () {
    $multiLineStringWKT = 'SRID=4326;MULTILINESTRING ZM ((8.12345 50.12345 10 12,9.12345 51.12345 20 22),(7.12345 49.12345 30 32,6.12345 48.12345 40 42))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiLineString)->geometryHasSrid(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(22.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(32.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(42.0);
})->group('WKT MultiLineString');
