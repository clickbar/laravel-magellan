<?php

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});

test('can generate 2D WKB Point', function () {
    $point = Point::make(8.12345, 50.12345);

    $pointWKB = $this->generator->generate($point);
    expect($pointWKB)->toBe('0101000000E561A1D6343F20407958A835CD0F4940');
})->group('WKB Point');

test('can generate 2D WKB Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);

    $pointWKB = $this->generator->generate($point);
    expect($pointWKB)->toBe('0101000020E6100000E561A1D6343F20407958A835CD0F4940');
})->group('WKB Point');

test('can generate 3DZ WKB Point', function () {
    $point = Point::make(8.12345, 50.12345, 10);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('0101000080E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Point');

test('can generate 3DZ WKB Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('01010000A0E6100000E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Point');

test('can generate 3DM WKB Point', function () {
    $point = Point::make(8.12345, 50.12345, null, 10);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('0101000040E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Point');

test('can generate 3DM WKB Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, null, 10);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('0101000060E6100000E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Point');

test('can generate 4D WKB Point', function () {
    $point = Point::make(8.12345, 50.12345, 10, 20);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('01010000C0E561A1D6343F20407958A835CD0F494000000000000024400000000000003440');
})->group('WKB Point');

test('can generate 4D WKB Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10, 20);

    $pointWKB = $this->generator->generate($point);

    expect($pointWKB)->toBe('01010000E0E6100000E561A1D6343F20407958A835CD0F494000000000000024400000000000003440');
})->group('WKB Point');

test('can generate 2D WKB LineString', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940');
})->group('WKB LineString');

test('can generate 2D WKB LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('0102000020E610000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940');
})->group('WKB LineString');

test('can generate 3DZ WKB LineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 3DZ WKB LineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $lineString = LineString::make([$point1, $point2]);

    $lineStringWKB = $this->generator->generate($lineString);

    expect($lineStringWKB)->toBe('01020000A0E610000002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440');
})->group('WKB LineString');

test('can generate 2D WKB MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 49.12345);
    $point4 = Point::make(6.12345, 48.12345);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('010500000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiLineString');

test('can generate 2D WKB MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('0105000020E610000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiLineString');

test('can generate 3DZ WKB MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 49.12345, 30);
    $point4 = Point::make(6.12345, 48.12345, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('010500008002000000010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200008002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiLineString');

test('can generate 3DZ WKB MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('01050000A0E610000002000000010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200008002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiLineString');

test('can generate 2D WKB Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB Polygon');

test('can generate 2D WKB Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E61000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB Polygon');

test('can generate 3DZ WKB Simple Polygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 48.12345, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 3DZ WKB Simple Polygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 30);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);

    $polygon = Polygon::make([$lineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000A0E61000000100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);
    $holePoint1 = Point::make(8.27133, 50.16634);
    $holePoint2 = Point::make(8.198547, 50.035091);
    $holePoint3 = Point::make(8.267211, 50.050966);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000000200000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A154940');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E61000000200000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A154940');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with multi hole', function () {
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

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940');
})->group('WKB Polygon');

test('can generate 2D WKB Polygon with multi hole with SRID', function () {
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

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000020E61000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940');
})->group('WKB Polygon');

test('can generate 3DZ WKB Polygon with multi hole', function () {
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

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000800300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440');
})->group('WKB Polygon');

test('can generate 3DZ WKB Polygon with multi hole with SRID', function () {
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

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000A0E61000000300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440');
})->group('WKB Polygon');

test('can generate 2D WKB MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 49.12345);
    $point4 = Point::make(6.12345, 48.12345);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiPoint');

test('can generate 2D WKB MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(49.12345, 7.12345);
    $point4 = Point::makeGeodetic(48.12345, 6.12345);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000020E6100000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840');
})->group('WKB MultiPoint');

test('can generate 3DZ WKB MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 20);
    $point3 = Point::make(7.12345, 49.12345, 30);
    $point4 = Point::make(6.12345, 48.12345, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000080040000000101000080E561A1D6343F20407958A835CD0F494000000000000024400101000080E561A1D6343F22407958A835CD8F494000000000000034400101000080CAC342AD697E1C407958A835CD8F48400000000000003E400101000080CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiPoint');

test('can generate 3DZ WKB MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('01040000A0E6100000040000000101000080E561A1D6343F20407958A835CD0F494000000000000024400101000080E561A1D6343F22407958A835CD8F494000000000000034400101000080CAC342AD697E1C407958A835CD8F48400000000000003E400101000080CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiPoint');

test('can generate 2D WKB Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);
    $point4 = Point::make(10.12345, 50.12345);
    $point5 = Point::make(11.12345, 51.12345);
    $point6 = Point::make(9.12345, 48.12345);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940');
})->group('WKB MultiPolygon');

test('can generate 2D WKB Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);
    $point4 = Point::makeGeodetic(50.12345, 10.12345);
    $point5 = Point::makeGeodetic(51.12345, 11.12345);
    $point6 = Point::makeGeodetic(48.12345, 9.12345);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('0106000020E61000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940');
})->group('WKB MultiPolygon');

test('can generate 3DZ WKB Simple MultiPolygon', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 10);
    $point3 = Point::make(7.12345, 48.12345, 10);
    $point4 = Point::make(10.12345, 50.12345, 10);
    $point5 = Point::make(11.12345, 51.12345, 10);
    $point6 = Point::make(9.12345, 48.12345, 10);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000800200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 3DZ WKB Simple MultiPolygon with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $point4 = Point::makeGeodetic(50.12345, 10.12345, 10);
    $point5 = Point::makeGeodetic(51.12345, 11.12345, 10);
    $point6 = Point::makeGeodetic(48.12345, 9.12345, 10);

    $lineString1 = LineString::make([$point1, $point2, $point3, $point1]);
    $lineString2 = LineString::make([$point4, $point5, $point6, $point4]);

    $polygon1 = Polygon::make([$lineString1]);
    $polygon2 = Polygon::make([$lineString2]);

    $multiPolygon = MultiPolygon::make([$polygon1, $polygon2]);

    $multiPolygonWKB = $this->generator->generate($multiPolygon);

    expect($multiPolygonWKB)->toBe('01060000A0E61000000200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440');
})->group('WKB MultiPolygon');

test('can generate 2D WKB GeometryCollection', function () {
    $point = Point::make(8.12345, 50.12345);
    $point2 = Point::make(9.12345, 51.12345);
    $point3 = Point::make(7.12345, 48.12345);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB GeometryCollection');


test('can generate 2D WKB GeometryCollection with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);
    $point2 = Point::makeGeodetic(51.12345, 9.12345);
    $point3 = Point::makeGeodetic(48.12345, 7.12345);

    $lineString = LineString::make([$point, $point2]);
    $lineStringForPolygon = LineString::make([$point, $point2, $point3, $point]);
    $polygon = Polygon::make([$lineStringForPolygon]);

    $geometryCollection = GeometryCollection::make([$point, $lineString, $polygon]);

    $geometryCollectionWKB = $this->generator->generate($geometryCollection);

    expect($geometryCollectionWKB)->toBe('0107000020E6100000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940');
})->group('WKB GeometryCollection');
