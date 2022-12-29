<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKTParser::class);
});

test('can parse 2D WKT Polygon with multi hole', function () {
    $polygonWKT = 'POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634),(8.393554 50.322669,8.367462 50.229637,8.491058 50.341078,8.393554 50.322669))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
})->group('WKT Polygon');

test('can parse 2D WKT Polygon with multi hole with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634),(8.393554 50.322669,8.367462 50.229637,8.491058 50.341078,8.393554 50.322669))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($polygon)->geometryHasSrid(4326);
    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
})->group('WKT Polygon');

test('can parse 3DZ WKT Polygon with multi hole', function () {
    $polygonWKT = 'POLYGON Z ((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getZ())->toBe(10.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getZ())->toBe(10.0);
})->group('WKT Polygon');

test('can parse 3DZ WKT Polygon with multi hole with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON Z ((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($polygon)->geometryHasSrid(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getZ())->toBe(10.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getZ())->toBe(10.0);
})->group('WKT Polygon');

test('can parse 3DM WKT Polygon with multi hole', function () {
    $polygonWKT = 'POLYGON M ((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getM())->toBe(10.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKT Polygon');

test('can parse 3DM WKT Polygon with multi hole with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON M ((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($polygon)->geometryHasSrid(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getM())->toBe(10.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getM())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKT Polygon');

test('can parse 4D WKT Polygon with multi hole', function () {
    $polygonWKT = 'POLYGON ZM ((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12),(8.27133 50.16634 10 12,8.198547 50.035091 10 12,8.267211 50.050966 10 12,8.27133 50.16634 10 12),(8.393554 50.322669 10 12,8.367462 50.229637 10 12,8.491058 50.341078 10 12,8.393554 50.322669 10 12))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getM())->toBe(12.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKT Polygon');

test('can parse 4D WKT Polygon with multi hole with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON ZM ((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12),(8.27133 50.16634 10 12,8.198547 50.035091 10 12,8.267211 50.050966 10 12,8.27133 50.16634 10 12),(8.393554 50.322669 10 12,8.367462 50.229637 10 12,8.491058 50.341078 10 12,8.393554 50.322669 10 12))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($polygon)->geometryHasSrid(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getX())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getY())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getX())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getY())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getX())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getY())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getM())->toBe(12.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getX())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getY())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getX())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getY())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getM())->toBe(12.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getX())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getY())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getZ())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKT Polygon');
