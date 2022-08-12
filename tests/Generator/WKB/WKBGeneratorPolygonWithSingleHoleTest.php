<?php

use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});


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


test('can generate 3DZ WKB Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345, 10);
    $point2 = Point::make(9.12345, 51.12345, 10);
    $point3 = Point::make(7.12345, 48.12345, 10);
    $holePoint1 = Point::make(8.27133, 50.16634, 10);
    $holePoint2 = Point::make(8.198547, 50.035091, 10);
    $holePoint3 = Point::make(8.267211, 50.050966, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000800200000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A1549400000000000002440');
})->group('WKB Polygon');

test('can generate 3DZ WKB Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 10);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000A0E61000000200000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A1549400000000000002440');
})->group('WKB Polygon');

test('can generate 3DM WKB Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 10);
    $point3 = Point::make(7.12345, 48.12345, null, 10);
    $holePoint1 = Point::make(8.27133, 50.16634, null, 10);
    $holePoint2 = Point::make(8.198547, 50.035091, null, 10);
    $holePoint3 = Point::make(8.267211, 50.050966, null, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000400200000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A1549400000000000002440');
})->group('WKB Polygon');

test('can generate 3DM WKB Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 10);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, null, 10);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, null, 10);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, null, 10);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, null, 10);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('0103000060E61000000200000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A1549400000000000002440');
})->group('WKB Polygon');

test('can generate 4D WKB Polygon with single hole', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 10, 12);
    $point3 = Point::make(7.12345, 48.12345, 10, 12);
    $holePoint1 = Point::make(8.27133, 50.16634, 10, 12);
    $holePoint2 = Point::make(8.198547, 50.035091, 10, 12);
    $holePoint3 = Point::make(8.267211, 50.050966, 10, 12);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000C00200000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284004000000EDD808C4EB8A204021020EA14A154940000000000000244000000000000028401570CFF3A765204025B1A4DC7D044940000000000000244000000000000028404E4354E1CF8820409E9ACB0D8606494000000000000024400000000000002840EDD808C4EB8A204021020EA14A15494000000000000024400000000000002840');
})->group('WKB Polygon');

test('can generate 4D WKB Polygon with single hole with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 10, 12);
    $point3 = Point::makeGeodetic(48.12345, 7.12345, 10, 12);
    $holePoint1 = Point::makeGeodetic(50.16634, 8.27133, 10, 12);
    $holePoint2 = Point::makeGeodetic(50.035091, 8.198547, 10, 12);
    $holePoint3 = Point::makeGeodetic(50.050966, 8.267211, 10, 12);

    $lineString = LineString::make([$point1, $point2, $point3, $point1]);
    $holeLineString = LineString::make([$holePoint1, $holePoint2, $holePoint3, $holePoint1]);

    $polygon = Polygon::make([$lineString, $holeLineString]);

    $polygonWKB = $this->generator->generate($polygon);

    expect($polygonWKB)->toBe('01030000E0E61000000200000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284004000000EDD808C4EB8A204021020EA14A154940000000000000244000000000000028401570CFF3A765204025B1A4DC7D044940000000000000244000000000000028404E4354E1CF8820409E9ACB0D8606494000000000000024400000000000002840EDD808C4EB8A204021020EA14A15494000000000000024400000000000002840');
})->group('WKB Polygon');
