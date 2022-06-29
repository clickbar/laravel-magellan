<?php

use Clickbar\Postgis\Geometries\GeometryCollection;
use Clickbar\Postgis\Geometries\GeometryFactory;
use Clickbar\Postgis\Geometries\LineString;
use Clickbar\Postgis\Geometries\MultiLineString;
use Clickbar\Postgis\Geometries\MultiPoint;
use Clickbar\Postgis\Geometries\MultiPolygon;
use Clickbar\Postgis\Geometries\Point;
use Clickbar\Postgis\Geometries\Polygon;
use Clickbar\Postgis\IO\Parser\WKB\WKBParser;

beforeEach(function () {
    $this->parser = new WKBParser(new GeometryFactory());
});

test('can parse 2D WKB Point', function () {
    $pointWKB = '0101000000E561A1D6343F20407958A835CD0F4940'; // st_makepoint(8.12345, 50.12345)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
})->group('WKB Point');

test('can parse 2D WKB Point with SRID', function () {
    $pointWKB = '0101000020E6100000E561A1D6343F20407958A835CD0F4940'; // st_setsrid(st_makepoint(8.12345, 50.12345), 4326)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
    expect($point->getSrid())->toBe(4326);
})->group('WKB Point');

test('can parse 3D WKB Point', function () {
    $pointWKB = '0101000080E561A1D6343F20407958A835CD0F49400000000000002440'; // st_makepoint(8.12345, 50.12345, 10)

    $point = $this->parser->parse($pointWKB);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
    expect($point->getAltitude())->toBe(10.0);
})->group('WKB Point');

test('can parse 2D WKB LineString', function () {
    $lineStringWKB = '010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940'; // st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345))

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
})->group('WKB LineString');

test('can parse 2D WKB LineString With SRID', function () {
    $lineStringWKB = '0102000020E610000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940'; // st_setsrid(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), 4326)

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($lineString->getSrid())->toBe(4326);
})->group('WKB LineString');

test('can parse 3D WKB LineString', function () {
    $lineStringWKB = '010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440'; // st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345))

    $lineString = $this->parser->parse($lineStringWKB);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getAltitude())->toBe(20.0);
})->group('WKB LineString');

test('can parse 2D WKB MultiLineString', function () {
    $multiLineStringWKB = '010500000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840'; // st_collect(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makeline(st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)))

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
})->group('WKB MultiLineString');

test('can parse 2D WKB MultiLineString With SRID', function () {
    $multiLineStringWKB = '0105000020E610000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840'; // st_setsrid(st_collect(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makeline(st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345))), 4326)

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
    expect($multiLineString->getSrid())->toBe(4326);
})->group('WKB MultiLineString');

test('can parse 3D WKB MultiLineString', function () {
    $multiLineStringWKB = '010500008002000000010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200008002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440'; // st_collect(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makeline(st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)))

    $multiLineString = $this->parser->parse($multiLineStringWKB);

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
})->group('WKB MultiLineString');

test('can parse 2D WKB Simple Polygon', function () {
    $polygonWKB = '01030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]));

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
})->group('WKB Polygon');

test('can parse 2D WKB Simple Polygon with SRID', function () {
    $polygonWKB = '0103000020E61000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)])), 4326)

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getSrid())->toBe(4326);
})->group('WKB Polygon');

test('can parse 3D WKB Simple Polygon', function () {
    $polygonWKB = '01030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440CAC342AD697E1C407958A835CD0F48400000000000003E40E561A1D6343F20407958A835CD0F49400000000000002440'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345,20), st_makepoint(7.12345, 48.12345,30), st_makepoint(8.12345, 50.12345,10)]))

    $polygon = $this->parser->parse($polygonWKB);

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
})->group('WKB Polygon');

test('can parse 2D WKB Polygon with single hole', function () {
    $polygonWKB = '01030000000200000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A154940'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133, 50.16634), st_makepoint(8.198547,50.035091), st_makepoint(8.267211,50.050966), st_makepoint(8.27133,50.16634)])]);

    $polygon = $this->parser->parse($polygonWKB);

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
})->group('WKB Polygon');

test('can parse 2D WKB Polygon with single hole with SRID', function () {
    $polygonWKB = '01030000A0E61000000200000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A1549400000000000002440'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345,10), st_makepoint(7.12345, 48.12345,10), st_makepoint(8.12345, 50.12345,10)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133, 50.16634,10), st_makepoint(8.198547,50.035091,10), st_makepoint(8.267211,50.050966,10), st_makepoint(8.27133,50.16634,10)])]), 4326)

    $polygon = $this->parser->parse($polygonWKB);

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

    expect($polygon->getSrid())->toBe(4326);
})->group('WKB Polygon');

test('can parse 2D WKB Polygon with multi hole', function () {
    $polygonWKB = '01030000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133, 50.16634), st_makepoint(8.198547,50.035091), st_makepoint(8.267211,50.050966), st_makepoint(8.27133,50.16634)]), st_makeline(ARRAY[st_makepoint(8.393554, 50.322669),st_makepoint(8.367462, 50.229637),st_makepoint(8.491058, 50.341078),st_makepoint(8.393554, 50.322669)])])

    $polygon = $this->parser->parse($polygonWKB);

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
})->group('WKB Polygon');

test('can parse 2D WKB Polygon with multi hole with SRID', function () {
    $polygonWKB = '0103000020E61000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133, 50.16634), st_makepoint(8.198547,50.035091), st_makepoint(8.267211,50.050966), st_makepoint(8.27133,50.16634)]), st_makeline(ARRAY[st_makepoint(8.393554, 50.322669),st_makepoint(8.367462, 50.229637),st_makepoint(8.491058, 50.341078),st_makepoint(8.393554, 50.322669)])]),4326)

    $polygon = $this->parser->parse($polygonWKB);

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

    expect($polygon->getSRID())->toBe(4326);
})->group('WKB Polygon');

test('can parse 3D WKB Polygon with multi hole', function () {
    $polygonWKB = '01030000800300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10), st_makepoint(9.12345,51.12345,10), st_makepoint(7.12345,48.12345,10), st_makepoint(8.12345,50.12345,10)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133,50.16634,10), st_makepoint(8.198547,50.035091,10), st_makepoint(8.267211,50.050966,10), st_makepoint(8.27133,50.16634,10)]), st_makeline(ARRAY[st_makepoint(8.393554,50.322669,10),st_makepoint(8.367462,50.229637,10),st_makepoint(8.491058,50.341078,10),st_makepoint(8.393554,50.322669,10)])])


    $polygon = $this->parser->parse($polygonWKB);

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
})->group('WKB Polygon');

test('can parse 2D WKB MultiPoint', function () {
    $multiPointWKB = '0104000000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)])

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);
})->group('WKB MultiPoint');

test('can parse 2D WKB MultiPoint wit SRID', function () {
    $multiPointWKB = '0104000020E6100000040000000101000000E561A1D6343F20407958A835CD0F49400101000000E561A1D6343F22407958A835CD8F49400101000000CAC342AD697E1C407958A835CD8F48400101000000CAC342AD697E18407958A835CD0F4840'; // st_setsrid(st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)]), 4326)

    $multiPoint = $this->parser->parse($multiPointWKB);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);

    expect($multiPoint->getSRID())->toBe(4326);
})->group('WKB MultiPoint');

test('can parse 3D WKB MultiPoint', function () {
    $multiPointWKB = '0104000080040000000101000080E561A1D6343F20407958A835CD0F494000000000000024400101000080E561A1D6343F22407958A835CD8F494000000000000034400101000080CAC342AD697E1C407958A835CD8F48400000000000003E400101000080CAC342AD697E18407958A835CD0F48400000000000004440'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20), st_makepoint(7.12345, 49.12345, 30), st_makepoint(6.12345, 48.12345, 40)])

    $multiPoint = $this->parser->parse($multiPointWKB);

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
})->group('WKB MultiPoint');

test('can parse 2D WKB Simple MultiPolygon', function () {
    $multiPolygonWKB = '01060000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940'; // st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345), st_makepoint(11.12345,51.12345), st_makepoint(9.12345,48.12345), st_makepoint(10.12345,50.12345)]))])

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

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
})->group('WKB MultiPolygon');


test('can parse 2D WKB Simple MultiPolygon with SRID', function () {
    $multiPolygonWKB = '0106000020E61000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940'; // st_setsrid(st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345), st_makepoint(11.12345,51.12345), st_makepoint(9.12345,48.12345), st_makepoint(10.12345,50.12345)]))]),4326)

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

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

    expect($multiPolygon->getSRID())->toBe(4326);
})->group('WKB MultiPolygon');

test('can parse 3D WKB Simple MultiPolygon', function () {
    $multiPolygonWKB = '01060000800200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440'; // st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10), st_makepoint(9.12345,51.12345,10), st_makepoint(7.12345,48.12345,10), st_makepoint(8.12345,50.12345,10)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345,10), st_makepoint(11.12345,51.12345,10), st_makepoint(9.12345,48.12345,10), st_makepoint(10.12345,50.12345,10)]))])


    $multiPolygon = $this->parser->parse($multiPolygonWKB);

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
})->group('WKB MultiPolygon');


test('can parse 2D WKB GeometryCollection', function () {
    $geometryCollectionWKB = '0107000000030000000101000000E561A1D6343F20407958A835CD0F4940010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F494001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F4940'; // st_collect(ARRAY[st_makepoint(8.12345, 50.12345), st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]))])

    $geometryCollection = $this->parser->parse($geometryCollectionWKB);

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
})->group('WKB GeometryCollection');
