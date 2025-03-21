<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator;
});

test('can generate 2D WKT Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);
    $holePoint1 = Point::make(8.27133, 50.16634);
    $holePoint2 = Point::make(8.198547, 50.035091);
    $holePoint3 = Point::make(8.267211, 50.050966);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

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

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON((8.12345 50.12345,9.12345 51.12345,7.12345 48.12345,8.12345 50.12345),(8.27133 50.16634,8.198547 50.035091,8.267211 50.050966,8.27133 50.16634))');
})->group('WKT Polygon');

test('can generate 3DZ WKT Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 48.12345, 30);
    $holePoint1 = Point::make(8.27133, 50.16634, 10);
    $holePoint2 = Point::make(8.198547, 50.035091, 20);
    $holePoint3 = Point::make(8.267211, 50.050966, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 20,8.267211 50.050966 30,8.27133 50.16634 10))');
})->group('WKT Polygon');

test('can generate 3DZ WKT Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 20);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON Z((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 20,8.267211 50.050966 30,8.27133 50.16634 10))');
})->group('WKT Polygon');

test('can generate 3DM WKT Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 48.12345, null, 30);
    $holePoint1 = Point::make(8.27133, 50.16634, null, 10);
    $holePoint2 = Point::make(8.198547, 50.035091, null, 20);
    $holePoint3 = Point::make(8.267211, 50.050966, null, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 20,8.267211 50.050966 30,8.27133 50.16634 10))');
})->group('WKT Polygon');

test('can generate 3DM WKT Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 30);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, null, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, null, 20);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, null, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON M((8.12345 50.12345 10,9.12345 51.12345 20,7.12345 48.12345 30,8.12345 50.12345 10),(8.27133 50.16634 10,8.198547 50.035091 20,8.267211 50.050966 30,8.27133 50.16634 10))');
})->group('WKT Polygon');

test('can generate 4D WKT Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 48.12345, 30, 32);
    $holePoint1 = Point::make(8.27133, 50.16634, 10, 12);
    $holePoint2 = Point::make(8.198547, 50.035091, 20, 22);
    $holePoint3 = Point::make(8.267211, 50.050966, 30, 32);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12),(8.27133 50.16634 10 12,8.198547 50.035091 20 22,8.267211 50.050966 30 32,8.27133 50.16634 10 12))');
})->group('WKT Polygon');

test('can generate 4D WKT Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30, 32);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10, 12);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 20, 22);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 30, 32);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKT = $this->generator->generate($polygon);

    expect($polygonWKT)->toBe('SRID=4326;POLYGON ZM((8.12345 50.12345 10 12,9.12345 51.12345 20 22,7.12345 48.12345 30 32,8.12345 50.12345 10 12),(8.27133 50.16634 10 12,8.198547 50.035091 20 22,8.267211 50.050966 30 32,8.27133 50.16634 10 12))');
})->group('WKT Polygon');
