<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\MultiLineString;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(WKBParser::class);
});

test('can parse 2D WKB MultiLineString', function () {
    $multiLineStringWKB = '010500000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840'; // st_collect(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makeline(st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)))

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
})->group('WKB MultiLineString');

test('can parse empty 2D WKB MultiLineString', function () {
    $multiLineStringWKB = '010500000000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiLineString)->geometryHasSrid(null);
})->group('WKB MultiLineString');

test('can parse 2D WKB MultiLineString With SRID', function () {
    $multiLineStringWKB = '0105000020E610000002000000010200000002000000E561A1D6343F20407958A835CD0F4940E561A1D6343F22407958A835CD8F4940010200000002000000CAC342AD697E1C407958A835CD8F4840CAC342AD697E18407958A835CD0F4840'; // st_setsrid(st_collect(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makeline(st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345))), 4326)

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString)->geometryHasSrid(4326);
})->group('WKB MultiLineString');

test('can parse empty 2D WKB MultiLineString with SRID', function () {
    $multiLineStringWKB = '0105000020E610000000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiLineString)->geometryHasSrid(4326);
})->group('WKB MultiLineString');

test('can parse 3DZ WKB MultiLineString', function () {
    $multiLineStringWKB = '010500008002000000010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200008002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440'; // st_collect(st_makeline(st_makepoint(8.12345, 50.12345), st_makepoint(9.12345, 51.12345)), st_makeline(st_makepoint(7.12345, 49.12345), st_makepoint(6.12345, 48.12345)))

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
})->group('WKB MultiLineString');

test('can parse empty 3DZ WKB MultiLineString', function () {
    $multiLineStringWKB = '010500008000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DZ);
    expect($multiLineString)->geometryHasSrid(null);
})->group('WKB MultiLineString');

test('can parse 3DZ WKB MultiLineString with SRID', function () {
    $multiLineStringWKB = '01050000A0E610000002000000010200008002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200008002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440'; // st_setsrid(st_collect(st_makeline(st_makepoint(8.12345, 50.12345, 10), st_makepoint(9.12345, 51.12345, 20)), st_makeline(st_makepoint(7.12345, 49.12345, 30), st_makepoint(6.12345, 48.12345, 40))), 4326)

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
    expect($multiLineString)->geometryHasSrid(4326);
})->group('WKB MultiLineString');

test('can parse empty 3DZ WKB MultiLineString with SRID', function () {
    $multiLineStringWKB = '01050000A0E610000000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_2D);
    expect($multiLineString)->geometryHasSrid(4326);
})->group('WKB MultiLineString');

test('can parse 3DM WKB MultiLineString', function () {
    $multiLineStringWKB = '010500004002000000010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200004002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440'; // st_collect(st_makeline(st_makepointM(8.12345, 50.12345, 10), st_makepointM(9.12345, 51.12345, 20)), st_makeline(st_makepointM(7.12345, 49.12345, 30), st_makepointM(6.12345, 48.12345, 40)))

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(40.0);
})->group('WKB MultiLineString');

test('can parse empty 3DM WKB MultiLineString', function () {
    $multiLineStringWKB = '010500004000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiLineString)->geometryHasSrid(null);
})->group('WKB MultiLineString');

test('can parse 3DM WKB MultiLineString with SRID', function () {
    $multiLineStringWKB = '0105000060E610000002000000010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200004002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440'; // st_setsrid(st_collect(st_makeline(st_makepointM(8.12345, 50.12345, 10), st_makepointM(9.12345, 51.12345, 20)), st_makeline(st_makepointM(7.12345, 49.12345, 30), st_makepointM(6.12345, 48.12345, 40))), 4326)

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiLineString)->geometryHasSrid(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(40.0);
})->group('WKB MultiLineString');

test('can parse empty 3DM WKB MultiLineString with SRID', function () {
    $multiLineStringWKB = '0105000060E610000000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_3DM);
    expect($multiLineString)->geometryHasSrid(4326);
})->group('WKB MultiLineString');

test('can parse 4D WKB MultiLineString', function () {
    $multiLineStringWKB = '01050000C00200000001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001020000C002000000CAC342AD697E1C407958A835CD8F48400000000000003E400000000000004040CAC342AD697E18407958A835CD0F484000000000000044400000000000004540'; // st_collect(st_makeline(st_makepoint(8.12345, 50.12345, 10,12), st_makepoint(9.12345, 51.12345, 20,22)), st_makeline(st_makepoint(7.12345, 49.12345, 30,32), st_makepoint(6.12345, 48.12345, 40,42)))

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(22.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(32.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(42.0);
})->group('WKB MultiLineString');

test('can parse empty 4D WKB MultiLineString', function () {
    $multiLineStringWKB = '01050000C000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiLineString)->geometryHasSrid(null);
})->group('WKB MultiLineString');

test('can parse 4D WKB MultiLineString with SRID', function () {
    $multiLineStringWKB = '01050000E0E61000000200000001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001020000C002000000CAC342AD697E1C407958A835CD8F48400000000000003E400000000000004040CAC342AD697E18407958A835CD0F484000000000000044400000000000004540'; // st_setsrid(st_collect(st_makeline(st_makepoint(8.12345, 50.12345, 10,12), st_makepoint(9.12345, 51.12345, 20,22)), st_makeline(st_makepoint(7.12345, 49.12345, 30,32), st_makepoint(6.12345, 48.12345, 40,42))), 4326)

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiLineString)->geometryHasSrid(4326);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getX())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getY())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getZ())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getM())->toBe(12.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getX())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getY())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getZ())->toBe(20.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getM())->toBe(22.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getX())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getY())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getZ())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getM())->toBe(32.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getX())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getY())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getZ())->toBe(40.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getM())->toBe(42.0);
})->group('WKB MultiLineString');

test('can parse empty 4D WKB MultiLineString with SRID', function () {
    $multiLineStringWKB = '01050000E0E610000000000000';

    $multiLineString = $this->parser->parse($multiLineStringWKB);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings())->toBeEmpty();
    expect($multiLineString->isEmpty())->toBeTrue();
    expect($multiLineString)->geometryHasDimension(Dimension::DIMENSION_4D);
    expect($multiLineString)->geometryHasSrid(4326);
})->group('WKB MultiLineString');
