<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\MultiPolygon;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKBParser::class);
});

test('can parse 2D WKB Simple MultiPolygon', function () {
    $multiPolygonWKB = '01060000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940'; // st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345), st_makepoint(11.12345,51.12345), st_makepoint(9.12345,48.12345), st_makepoint(10.12345,50.12345)]))])

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
})->group('WKB MultiPolygon');

test('can parse empty 2D WKB MultiPolygon', function () {
    $multiPolygonWKB = '010600000000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiPolygon)->geometryHasSrid(null);
})->group('WKB MultiPolygon');

test('can parse 2D WKB Simple MultiPolygon with SRID', function () {
    $multiPolygonWKB = '0106000020E61000000200000001030000000100000004000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940CAC342AD697E1C407958A835CD0F4840E561A1D6343F20407958A835CD0F494001030000000100000004000000E561A1D6343F24407958A835CD0F4940E561A1D6343F26407958A835CD8F4940E561A1D6343F22407958A835CD0F4840E561A1D6343F24407958A835CD0F4940'; // st_setsrid(st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345), st_makepoint(7.12345, 48.12345), st_makepoint(8.12345, 50.12345)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345), st_makepoint(11.12345,51.12345), st_makepoint(9.12345,48.12345), st_makepoint(10.12345,50.12345)]))]),4326)

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);

    expect($multiPolygon)->geometryHasSrid(4326);
})->group('WKB MultiPolygon');

test('can parse empty 2D WKB MultiPolygon with SRID', function () {
    $multiPolygonWKB = '0106000020E610000000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiPolygon)->geometryHasSrid(4326);
})->group('WKB MultiPolygon');

test('can parse 3DZ WKB Simple MultiPolygon', function () {
    $multiPolygonWKB = '01060000800200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440'; // st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10), st_makepoint(9.12345,51.12345,10), st_makepoint(7.12345,48.12345,10), st_makepoint(8.12345,50.12345,10)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345,10), st_makepoint(11.12345,51.12345,10), st_makepoint(9.12345,48.12345,10), st_makepoint(10.12345,50.12345,10)]))])

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
})->group('WKB MultiPolygon');

test('can parse empty 3DZ WKB MultiPolygon', function () {
    $multiPolygonWKB = '010600008000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($multiPolygon)->geometryHasSrid(null);
})->group('WKB MultiPolygon');

test('can parse 3DZ WKB Simple MultiPolygon with SRID', function () {
    $multiPolygonWKB = '01060000A0E61000000200000001030000800100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000800100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440'; // st_setsrid(st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10), st_makepoint(9.12345,51.12345,10), st_makepoint(7.12345,48.12345,10), st_makepoint(8.12345,50.12345,10)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345,10), st_makepoint(11.12345,51.12345,10), st_makepoint(9.12345,48.12345,10), st_makepoint(10.12345,50.12345,10)]))]), 4326)

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasSrid(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
})->group('WKB MultiPolygon');

test('can parse empty 3DZ WKB MultiPolygon with SRID', function () {
    $multiPolygonWKB = '01060000A0E610000000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($multiPolygon)->geometryHasSrid(4326);
})->group('WKB MultiPolygon');

test('can parse 3DM WKB Simple MultiPolygon', function () {
    $multiPolygonWKB = '01060000400200000001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000400100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440'; // st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepointm(8.12345,50.12345,10), st_makepointm(9.12345,51.12345,10), st_makepointm(7.12345,48.12345,10), st_makepointm(8.12345,50.12345,10)])), st_makepolygon(st_makeline(ARRAY[st_makepointm(10.12345,50.12345,10), st_makepointm(11.12345,51.12345,10), st_makepointm(9.12345,48.12345,10), st_makepointm(10.12345,50.12345,10)]))])

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKB MultiPolygon');

test('can parse empty 3DM WKB MultiPolygon', function () {
    $multiPolygonWKB = '010600004000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiPolygon)->geometryHasSrid(null);
})->group('WKB MultiPolygon');

test('can parse 3DM WKB Simple MultiPolygon with SRID', function () {
    $multiPolygonWKB = '0106000060E61000000200000001030000400100000004000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000002440CAC342AD697E1C407958A835CD0F48400000000000002440E561A1D6343F20407958A835CD0F4940000000000000244001030000400100000004000000E561A1D6343F24407958A835CD0F49400000000000002440E561A1D6343F26407958A835CD8F49400000000000002440E561A1D6343F22407958A835CD0F48400000000000002440E561A1D6343F24407958A835CD0F49400000000000002440'; // st_setsrid(st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepointm(8.12345,50.12345,10), st_makepointm(9.12345,51.12345,10), st_makepointm(7.12345,48.12345,10), st_makepointm(8.12345,50.12345,10)])), st_makepolygon(st_makeline(ARRAY[st_makepointm(10.12345,50.12345,10), st_makepointm(11.12345,51.12345,10), st_makepointm(9.12345,48.12345,10), st_makepointm(10.12345,50.12345,10)]))]), 4326)

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiPolygon)->geometryHasSrid(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(10.0);
})->group('WKB MultiPolygon');

test('can parse empty 3DM WKB MultiPolygon with SRID', function () {
    $multiPolygonWKB = '0106000060E610000000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiPolygon)->geometryHasSrid(4326);
})->group('WKB MultiPolygon');

test('can parse 4D WKB Simple MultiPolygon', function () {
    $multiPolygonWKB = '01060000C00200000001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001030000C00100000004000000E561A1D6343F24407958A835CD0F494000000000000024400000000000002840E561A1D6343F26407958A835CD8F494000000000000024400000000000002840E561A1D6343F22407958A835CD0F484000000000000024400000000000002840E561A1D6343F24407958A835CD0F494000000000000024400000000000002840'; // st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10, 12), st_makepoint(9.12345,51.12345,10, 12), st_makepoint(7.12345,48.12345,10, 12), st_makepoint(8.12345,50.12345,10, 12)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345,10, 12), st_makepoint(11.12345,51.12345,10, 12), st_makepoint(9.12345,48.12345,10, 12), st_makepoint(10.12345,50.12345,10, 12)]))])

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKB MultiPolygon');

test('can parse empty 4D WKB MultiPolygon', function () {
    $multiPolygonWKB = '01060000C000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiPolygon)->geometryHasSrid(null);
})->group('WKB MultiPolygon');

test('can parse 4D WKB Simple MultiPolygon with SRID', function () {
    $multiPolygonWKB = '01060000E0E61000000200000001030000C00100000004000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F494000000000000024400000000000002840CAC342AD697E1C407958A835CD0F484000000000000024400000000000002840E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001030000C00100000004000000E561A1D6343F24407958A835CD0F494000000000000024400000000000002840E561A1D6343F26407958A835CD8F494000000000000024400000000000002840E561A1D6343F22407958A835CD0F484000000000000024400000000000002840E561A1D6343F24407958A835CD0F494000000000000024400000000000002840'; // st_setsrid(st_collect (ARRAY [st_makepolygon(st_makeline(ARRAY[st_makepoint(8.12345,50.12345,10, 12), st_makepoint(9.12345,51.12345,10, 12), st_makepoint(7.12345,48.12345,10, 12), st_makepoint(8.12345,50.12345,10, 12)])), st_makepolygon(st_makeline(ARRAY[st_makepoint(10.12345,50.12345,10, 12), st_makepoint(11.12345,51.12345,10, 12), st_makepoint(9.12345,48.12345,10, 12), st_makepoint(10.12345,50.12345,10, 12)]))]), 4326)

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiPolygon)->geometryHasSrid(4326);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getX())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getX())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getY())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getM())->toBe(12.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getX())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getY())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getZ())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getM())->toBe(12.0);
})->group('WKB MultiPolygon');

test('can parse empty 4D WKB MultiPolygon with SRID', function () {
    $multiPolygonWKB = '01060000E0E610000000000000';

    $multiPolygon = $this->parser->parse($multiPolygonWKB);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons())->toBeEmpty();
    expect($multiPolygon->isEmpty())->toBeTrue();
    expect($multiPolygon)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiPolygon)->geometryHasSrid(4326);
})->group('WKB MultiPolygon');
