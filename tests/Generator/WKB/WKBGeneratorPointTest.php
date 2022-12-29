<?php

use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;

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
