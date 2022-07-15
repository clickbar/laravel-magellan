<?php

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Dimension;
use Clickbar\Postgis\IO\Generator\Geojson\GeojsonGenerator;

beforeEach(function () {
    $this->generator = new GeojsonGenerator();
});

test('can generate 2D Geojson Point', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);

    $pointGeojson = $this->generator->generate($point);

    expect($pointGeojson)->toBe([
        'type' => 'Point',
        'coordinates' => [8.12345, 50.12345],
    ]);
})->group('Geojson Point');

test('can generate 3D Geojson Point', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10);

    $pointGeojson = $this->generator->generate($point);

    expect($pointGeojson)->toBe([
        'type' => 'Point',
        'coordinates' => [8.12345, 50.12345, 10.0],
    ]);
})->group('Geojson Point');

test('can generate 2D Geojson LineString', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2]);

    $lineStringGeojson = $this->generator->generate($lineString);

    expect($lineStringGeojson)->toBe([
        'type' => 'LineString',
        'coordinates' => [[8.12345, 50.12345], [9.12345, 51.12345]],
    ]);
})->group('Geojson LineString');

test('can generate 3D Geojson LineString', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2]);

    $lineStringGeojson = $this->generator->generate($lineString);

    expect($lineStringGeojson)->toBe([
        'type' => 'LineString',
        'coordinates' => [[8.12345, 50.12345, 10.0], [9.12345, 51.12345, 20.0]],
    ]);
})->group('Geojson LineString');

test('can generate 2D Geojson MultiLineString', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2]);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point3, $point4]);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_2D, [$lineString1, $lineString2]);

    $multiLineStringGeojson = $this->generator->generate($multiLineString);

    expect($multiLineStringGeojson)->toBe([
        'type' => 'MultiLineString',
        'coordinates' => [[[8.12345, 50.12345], [9.12345, 51.12345]], [[7.12345, 49.12345], [6.12345, 48.12345]]],
    ]);
})->group('Geojson MultiLineString');

test('can generate 3D Geojson MultiLineString', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2]);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point3, $point4]);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_3DZ, [$lineString1, $lineString2]);

    $multiLineStringGeojson = $this->generator->generate($multiLineString);

    expect($multiLineStringGeojson)->toBe([
        'type' => 'MultiLineString',
        'coordinates' => [[[8.12345, 50.12345, 10.0], [9.12345, 51.12345, 20.0]], [[7.12345, 49.12345, 30.0], [6.12345, 48.12345, 40.0]]],
    ]);
})->group('Geojson MultiLineString');

test('can generate 2D Geojson Simple Polygon', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString]);

    $polygonGeojson = $this->generator->generate($polygon);

    expect($polygonGeojson)->toBe([
        'type' => 'Polygon',
        'coordinates' => [[[8.12345, 50.12345], [9.12345, 51.12345], [7.12345, 48.12345], [8.12345, 50.12345]]],
    ]);
})->group('Geojson Polygon');

test('can generate 3D Geojson Simple Polygon', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString]);

    $polygonGeojson = $this->generator->generate($polygon);

    expect($polygonGeojson)->toBe([
        'type' => 'Polygon',
        'coordinates' => [[[8.12345, 50.12345, 10.0], [9.12345, 51.12345, 20.0], [7.12345, 48.12345, 30.0], [8.12345, 50.12345, 10.0]]],
    ]);
})->group('Geojson Polygon');

test('can generate 2D Geojson Polygon with single hole', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString]);

    $polygonGeojson = $this->generator->generate($polygon);

    expect($polygonGeojson)->toBe([
        'type' => 'Polygon',
        'coordinates' => [[[8.12345, 50.12345], [9.12345, 51.12345], [7.12345, 48.12345], [8.12345, 50.12345]], [[8.27133, 50.16634], [8.198547, 50.035091], [8.267211, 50.050966], [8.27133, 50.16634]]],
    ]);
})->group('Geojson Polygon');

test('can generate 2D Geojson Polygon with multi hole', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211);
    $hole2Point1 = Point::makeGeodetic(50.322669, 8.393554);
    $hole2Point2 = Point::makeGeodetic(50.229637, 8.367462);
    $hole2Point3 = Point::makeGeodetic(50.341078, 8.491058);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = new LineString(Dimension::DIMENSION_2D, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString, $hole2LineString]);

    $polygonGeojson = $this->generator->generate($polygon);

    expect($polygonGeojson)->toBe([
        'type' => 'Polygon',
        'coordinates' => [
            [[8.12345, 50.12345], [9.12345, 51.12345], [7.12345, 48.12345], [8.12345, 50.12345]],
            [[8.27133, 50.16634], [8.198547, 50.035091], [8.267211, 50.050966], [8.27133, 50.16634]],
            [[8.393554, 50.322669], [8.367462, 50.229637], [8.491058, 50.341078], [8.393554, 50.322669]],
        ],
    ]);
})->group('Geojson Polygon');

test('can generate 3D Geojson Polygon with multi hole', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 10);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 10);
    $hole2Point1 = Point::makeGeodetic(50.322669, 8.393554, 10);
    $hole2Point2 = Point::makeGeodetic(50.229637, 8.367462, 10);
    $hole2Point3 = Point::makeGeodetic(50.341078, 8.491058, 10);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_3DZ, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = new LineString(Dimension::DIMENSION_3DZ, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString, $holeLineString, $hole2LineString]);

    $polygonGeojson = $this->generator->generate($polygon);

    expect($polygonGeojson)->toBe([
        'type' => 'Polygon',
        'coordinates' => [
            [[8.12345, 50.12345, 10.0], [9.12345, 51.12345, 10.0], [7.12345, 48.12345, 10.0], [8.12345, 50.12345, 10.0]],
            [[8.27133, 50.16634, 10.0], [8.198547, 50.035091, 10.0], [8.267211, 50.050966, 10.0], [8.27133, 50.16634, 10.0]],
            [[8.393554, 50.322669, 10.0], [8.367462, 50.229637, 10.0], [8.491058, 50.341078, 10.0], [8.393554, 50.322669, 10.0]],
        ],
    ]);
})->group('Geojson Polygon');

test('can generate 2D Geojson MultiPoint', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point4]);

    $multiPointGeojson = $this->generator->generate($multiPoint);

    expect($multiPointGeojson)->toBe([
        'type' => 'MultiPoint',
        'coordinates' => [
            [8.12345, 50.12345], [9.12345, 51.12345], [7.12345, 49.12345], [6.12345, 48.12345],
        ],
    ]);
})->group('Geojson MultiPoint');

test('can generate 3D Geojson MultiPoint', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point4]);

    $multiPointGeojson = $this->generator->generate($multiPoint);

    expect($multiPointGeojson)->toBe([
        'type' => 'MultiPoint',
        'coordinates' => [
            [8.12345, 50.12345, 10.0], [9.12345, 51.12345, 20.0], [7.12345, 49.12345, 30.0], [6.12345, 48.12345, 40.0],
        ],
    ]);
})->group('Geojson MultiPoint');

test('can generate 2D Geojson Simple MultiPolygon', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $point4 = Point::makeGeodetic(50.12345, 10.12345);
    $point5 = Point::makeGeodetic(51.12345, 11.12345);
    $point6 = Point::makeGeodetic(48.12345, 9.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point4, $point5, $point6, $point4]);

    $polygon1 = new Polygon(Dimension::DIMENSION_2D, [$lineString1]);
    $polygon2 = new Polygon(Dimension::DIMENSION_2D, [$lineString2]);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_2D, [$polygon1, $polygon2]);

    $multiPolygonGeojson = $this->generator->generate($multiPolygon);

    expect($multiPolygonGeojson)->toBe([
        'type' => 'MultiPolygon',
        'coordinates' => [
            [[[8.12345, 50.12345], [9.12345, 51.12345], [7.12345, 48.12345], [8.12345, 50.12345]]],
            [[[10.12345, 50.12345], [11.12345, 51.12345], [9.12345, 48.12345], [10.12345, 50.12345]]],
        ],
    ]);
})->group('Geojson MultiPolygon');

test('can generate 3D Geojson Simple MultiPolygon', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $point4 = Point::makeGeodetic(50.12345, 10.12345, 10);
    $point5 = Point::makeGeodetic(51.12345, 11.12345, 10);
    $point6 = Point::makeGeodetic(48.12345, 9.12345, 10);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point4, $point5, $point6, $point4]);

    $polygon1 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString1]);
    $polygon2 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString2]);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_3DZ, [$polygon1, $polygon2]);

    $multiPolygonGeojson = $this->generator->generate($multiPolygon);

    expect($multiPolygonGeojson)->toBe([
        'type' => 'MultiPolygon',
        'coordinates' => [
            [[[8.12345, 50.12345, 10.0], [9.12345, 51.12345, 10.0], [7.12345, 48.12345, 10.0], [8.12345, 50.12345, 10.0]]],
            [[[10.12345, 50.12345, 10.0], [11.12345, 51.12345, 10.0], [9.12345, 48.12345, 10.0], [10.12345, 50.12345, 10.0]]],
        ],
    ]);
})->group('Geojson MultiPolygon');


test('can generate 2D Geojson GeometryCollection', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point, $point2]);
    $lineStringForPolygon = new LineString(Dimension::DIMENSION_2D, [$point, $point2, $point3, $point]);
    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineStringForPolygon]);

    $geometryCollection = new GeometryCollection(Dimension::DIMENSION_2D, [$point, $lineString, $polygon]);

    $geometryCollectionGeojson = $this->generator->generate($geometryCollection);

    expect($geometryCollectionGeojson)->toBe([
        'type' => 'GeometryCollection',
        'geometries' => [
            [
                'type' => 'Point',
                'coordinates' => [8.12345, 50.12345],],
            [
                'type' => 'LineString',
                'coordinates' => [[8.12345, 50.12345], [9.12345, 51.12345]],],
            [
                'type' => 'Polygon',
                'coordinates' => [[[8.12345, 50.12345], [9.12345, 51.12345], [7.12345, 48.12345], [8.12345, 50.12345]]],
            ],
        ],
    ]);
})->group('Geojson GeometryCollection');
