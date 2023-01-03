<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKTParser::class);
});

test('can parse empty 2D WKT Point', function () {
    $pointWKT = 'POINT EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($point)->geometryHasSrid(null);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse empty 3DZ WKT Point', function () {
    $pointWKT = 'POINT Z EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($point)->geometryHasSrid(null);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse empty 3DM WKT Point', function () {
    $pointWKT = 'POINT M EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point)->geometryHasSrid(null);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse empty 4D WKT Point', function () {
    $pointWKT = 'POINT ZM EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point)->geometryHasSrid(null);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse empty 2D WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($point)->geometryHasSrid(4326);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse empty 3DZ WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT Z EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($point)->geometryHasSrid(4326);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse empty 3DM WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT M EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point)->geometryHasSrid(4326);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse empty 4D WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT ZM EMPTY';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point)->geometryHasSrid(4326);
    expect($point->isEmpty())->toBeTrue();
})->group('WKT Point');

test('can parse 2D WKT Point', function () {
    $pointWKT = 'POINT(8.12345 50.12345)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
})->group('WKT Point');

test('can parse 2D WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT(8.12345 50.12345)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($point)->geometryHasSrid(4326);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
})->group('WKT Point');

test('can parse 3DZ WKT Point', function () {
    $pointWKT = 'POINT Z (8.12345 50.12345 10)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
})->group('WKT Point');

test('can parse 3DZ WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT Z (8.12345 50.12345 10)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($point)->geometryHasSrid(4326);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
})->group('WKT Point');

test('can parse 3DM WKT Point', function () {
    $pointWKT = 'POINT M (8.12345 50.12345 10)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getM())->toBe(10.0);
})->group('WKT Point');

test('can parse 3DM WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT M (8.12345 50.12345 10)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($point)->geometryHasSrid(4326);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getM())->toBe(10.0);
})->group('WKT Point');

test('can parse 4D WKT Point', function () {
    $pointWKT = 'POINT ZM (8.12345 50.12345 10 20)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
    expect($point->getM())->toBe(20.0);
})->group('WKT Point');

test('can parse 4D WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT ZM (8.12345 50.12345 10 20)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($point)->geometryHasSrid(4326);
    expect($point->getX())->toBe(8.12345);
    expect($point->getY())->toBe(50.12345);
    expect($point->getZ())->toBe(10.0);
    expect($point->getM())->toBe(20.0);
})->group('WKT Point');
