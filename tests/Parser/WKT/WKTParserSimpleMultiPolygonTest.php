<?php

use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\Geometries\MultiPolygon;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;

beforeEach(function () {
    $this->parser = new WKTParser(new GeometryFactory());
});

test('can parse 2D WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_2D);

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
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiPolygon)->geometryHasSrid(4326);

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

test('can parse 3DZ WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON Z (((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DZ);

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
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($multiPolygon)->geometryHasSrid(4326);

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

test('can parse 3DM WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON M (((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DM);

    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKT MultiPolygon');

test('can parse 3DM WKT Simple MultiPolygon with SRID', function () {
    $multiPolygonWKT = 'SRID=4326;MULTIPOLYGON M (((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiPolygon)->geometryHasSrid(4326);

    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKT MultiPolygon');

test('can parse 4D WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON ZM (((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12)),((10.12345 50.12345 10 12,11.12345 51.12345 10 12,9.12345 48.12345 10 12,10.12345 50.12345 10 12)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_4D);

    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKT MultiPolygon');

test('can parse 4D WKT Simple MultiPolygon with SRID', function () {
    $multiPolygonWKT = 'SRID=4326;MULTIPOLYGON ZM (((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12)),((10.12345 50.12345 10 12,11.12345 51.12345 10 12,9.12345 48.12345 10 12,10.12345 50.12345 10 12)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiPolygon)->geometryHasSrid(4326);

    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKT MultiPolygon');
