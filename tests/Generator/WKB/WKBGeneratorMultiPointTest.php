<?php

use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

beforeEach(function () {
    $this->generator = new WKBGenerator();
});

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

test('can generate 3DM WKB MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345, null, 10);
    $point2 = Point::make(9.12345, 51.12345, null, 20);
    $point3 = Point::make(7.12345, 49.12345, null, 30);
    $point4 = Point::make(6.12345, 48.12345, null, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000040040000000101000040E561A1D6343F20407958A835CD0F494000000000000024400101000040E561A1D6343F22407958A835CD8F494000000000000034400101000040CAC342AD697E1C407958A835CD8F48400000000000003E400101000040CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiPoint');

test('can generate 3DM WKB MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, null, 10);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, null, 20);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, null, 30);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, null, 40);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('0104000060E6100000040000000101000040E561A1D6343F20407958A835CD0F494000000000000024400101000040E561A1D6343F22407958A835CD8F494000000000000034400101000040CAC342AD697E1C407958A835CD8F48400000000000003E400101000040CAC342AD697E18407958A835CD0F48400000000000004440');
})->group('WKB MultiPoint');

test('can generate 4D WKB MultiPoint', function () {
    $point1 = Point::make(8.12345, 50.12345, 10, 12);
    $point2 = Point::make(9.12345, 51.12345, 20, 22);
    $point3 = Point::make(7.12345, 49.12345, 30, 32);
    $point4 = Point::make(6.12345, 48.12345, 40, 42);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('01040000C00400000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001010000C0E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001010000C0CAC342AD697E1C407958A835CD8F48400000000000003E40000000000000404001010000C0CAC342AD697E18407958A835CD0F484000000000000044400000000000004540');
})->group('WKB MultiPoint');

test('can generate 4D WKB MultiPoint with SRID', function () {
    $point1 = Point::makeGeodetic(50.12345, 8.12345, 10, 12);
    $point2 = Point::makeGeodetic(51.12345, 9.12345, 20, 22);
    $point3 = Point::makeGeodetic(49.12345, 7.12345, 30, 32);
    $point4 = Point::makeGeodetic(48.12345, 6.12345, 40, 42);

    $multiPoint = MultiPoint::make([$point1, $point2, $point3, $point4]);

    $multiPointWKB = $this->generator->generate($multiPoint);

    expect($multiPointWKB)->toBe('01040000E0E61000000400000001010000C0E561A1D6343F20407958A835CD0F49400000000000002440000000000000284001010000C0E561A1D6343F22407958A835CD8F49400000000000003440000000000000364001010000C0CAC342AD697E1C407958A835CD8F48400000000000003E40000000000000404001010000C0CAC342AD697E18407958A835CD0F484000000000000044400000000000004540');
})->group('WKB MultiPoint');
