<?php

use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\MultiLineString;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});


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


test('can generate 3DM WKB MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 49.12345, null, 30);
    $point4 = Point::make(6.12345, 48.12345, null, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('010500004002000000010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200004002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiLineString');

test('can generate 3DM WKB MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, null, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, null, 40);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('0105000060E610000002000000010200004002000000E561A1D6343F20407958A835CD0F49400000000000002440E561A1D6343F22407958A835CD8F49400000000000003440010200004002000000CAC342AD697E1C407958A835CD8F48400000000000003E40CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiLineString');


test('can generate 4D WKB MultiLineString', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 49.12345, 30, 32);
    $point4 = Point::make(6.12345, 48.12345, 40, 42);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('01050000C00200000001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001020000C002000000CAC342AD697E1C407958A835CD8F48400000000000003E400000000000004040CAC342AD697E18407958A835CD0F484000000000000044400000000000004540');
})->group('WKB MultiLineString');

test('can generate 4D WKB MultiLineString with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30, 32);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40, 42);

    $lineString1 = LineString::make([$point1, $point2]);
    $lineString2 = LineString::make([$point3, $point4]);

    $multiLineString = MultiLineString::make([$lineString1, $lineString2]);

    $multiLineStringWKB = $this->generator->generate($multiLineString);

    expect($multiLineStringWKB)->toBe('01050000E0E61000000200000001020000C002000000E561A1D6343F20407958A835CD0F494000000000000024400000000000002840E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001020000C002000000CAC342AD697E1C407958A835CD8F48400000000000003E400000000000004040CAC342AD697E18407958A835CD0F484000000000000044400000000000004540');
})->group('WKB MultiLineString');
