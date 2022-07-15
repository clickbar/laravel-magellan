<?php

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Dimension;
use Clickbar\Postgis\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});

test('can generate 2D WKB Point', function () {
    $point = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);

    $pointWKB = $this->generator->generate($point);
    expect($pointWKB)->toBe('0101000000E561A1D6343F20407958A835CD0F4940');
})->group('WKB Point');

test('can generate 2D WKB Point with SRID', function () {
    $point = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);

    $pointWKB = $this->generator->generate($point);
    expect($pointWKB)->toBe('0101000020E6100000E561A1D6343F20407958A835CD0F4940');
})->group('WKB Point');

test('can generate 3D WKB Point', function () {
    $point = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('0101000080E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Point');

test('can generate 3D WKB Point with SRID', function () {
    $point = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10, 4326);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('01010000A0E6100000E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Point');

test('can generate 2D WKB LineString', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940');
})->group('WKB LineString');

test('can generate 2D WKB LineString with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2], 4326);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('0102000020E610000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940');
})->group('WKB LineString');

test('can generate 3D WKB LineString', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20);
    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 3D WKB LineString with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10, 4326);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20, 4326);
    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2], 4326);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('01020000A0E610000002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 2D WKB MultiLineString', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $point3 = new Point(Dimension::DIMENSION_2D, 49.12345, 7.12345);
    $point4 = new Point(Dimension::DIMENSION_2D, 48.12345, 6.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2]);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point3, $point4]);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_2D, [$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('010500000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiLineString');

test('can generate 2D WKB MultiLineString with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $point3 = new Point(Dimension::DIMENSION_2D, 49.12345, 7.12345, null, 4326);
    $point4 = new Point(Dimension::DIMENSION_2D, 48.12345, 6.12345, null, 4326);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point3, $point4], 4326);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_2D, [$lineString1, $lineString2], 4326);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('0105000020E610000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiLineString');

test('can generate 3D WKB MultiLineString', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 49.12345, 7.12345, 30);
    $point4 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 6.12345, 40);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2]);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point3, $point4]);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_3DZ, [$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('010500008002000000010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200008002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiLineString');

test('can generate 3D WKB MultiLineString with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10, 4326);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20, 4326);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 49.12345, 7.12345, 30, 4326);
    $point4 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 6.12345, 40, 4326);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point3, $point4], 4326);

    $multiLineString = new MultiLineString(Dimension::DIMENSION_3DZ, [$lineString1, $lineString2], 4326);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('01050000A0E610000002000000010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200008002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiLineString');

test('can generate 2D WKB Simple Polygon', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB Polygon');

test('can generate 2D WKB Simple Polygon with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345, null, 4326);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString], 4326);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E61000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB Polygon');

test('can generate 3D WKB Simple Polygon', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 7.12345, 30);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 3D WKB Simple Polygon with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10, 4326);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20, 4326);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 7.12345, 30, 4326);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString], 4326);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000A0E61000000100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with single hole', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345);
    $holePoint1 = new Point(Dimension::DIMENSION_2D, 50.16634, 8.27133);
    $holePoint2 = new Point(Dimension::DIMENSION_2D, 50.035091, 8.198547);
    $holePoint3 = new Point(Dimension::DIMENSION_2D, 50.050966, 8.267211);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000000200000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A154940');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with single hole with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345, null, 4326);
    $holePoint1 = new Point(Dimension::DIMENSION_2D, 50.16634, 8.27133, null, 4326);
    $holePoint2 = new Point(Dimension::DIMENSION_2D, 50.035091, 8.198547, null, 4326);
    $holePoint3 = new Point(Dimension::DIMENSION_2D, 50.050966, 8.267211, null, 4326);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString], 4326);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E61000000200000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A154940');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with multi hole', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345);
    $holePoint1 = new Point(Dimension::DIMENSION_2D, 50.16634, 8.27133);
    $holePoint2 = new Point(Dimension::DIMENSION_2D, 50.035091, 8.198547);
    $holePoint3 = new Point(Dimension::DIMENSION_2D, 50.050966, 8.267211);
    $hole2Point1 = new Point(Dimension::DIMENSION_2D, 50.322669, 8.393554);
    $hole2Point2 = new Point(Dimension::DIMENSION_2D, 50.229637, 8.367462);
    $hole2Point3 = new Point(Dimension::DIMENSION_2D, 50.341078, 8.491058);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = new LineString(Dimension::DIMENSION_2D, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString, $hole2LineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with multi hole with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345, null, 4326);
    $holePoint1 = new Point(Dimension::DIMENSION_2D, 50.16634, 8.27133, null, 4326);
    $holePoint2 = new Point(Dimension::DIMENSION_2D, 50.035091, 8.198547, null, 4326);
    $holePoint3 = new Point(Dimension::DIMENSION_2D, 50.050966, 8.267211, null, 4326);
    $hole2Point1 = new Point(Dimension::DIMENSION_2D, 50.322669, 8.393554, null, 4326);
    $hole2Point2 = new Point(Dimension::DIMENSION_2D, 50.229637, 8.367462, null, 4326);
    $hole2Point3 = new Point(Dimension::DIMENSION_2D, 50.341078, 8.491058, null, 4326);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);
    $holeLineString = new LineString(Dimension::DIMENSION_2D, [$holePoint1, $holePoint2, $holePoint3, $holePoint1], 4326);
    $hole2LineString = new LineString(Dimension::DIMENSION_2D, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineString, $holeLineString, $hole2LineString], 4326);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E61000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940');
})->group('WKB Polygon');

test('can generate 3D WKB Polygon with multi hole', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 10);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 7.12345, 10);
    $holePoint1 = new Point(Dimension::DIMENSION_3DZ, 50.16634, 8.27133, 10);
    $holePoint2 = new Point(Dimension::DIMENSION_3DZ, 50.035091, 8.198547, 10);
    $holePoint3 = new Point(Dimension::DIMENSION_3DZ, 50.050966, 8.267211, 10);
    $hole2Point1 = new Point(Dimension::DIMENSION_3DZ, 50.322669, 8.393554, 10);
    $hole2Point2 = new Point(Dimension::DIMENSION_3DZ, 50.229637, 8.367462, 10);
    $hole2Point3 = new Point(Dimension::DIMENSION_3DZ, 50.341078, 8.491058, 10);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);
    $holeLineString = new LineString(Dimension::DIMENSION_3DZ, [$holePoint1, $holePoint2, $holePoint3, $holePoint1]);
    $hole2LineString = new LineString(Dimension::DIMENSION_3DZ, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1]);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString, $holeLineString, $hole2LineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000800300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440');
})->group('WKB Polygon');

test('can generate 3D WKB Polygon with multi hole with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10, 4326);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 10, 4326);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 7.12345, 10, 4326);
    $holePoint1 = new Point(Dimension::DIMENSION_3DZ, 50.16634, 8.27133, 10, 4326);
    $holePoint2 = new Point(Dimension::DIMENSION_3DZ, 50.035091, 8.198547, 10, 4326);
    $holePoint3 = new Point(Dimension::DIMENSION_3DZ, 50.050966, 8.267211, 10, 4326);
    $hole2Point1 = new Point(Dimension::DIMENSION_3DZ, 50.322669, 8.393554, 10, 4326);
    $hole2Point2 = new Point(Dimension::DIMENSION_3DZ, 50.229637, 8.367462, 10, 4326);
    $hole2Point3 = new Point(Dimension::DIMENSION_3DZ, 50.341078, 8.491058, 10, 4326);

    $lineString = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1], 4326);
    $holeLineString = new LineString(Dimension::DIMENSION_3DZ, [$holePoint1, $holePoint2, $holePoint3, $holePoint1], 4326);
    $hole2LineString = new LineString(Dimension::DIMENSION_3DZ, [$hole2Point1, $hole2Point2, $hole2Point3, $hole2Point1], 4326);

    $polygon = new Polygon(Dimension::DIMENSION_3DZ, [$lineString, $holeLineString, $hole2LineString], 4326);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000A0E61000000300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440');
})->group('WKB Polygon');

test('can generate 2D WKB MultiPoint', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $point3 = new Point(Dimension::DIMENSION_2D, 49.12345, 7.12345);
    $point4 = new Point(Dimension::DIMENSION_2D, 48.12345, 6.12345);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiPoint');

test('can generate 2D WKB MultiPoint with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $point3 = new Point(Dimension::DIMENSION_2D, 49.12345, 7.12345, null, 4326);
    $point4 = new Point(Dimension::DIMENSION_2D, 48.12345, 6.12345, null, 4326);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point4], 4326);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000020E6100000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiPoint');

test('can generate 3D WKB MultiPoint', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 49.12345, 7.12345, 30);
    $point4 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 6.12345, 40);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000080040000000101000080E561A1D6343F20407958A835CD0F494000000000000024400101000080E561A1D6343F22407958A835CD8F494000000000000034400101000080CAC342AD697E1C407958A835CD8F48400000000000003E400101000080CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiPoint');

test('can generate 3D WKB MultiPoint with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10, 4326);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 20, 4326);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 49.12345, 7.12345, 30, 4326);
    $point4 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 6.12345, 40, 4326);

    $multiPoint = new MultiPoint(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point4], 4326);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('01040000A0E6100000040000000101000080E561A1D6343F20407958A835CD0F494000000000000024400101000080E561A1D6343F22407958A835CD8F494000000000000034400101000080CAC342AD697E1C407958A835CD8F48400000000000003E400101000080CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiPoint');

test('can generate 2D WKB Simple MultiPolygon', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345);
    $point4 = new Point(Dimension::DIMENSION_2D, 50.12345, 10.12345);
    $point5 = new Point(Dimension::DIMENSION_2D, 51.12345, 11.12345);
    $point6 = new Point(Dimension::DIMENSION_2D, 48.12345, 9.12345);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1]);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point4, $point5, $point6, $point4]);

    $polygon1 = new Polygon(Dimension::DIMENSION_2D, [$lineString1]);
    $polygon2 = new Polygon(Dimension::DIMENSION_2D, [$lineString2]);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_2D, [$polygon1, $polygon2]);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940');
})->group('WKB MultiPolygon');

test('can generate 2D WKB Simple MultiPolygon with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345, null, 4326);
    $point4 = new Point(Dimension::DIMENSION_2D, 50.12345, 10.12345, null, 4326);
    $point5 = new Point(Dimension::DIMENSION_2D, 51.12345, 11.12345, null, 4326);
    $point6 = new Point(Dimension::DIMENSION_2D, 48.12345, 9.12345, null, 4326);

    $lineString1 = new LineString(Dimension::DIMENSION_2D, [$point1, $point2, $point3, $point1], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_2D, [$point4, $point5, $point6, $point4], 4326);

    $polygon1 = new Polygon(Dimension::DIMENSION_2D, [$lineString1], 4326);
    $polygon2 = new Polygon(Dimension::DIMENSION_2D, [$lineString2], 4326);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_2D, [$polygon1, $polygon2], 4326);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('0106000020E61000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940');
})->group('WKB MultiPolygon');

test('can generate 3D WKB Simple MultiPolygon', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 10);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 7.12345, 10);
    $point4 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 10.12345, 10);
    $point5 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 11.12345, 10);
    $point6 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 9.12345, 10);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1]);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point4, $point5, $point6, $point4]);

    $polygon1 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString1]);
    $polygon2 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString2]);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_3DZ, [$polygon1, $polygon2]);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000800200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 3D WKB Simple MultiPolygon with SRID', function () {
    $point1 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 8.12345, 10, 4326);
    $point2 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 9.12345, 10, 4326);
    $point3 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 7.12345, 10, 4326);
    $point4 = new Point(Dimension::DIMENSION_3DZ, 50.12345, 10.12345, 10, 4326);
    $point5 = new Point(Dimension::DIMENSION_3DZ, 51.12345, 11.12345, 10, 4326);
    $point6 = new Point(Dimension::DIMENSION_3DZ, 48.12345, 9.12345, 10, 4326);

    $lineString1 = new LineString(Dimension::DIMENSION_3DZ, [$point1, $point2, $point3, $point1], 4326);
    $lineString2 = new LineString(Dimension::DIMENSION_3DZ, [$point4, $point5, $point6, $point4], 4326);

    $polygon1 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString1], 4326);
    $polygon2 = new Polygon(Dimension::DIMENSION_3DZ, [$lineString2], 4326);

    $multiPolygon = new MultiPolygon(Dimension::DIMENSION_3DZ, [$polygon1, $polygon2], 4326);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000A0E61000000200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 2D WKB GeometryCollection', function () {
    $point = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point, $point2]);
    $lineStringForPolygon = new LineString(Dimension::DIMENSION_2D, [$point, $point2, $point3, $point]);
    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineStringForPolygon]);

    $geometryCollection = new GeometryCollection(Dimension::DIMENSION_2D, [$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB GeometryCollection');


test('can generate 2D WKB GeometryCollection with SRID', function () {
    $point = new Point(Dimension::DIMENSION_2D, 50.12345, 8.12345, null, 4326);
    $point2 = new Point(Dimension::DIMENSION_2D, 51.12345, 9.12345, null, 4326);
    $point3 = new Point(Dimension::DIMENSION_2D, 48.12345, 7.12345, null, 4326);

    $lineString = new LineString(Dimension::DIMENSION_2D, [$point, $point2], 4326);
    $lineStringForPolygon = new LineString(Dimension::DIMENSION_2D, [$point, $point2, $point3, $point], 4326);
    $polygon = new Polygon(Dimension::DIMENSION_2D, [$lineStringForPolygon], 4326);

    $geometryCollection = new GeometryCollection(Dimension::DIMENSION_2D, [$point, $lineString, $polygon], 4326);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000020E6100000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB GeometryCollection');
