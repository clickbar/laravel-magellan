<?php

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\GeometryFactory;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Parser\WKT\WKTParser;

beforeEach(function () {
    $this->parser = new WKTParser(new GeometryFactory());
});

test('can parse 2D WKT Point', function () {
    $pointWKT = 'POINT(8.12345 50.12345)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
})->group('WKT Point');

test('can parse 2D WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT(8.12345 50.12345)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
    expect($point->getSrid())->toBe(4326);
})->group('WKT Point');

test('can parse 3D WKT Point', function () {
    $pointWKT = 'POINT Z (8.12345 50.12345 10)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
    expect($point->getAltitude())->toBe(10.0);
})->group('WKT Point');

test('can parse 3D WKT Point with SRID', function () {
    $pointWKT = 'SRID=4326;POINT Z (8.12345 50.12345 10)';

    $point = $this->parser->parse($pointWKT);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
    expect($point->getAltitude())->toBe(10.0);
    expect($point->getSrid())->toBe(4326);
})->group('WKT Point');

test('can parse 2D WKT LineString', function () {
    $lineStringWKT = 'LINESTRING(8.12345 50.12345,9.12345 51.12345)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
})->group('WKT LineString');

test('can parse 2D WKT LineString with SRID', function () {
    $lineStringWKT = 'SRID=4326;LINESTRING(8.12345 50.12345,9.12345 51.12345)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);
})->group('WKT LineString');

test('can parse 3D WKT LineString', function () {
    $lineStringWKT = 'LINESTRING Z (8.12345 50.12345 10,9.12345 51.12345 20)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getAltitude())->toBe(20.0);
})->group('WKT LineString');

test('can parse 3D WKT LineString with SRID', function () {
    $lineStringWKT = 'SRID=4326;LINESTRING Z (8.12345 50.12345 10,9.12345 51.12345 20)';

    $lineString = $this->parser->parse($lineStringWKT);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getSrid())->toBe(4326);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);
})->group('WKT LineString');

test('can parse 2D WKT MultiLineString', function () {
    $multiLineStringWKT = 'MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
})->group('WKT MultiLineString');

test('can parse 2D WKT MultiLineString with SRID', function () {
    $multiLineStringWKT = 'SRID=4326;MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getSrid())->toBe(4326);
})->group('WKT MultiLineString');

test('can parse 3D WKT MultiLineString', function () {
    $multiLineStringWKT = 'MULTILINESTRING Z ((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getAltitude())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getAltitude())->toBe(40.0);
})->group('WKT MultiLineString');

test('can parse 3D WKT MultiLineString with SRID', function () {
    $multiLineStringWKT = 'SRID=4326;MULTILINESTRING Z ((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))';

    $multiLineString = $this->parser->parse($multiLineStringWKT);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[1]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getAltitude())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getAltitude())->toBe(40.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getSrid())->toBe(4326);
})->group('WKT MultiLineString');

test('can parse 2D WKT Simple Polygon', function () {
    $polygonWKT = 'POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
})->group('WKT Polygon');

test('can parse 2D WKT Simple Polygon with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT Polygon');

test('can parse 3D WKT Simple Polygon', function () {
    $polygonWKT = 'POLYGON Z ((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
})->group('WKT Polygon');

test('can parse 3D WKT Simple Polygon with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON Z ((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT Polygon');

test('can parse 2D WKT Polygon with single hole', function () {
    $polygonWKT = 'POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);
})->group('WKT Polygon');

test('can parse 2D WKT Polygon with single hole with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);

    expect($polygon->getLineStrings()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT Polygon');

test('can parse 2D WKT Polygon with multi hole', function () {
    $polygonWKT = 'POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634),(8.393554 50.322669,8.367462 50.229637,8.491058 50.341078,8.393554 50.322669))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLongitude())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLatitude())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLongitude())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLatitude())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLatitude())->toBe(50.322669);
})->group('WKT Polygon');

test('can parse 2D WKT Polygon with multi hole with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634),(8.393554 50.322669,8.367462 50.229637,8.491058 50.341078,8.393554 50.322669))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getSrid())->toBe(4326);


    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);

    expect($polygon->getLineStrings()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getSrid())->toBe(4326);

    expect($polygon->getLineStrings()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLongitude())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLatitude())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLongitude())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLatitude())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT Polygon');

test('can parse 3D WKT Polygon with multi hole', function () {
    $polygonWKT = 'POLYGON Z ((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getAltitude())->toBe(10.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLongitude())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLatitude())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLongitude())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLatitude())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getAltitude())->toBe(10.0);
})->group('WKT Polygon');

test('can parse 3D WKT Polygon with multi hole with SRID', function () {
    $polygonWKT = 'SRID=4326;POLYGON Z ((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))';

    $polygon = $this->parser->parse($polygonWKT);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getSrid())->toBe(4326);


    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);

    expect($polygon->getLineStrings()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getSrid())->toBe(4326);

    expect($polygon->getLineStrings()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLongitude())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLatitude())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLongitude())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLatitude())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT Polygon');

test('can parse 2D WKT MultiPoint', function () {
    $multiPointWKT = 'MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);
})->group('WKT MultiPoint');

test('can parse 2D WKT MultiPoint with SRID', function () {
    $multiPointWKT = 'SRID=4326;MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getSrid())->toBe(4326);

    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT MultiPoint');

test('can parse 3D WKT MultiPoint', function () {
    $multiPointWKT = 'MULTIPOINT Z (8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getAltitude())->toBe(30.0);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getAltitude())->toBe(40.0);
})->group('WKT MultiPoint');

test('can parse 3D WKT MultiPoint with SRID', function () {
    $multiPointWKT = 'SRID=4326;MULTIPOINT Z (8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)';

    $multiPoint = $this->parser->parse($multiPointWKT);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getSrid())->toBe(4326);

    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPoint->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($multiPoint->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getAltitude())->toBe(30.0);
    expect($multiPoint->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getAltitude())->toBe(40.0);
    expect($multiPoint->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT MultiPoint');

test('can parse 2D WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
})->group('WKT MultiPolygon');

test('can parse 2D WKT Simple MultiPolygon with SRID', function () {
    $multiPolygonWKT = 'SRID=4326;MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT MultiPolygon');

test('can parse 3D WKT Simple MultiPolygon', function () {
    $multiPolygonWKT = 'MULTIPOLYGON Z (((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
})->group('WKT MultiPolygon');


test('can parse 3D WKT Simple MultiPolygon with SRID', function () {
    $multiPolygonWKT = 'SRID=4326;MULTIPOLYGON Z (((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))';

    $multiPolygon = $this->parser->parse($multiPolygonWKT);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);

    expect($multiPolygon->getPolygons()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT MultiPolygon');

test('can parse 2D WKT GeometryCollection', function () {
    $geometryCollectionWKT = 'GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
})->group('WKT GeometryCollection');

test('can parse 2D WKT GeometryCollection with SRID', function () {
    $geometryCollectionWKT = 'SRID=4326;GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))';

    $geometryCollection = $this->parser->parse($geometryCollectionWKT);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getSrid())->toBe(4326);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
    expect($point->getSrid())->toBe(4326);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getSrid())->toBe(4326);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getSrid())->toBe(4326);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getSrid())->toBe(4326);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getSrid())->toBe(4326);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getSrid())->toBe(4326);
})->group('WKT GeometryCollection');
