<?php

use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator();
});


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

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

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

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634),(8.393554 50.322669,8.367462 50.229637,8.491058 50.341078,8.393554 50.322669))');
})->group('WKT Polygon');

test('can generate 3DZ WKT Polygon with multi hole', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 10);
    $point3 = Point::make(7.12345, 48.12345, 10);
    $holePoint1 = Point::make(8.27133, 50.16634, 10);
    $holePoint2 = Point::make(8.198547, 50.035091, 10);
    $holePoint3 = Point::make(8.267211, 50.050966, 10);
    $hole2Point1 = Point::make(8.393554, 50.322669, 10);
    $hole2Point2 = Point::make(8.367462, 50.229637, 10);
    $hole2Point3 = Point::make(8.491058, 50.341078, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))');
})->group('WKT Polygon');

test('can generate 3DZ WKT Polygon with multi hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 10);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 10);
    $hole2Point1 = Point::makeGeodetic(50.322669, 8.393554, 10);
    $hole2Point2 = Point::makeGeodetic(50.229637, 8.367462, 10);
    $hole2Point3 = Point::makeGeodetic(50.341078, 8.491058, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))');
})->group('WKT Polygon');


test('can generate 3DM WKT Polygon with multi hole', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 10);
    $point3 = Point::make(7.12345, 48.12345, null, 10);
    $holePoint1 = Point::make(8.27133, 50.16634, null, 10);
    $holePoint2 = Point::make(8.198547, 50.035091, null, 10);
    $holePoint3 = Point::make(8.267211, 50.050966, null, 10);
    $hole2Point1 = Point::make(8.393554, 50.322669, null, 10);
    $hole2Point2 = Point::make(8.367462, 50.229637, null, 10);
    $hole2Point3 = Point::make(8.491058, 50.341078, null, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON M((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))');
})->group('WKT Polygon');

test('can generate 3DM WKT Polygon with multi hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 10);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, null, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, null, 10);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, null, 10);
    $hole2Point1 = Point::makeGeodetic(50.322669, 8.393554, null, 10);
    $hole2Point2 = Point::makeGeodetic(50.229637, 8.367462, null, 10);
    $hole2Point3 = Point::makeGeodetic(50.341078, 8.491058, null, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON M((8.12345 50.12345 10,9.12345 51.12345 10,7.12345 48.12345 10,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 10,8.267211 50.050966 10,8.27133 50.16634 10),(8.393554 50.322669 10,8.367462 50.229637 10,8.491058 50.341078 10,8.393554 50.322669 10))');
})->group('WKT Polygon');



test('can generate 4D WKT Polygon with multi hole', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 10, 12);
    $point3 = Point::make(7.12345, 48.12345, 10, 12);
    $holePoint1 = Point::make(8.27133, 50.16634, 10, 12);
    $holePoint2 = Point::make(8.198547, 50.035091, 10, 12);
    $holePoint3 = Point::make(8.267211, 50.050966, 10, 12);
    $hole2Point1 = Point::make(8.393554, 50.322669, 10, 12);
    $hole2Point2 = Point::make(8.367462, 50.229637, 10, 12);
    $hole2Point3 = Point::make(8.491058, 50.341078, 10, 12);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12),(8.27133 50.16634 10 12,8.198547 50.035091 10 12,8.267211 50.050966 10 12,8.27133 50.16634 10 12),(8.393554 50.322669 10 12,8.367462 50.229637 10 12,8.491058 50.341078 10 12,8.393554 50.322669 10 12))');
})->group('WKT Polygon');

test('can generate 4D WKT Polygon with multi hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10, 12);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10, 12);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10, 12);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 10, 12);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 10, 12);
    $hole2Point1 = Point::makeGeodetic(50.322669, 8.393554, 10, 12);
    $hole2Point2 = Point::makeGeodetic(50.229637, 8.367462, 10, 12);
    $hole2Point3 = Point::makeGeodetic(50.341078, 8.491058, 10, 12);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = LineString::make([$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = Polygon::make([$lineString, $holeLineString, $hole2LineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 10 12,7.12345 48.12345 10 12,8.12345 50.12345 10 12),(8.27133 50.16634 10 12,8.198547 50.035091 10 12,8.267211 50.050966 10 12,8.27133 50.16634 10 12),(8.393554 50.322669 10 12,8.367462 50.229637 10 12,8.491058 50.341078 10 12,8.393554 50.322669 10 12))');
})->group('WKT Polygon');
