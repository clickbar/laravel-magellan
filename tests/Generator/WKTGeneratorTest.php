<?php

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Dimension;
use Clickbar\Postgis\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator();
});

test('can generate 2D WKT Point', function () {
    $point = Point::make(8.12345, 50.12345);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT(8.12345 50.12345)');
})->group('WKT Point');


test('can generate 2D WKT Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT(8.12345 50.12345)');
})->group('WKT Point');

test('can generate 3D WKT Point', function () {
    $point = Point::make(8.12345, 50.12345, 10);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT Z(8.12345 50.12345 10)');
})->group('WKT Point');

test('can generate 3D WKT Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT Z(8.12345 50.12345 10)');
})->group('WKT Point');


test('can generate 2D WKT LineString', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING(8.12345 50.12345,9.12345 51.12345)');
})->group('WKT LineString');

test('can generate 2D WKT LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2], 4326);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING(8.12345 50.12345,9.12345 51.12345)');
})->group('WKT LineString');

test('can generate 3D WKT LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2]);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('LINESTRING Z(8.12345 50.12345 10,9.12345 51.12345 20)');
})->group('WKT LineString');

test('can generate 3D WKT LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2], 4326);

    $lineStringWKT = $this->generator->generate($lineString);

    expect($lineStringWKT)->toBe('SRID=4326;LINESTRING Z(8.12345 50.12345 10,9.12345 51.12345 20)');
})->group('WKT LineString');

test('can generate 2D WKT MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 49.12345);
    $point4 = Point::make(6.12345, 48.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2]);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point3, $point4]);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_2D, [$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))');
})->group('WKT MultiLineString');


test('can generate 2D WKT MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point3, $point4], 4326);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_2D, [$lineString1, $lineString2], 4326);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING((8.12345 50.12345,9.12345 51.12345),(7.12345 49.12345,6.12345 48.12345))');
})->group('WKT MultiLineString');


test('can generate 3D WKT MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 49.12345, 30);
    $point4 = Point::make(6.12345, 48.12345, 40);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2]);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point3, $point4]);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_3DZ, [$lineString1, $lineString2]);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('MULTILINESTRING Z((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))');
})->group('WKT MultiLineString');

test('can generate 3D WKT MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point3, $point4], 4326);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_3DZ, [$lineString1, $lineString2], 4326);

    $multiLineStringWKT = $this->generator->generate($multiLineString);

    expect($multiLineStringWKT)->toBe('SRID=4326;MULTILINESTRING Z((8.12345 50.12345 10,9.12345 51.12345 20),(7.12345 49.12345 30,6.12345 48.12345 40))');
})->group('WKT MultiLineString');

test('can generate 2D WKT Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345))');
})->group('WKT Polygon');

test('can generate 2D WKT Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString], 4326);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345))');
})->group('WKT Polygon');

test('can generate 3D WKT Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 48.12345, 30);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))');
})->group('WKT Polygon');

test('can generate 3D WKT Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString], 4326);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10))');
})->group('WKT Polygon');

test('can generate 2D WKT Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);
    $holePoint1 = Point::make(8.27133, 50.16634);
    $holePoint2 = Point::make(8.198547, 50.035091);
    $holePoint3 = Point::make(8.267211, 50.050966);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634))');
})->group('WKT Polygon');

test('can generate 2D WKT Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString], 4326);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634))');
})->group('WKT Polygon');

test('can generate 2D WKT Polygon with multi hole', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);
    $holePoint1 = Point::make(8.27133, 50.16634);
    $holePoint2 = Point::make(8.198547, 50.035091);
    $holePoint3 = Point::make(8.267211, 50.050966);
    $hole2Point1 = Point::make(8.393554, 50.322669);
    $hole2Point2 = Point::make(8.367462, 50.229637);
    $hole2Point3 = Point::make(8.491058, 50.341078);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = new LineString(Dimension::DIMENSION_2D, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634),(8.393554 50.322669,8.367462 50.229637,8.491058 50.341078,8.393554 50.322669))');
})->group('WKT Polygon');

test('can generate 2D WKT Polygon with multi hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211);
    $hole2Point1 = Point::makeGeodetic(50.322669, 8.393554);
    $hole2Point2 = Point::makeGeodetic(50.229637, 8.367462);
    $hole2Point3 = Point::makeGeodetic(50.341078, 8.491058);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1], 4326);
    $hole2LineString = new LineString(Dimension::DIMENSION_2D, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString, $hole2LineString], 4326);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634),(8.393554 50.322669,8.367462 50.229637,8.491058 50.341078,8.393554 50.322669))');
})->group('WKT Polygon');

test('can generate 3D WKT Polygon with multi hole', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 10);
    $point3 = Point::make(7.12345, 48.12345, 10);
    $holePoint1 = Point::make(8.27133, 50.16634, 10);
    $holePoint2 = Point::make(8.198547, 50.035091, 10);
    $holePoint3 = Point::make(8.267211, 50.050966, 10);
    $hole2Point1 = Point::make(8.393554, 50.322669, 10);
    $hole2Point2 = Point::make(8.367462, 50.229637, 10);
    $hole2Point3 = Point::make(8.491058, 50.341078, 10);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_3DZ, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = new LineString(Dimension::DIMENSION_3DZ, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))');
})->group('WKT Polygon');

test('can generate 3D WKT Polygon with multi hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 10);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 10);
    $hole2Point1 = Point::makeGeodetic(50.322669, 8.393554, 10);
    $hole2Point2 = Point::makeGeodetic(50.229637, 8.367462, 10);
    $hole2Point3 = Point::makeGeodetic(50.341078, 8.491058, 10);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1], 4326);
    $holeLineString = new LineString(Dimension::DIMENSION_3DZ, [$holePoint1, $holePoint2, $holePoint3, $holePoint1], 4326);
    $hole2LineString = new LineString(Dimension::DIMENSION_3DZ, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString, $holeLineString, $hole2LineString], 4326);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))');
})->group('WKT Polygon');

test('can generate 2D WKT MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 49.12345);
    $point4 = Point::make(6.12345, 48.12345);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)');
})->group('WKT MultiPoint');

test('can generate 2D WKT MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point4], 4326);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('SRID=4326;MULTIPOINT(8.12345 50.12345,9.12345 51.12345,7.12345 49.12345,6.12345 48.12345)');
})->group('WKT MultiPoint');

test('can generate 3D WKT MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 49.12345, 30);
    $point4 = Point::make(6.12345, 48.12345, 40);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point4]);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('MULTIPOINT Z(8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)');
})->group('WKT MultiPoint');

test('can generate 3D WKT MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point4], 4326);

    $multiPointWKT = $this->generator->generate($multiPoint);

    expect($multiPointWKT)->toBe('SRID=4326;MULTIPOINT Z(8.12345 50.12345 10,9.12345 51.12345 20,7.12345 49.12345 30,6.12345 48.12345 40)');
})->group('WKT MultiPoint');

test('can generate 2D WKT Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);
    $point4 = Point::make(10.12345, 50.12345);
    $point5 = Point::make(11.12345, 51.12345);
    $point6 = Point::make(9.12345, 48.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point4, $point5, $point6, $point4]);

    $polygon1 = new Polygon(Dimension::DIMENSION_2D, [$lineString1]);
    $polygon2 = new Polygon(Dimension::DIMENSION_2D, [$lineString2]);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_2D, [$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))');
})->group('WKT MultiPolygon');

test('can generate 2D WKT Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $point4 = Point::makeGeodetic(50.12345, 10.12345);
    $point5 = Point::makeGeodetic(51.12345, 11.12345);
    $point6 = Point::makeGeodetic(48.12345, 9.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point4, $point5, $point6, $point4], 4326);

    $polygon1 = new Polygon(Dimension::DIMENSION_2D, [$lineString1], 4326);
    $polygon2 = new Polygon(Dimension::DIMENSION_2D, [$lineString2], 4326);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_2D, [$polygon1, $polygon2], 4326);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON(((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)),((10.12345 50.12345,11.12345 51.12345,9.12345 48.12345,10.12345 50.12345)))');
})->group('WKT MultiPolygon');

test('can generate 3D WKT Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 10);
    $point3 = Point::make(7.12345, 48.12345, 10);
    $point4 = Point::make(10.12345, 50.12345, 10);
    $point5 = Point::make(11.12345, 51.12345, 10);
    $point6 = Point::make(9.12345, 48.12345, 10);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point4, $point5, $point6, $point4]);

    $polygon1 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString1]);
    $polygon2 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString2]);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_3DZ, [$polygon1, $polygon2]);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('MULTIPOLYGON Z(((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))');
})->group('WKT MultiPolygon');

test('can generate 3D WKT Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $point4 = Point::makeGeodetic(50.12345, 10.12345, 10);
    $point5 = Point::makeGeodetic(51.12345, 11.12345, 10);
    $point6 = Point::makeGeodetic(48.12345, 9.12345, 10);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point4, $point5, $point6, $point4], 4326);

    $polygon1 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString1], 4326);
    $polygon2 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString2], 4326);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_3DZ, [$polygon1, $polygon2], 4326);

    $multiPolygonWKT = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKT)->toBe('SRID=4326;MULTIPOLYGON Z(((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10)),((10.12345 50.12345 10,11.12345 51.12345 10,9.12345 48.12345 10,10.12345 50.12345 10)))');
})->group('WKT MultiPolygon');


test('can generate 2D WKT GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point, $point2]);
    $lineStringForPolygon = new LineString(Dimension::DIMENSION_2D, [$point, $point2, $point3, $point]);
    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineStringForPolygon]);

    $geometryCollection = new GeometryCollection(Dimension::DIMENSION_2D, [$point, $lineString, $polygon]);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))');
})->group('WKT GeometryCollection');

test('can generate 2D WKT GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point, $point2], 4326);
    $lineStringForPolygon = new LineString(Dimension::DIMENSION_2D, [$point, $point2, $point3, $point], 4326);
    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineStringForPolygon], 4326);

    $geometryCollection = new GeometryCollection(Dimension::DIMENSION_2D, [$point, $lineString, $polygon], 4326);

    $geometryCollectionWKT = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKT)->toBe('SRID=4326;GEOMETRYCOLLECTION(POINT(8.12345 50.12345),LINESTRING(8.12345 50.12345,9.12345 51.12345),POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345)))');
})->group('WKT GeometryCollection');
