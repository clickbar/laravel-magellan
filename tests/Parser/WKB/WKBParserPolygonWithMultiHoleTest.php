<?php

use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Dimension;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;

beforeEach(function () {
    $this->parser = new WKBParser(new GeometryFactory());
});

test('can parse 2D WKB Polygon with multi hole', function () {
    $polygonWKB = '01030000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133, 50.16634), st_makepoint(8.198547,50.035091), st_makepoint(8.267211,50.050966), st_makepoint(8.27133,50.16634)]), st_makeline(ARRAY[st_makepoint(8.393554, 50.322669),st_makepoint(8.367462, 50.229637),st_makepoint(8.491058, 50.341078),st_makepoint(8.393554, 50.322669)])])

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
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
})->group('WKB Polygon');

test('can parse 2D WKB Polygon with multi hole with SRID', function () {
    $polygonWKB = '0103000020E61000000300000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494004000000EDD808C4EB8A204021020EA14A1549401570CFF3A765204025B1A4DC7D0449404E4354E1CF8820409E9ACB0D86064940EDD808C4EB8A204021020EA14A15494004000000836BEEE87FC920406D37C1374D294940A60BB1FA23BC2040CC79C6BE641D4940DBE044F46BFB20404BB1A371A82B4940836BEEE87FC920406D37C1374D294940'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133, 50.16634), st_makepoint(8.198547,50.035091), st_makepoint(8.267211,50.050966), st_makepoint(8.27133,50.16634)]), st_makeline(ARRAY[st_makepoint(8.393554, 50.322669),st_makepoint(8.367462, 50.229637),st_makepoint(8.491058, 50.341078),st_makepoint(8.393554, 50.322669)])]),4326)

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
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

    expect($polygon)->geometryHasSrid(4326);
})->group('WKB Polygon');

test('can parse 3DZ WKB Polygon with multi hole', function () {
    $polygonWKB = '01030000800300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10), st_makepoint(9.12345,51.12345,10), st_makepoint(7.12345,48.12345,10), st_makepoint(8.12345,50.12345,10)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133,50.16634,10), st_makepoint(8.198547,50.035091,10), st_makepoint(8.267211,50.050966,10), st_makepoint(8.27133,50.16634,10)]), st_makeline(ARRAY[st_makepoint(8.393554,50.322669,10),st_makepoint(8.367462,50.229637,10),st_makepoint(8.491058,50.341078,10),st_makepoint(8.393554,50.322669,10)])])

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
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
})->group('WKB Polygon');

test('can parse 3DZ WKB Polygon with multi hole with SRID', function () {
    $polygonWKB = '01030000A0E61000000300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10), st_makepoint(9.12345,51.12345,10), st_makepoint(7.12345,48.12345,10), st_makepoint(8.12345,50.12345,10)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133,50.16634,10), st_makepoint(8.198547,50.035091,10), st_makepoint(8.267211,50.050966,10), st_makepoint(8.27133,50.16634,10)]), st_makeline(ARRAY[st_makepoint(8.393554,50.322669,10),st_makepoint(8.367462,50.229637,10),st_makepoint(8.491058,50.341078,10),st_makepoint(8.393554,50.322669,10)])]), 4326)

    $polygon = $this->parser->parse($polygonWKB);

    expect($polygon)->toBeInstanceOf(Polygon::class);
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

    expect($polygon)->geometryHasSrid(4326);
})->group('WKB Polygon');

test('can parse 3DM WKB Polygon with multi hole', function () {
    $polygonWKB = '01030000400300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440'; // st_makepolygon(st_makeline(ARRAY[st_makepointm(8.12345,50.12345,10), st_makepointm(9.12345,51.12345,10), st_makepointm(7.12345,48.12345,10), st_makepointm(8.12345,50.12345,10)]), ARRAY[st_makeline(ARRAY[ st_makepointm(8.27133,50.16634,10), st_makepointm(8.198547,50.035091,10), st_makepointm(8.267211,50.050966,10), st_makepointm(8.27133,50.16634,10)]), st_makeline(ARRAY[st_makepointm(8.393554,50.322669,10),st_makepointm(8.367462,50.229637,10),st_makepointm(8.491058,50.341078,10),st_makepointm(8.393554,50.322669,10)])])

    $polygon = $this->parser->parse($polygonWKB);

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
})->group('WKB Polygon');

test('can parse 3DM WKB Polygon with multi hole with SRID', function () {
    $polygonWKB = '0103000060E61000000300000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244004000000EDD808C4EB8A204021020EA14A15494000000000000024401570CFF3A765204025B1A4DC7D04494000000000000024404E4354E1CF8820409E9ACB0D860649400000000000002440EDD808C4EB8A204021020EA14A154940000000000000244004000000836BEEE87FC920406D37C1374D2949400000000000002440A60BB1FA23BC2040CC79C6BE641D49400000000000002440DBE044F46BFB20404BB1A371A82B49400000000000002440836BEEE87FC920406D37C1374D2949400000000000002440'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepointm(8.12345,50.12345,10), st_makepointm(9.12345,51.12345,10), st_makepointm(7.12345,48.12345,10), st_makepointm(8.12345,50.12345,10)]), ARRAY[st_makeline(ARRAY[ st_makepointm(8.27133,50.16634,10), st_makepointm(8.198547,50.035091,10), st_makepointm(8.267211,50.050966,10), st_makepointm(8.27133,50.16634,10)]), st_makeline(ARRAY[st_makepointm(8.393554,50.322669,10),st_makepointm(8.367462,50.229637,10),st_makepointm(8.491058,50.341078,10),st_makepointm(8.393554,50.322669,10)])]), 4326)

    $polygon = $this->parser->parse($polygonWKB);

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

    expect($polygon)->geometryHasSrid(4326);
})->group('WKB Polygon');

test('can parse 4D WKB Polygon with multi hole', function () {
    $polygonWKB = '01030000C00300000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284004000000EDD808C4EB8A204021020EA14A154940000000000000244000000000000028401570CFF3A765204025B1A4DC7D044940000000000000244000000000000028404E4354E1CF8820409E9ACB0D8606494000000000000024400000000000002840EDD808C4EB8A204021020EA14A1549400000000000002440000000000000284004000000836BEEE87FC920406D37C1374D29494000000000000024400000000000002840A60BB1FA23BC2040CC79C6BE641D494000000000000024400000000000002840DBE044F46BFB20404BB1A371A82B494000000000000024400000000000002840836BEEE87FC920406D37C1374D29494000000000000024400000000000002840'; // st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10, 12), st_makepoint(9.12345,51.12345,10, 12), st_makepoint(7.12345,48.12345,10, 12), st_makepoint(8.12345,50.12345,10, 12)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133,50.16634,10, 12), st_makepoint(8.198547,50.035091,10, 12), st_makepoint(8.267211,50.050966,10, 12), st_makepoint(8.27133,50.16634,10, 12)]), st_makeline(ARRAY[st_makepoint(8.393554,50.322669,10, 12),st_makepoint(8.367462,50.229637,10, 12),st_makepoint(8.491058,50.341078,10, 12),st_makepoint(8.393554,50.322669,10, 12)])])

    $polygon = $this->parser->parse($polygonWKB);

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
})->group('WKB Polygon');

test('can parse 4D WKB Polygon with multi hole with SRID', function () {
    $polygonWKB = '01030000E0E61000000300000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284004000000EDD808C4EB8A204021020EA14A154940000000000000244000000000000028401570CFF3A765204025B1A4DC7D044940000000000000244000000000000028404E4354E1CF8820409E9ACB0D8606494000000000000024400000000000002840EDD808C4EB8A204021020EA14A1549400000000000002440000000000000284004000000836BEEE87FC920406D37C1374D29494000000000000024400000000000002840A60BB1FA23BC2040CC79C6BE641D494000000000000024400000000000002840DBE044F46BFB20404BB1A371A82B494000000000000024400000000000002840836BEEE87FC920406D37C1374D29494000000000000024400000000000002840'; // st_setsrid(st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10, 12), st_makepoint(9.12345,51.12345,10, 12), st_makepoint(7.12345,48.12345,10, 12), st_makepoint(8.12345,50.12345,10, 12)]), ARRAY[st_makeline(ARRAY[ st_makepoint(8.27133,50.16634,10, 12), st_makepoint(8.198547,50.035091,10, 12), st_makepoint(8.267211,50.050966,10, 12), st_makepoint(8.27133,50.16634,10, 12)]), st_makeline(ARRAY[st_makepoint(8.393554,50.322669,10, 12),st_makepoint(8.367462,50.229637,10, 12),st_makepoint(8.491058,50.341078,10, 12),st_makepoint(8.393554,50.322669,10, 12)])]), 4326)

    $polygon = $this->parser->parse($polygonWKB);

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

    expect($polygon)->geometryHasSrid(4326);
})->group('WKB Polygon');
