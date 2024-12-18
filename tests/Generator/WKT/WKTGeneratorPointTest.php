<?php

use Clickbar\Magellan\Data\Geometries\Dimension;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

beforeEach(function () {
    $this->generator = new WKTGenerator;
});

test('can generate empty 2D WKT Point', function () {
    $point = Point::makeEmpty();
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT EMPTY');
})->group('WKT Point');

test('can generate empty 3DZ WKT Point', function () {
    $point = Point::makeEmpty(dimension: Dimension::DIMENSION_3DZ);
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT Z EMPTY');
})->group('WKT Point');

test('can generate empty 3DM WKT Point', function () {
    $point = Point::makeEmpty(dimension: Dimension::DIMENSION_3DM);
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT M EMPTY');
})->group('WKT Point');

test('can generate empty 4D WKT Point', function () {
    $point = Point::makeEmpty(dimension: Dimension::DIMENSION_4D);
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT ZM EMPTY');
})->group('WKT Point');

test('can generate empty 2D WKT Point with SRID', function () {
    $point = Point::makeEmpty(srid: 4326);
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT EMPTY');
})->group('WKT Point');

test('can generate empty 3DZ WKT Point with SRID', function () {
    $point = Point::makeEmpty(srid: 4326, dimension: Dimension::DIMENSION_3DZ);
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT Z EMPTY');
})->group('WKT Point');

test('can generate empty 3DM WKT Point with SRID', function () {
    $point = Point::makeEmpty(srid: 4326, dimension: Dimension::DIMENSION_3DM);
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT M EMPTY');
})->group('WKT Point');

test('can generate empty 4D WKT Point with SRID', function () {
    $point = Point::makeEmpty(srid: 4326, dimension: Dimension::DIMENSION_4D);
    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT ZM EMPTY');
})->group('WKT Point');

test('can generate 2D WKT Point', function () {
    $point = Point::make(8.12345, 50.12345);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT(8.12345 50.12345)');
})->group('WKT Point');

test('can generate 2D WKT Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT(8.12345 50.12345)');
})->group('WKT Point');

test('can generate 3DZ WKT Point', function () {
    $point = Point::make(8.12345, 50.12345, 10);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT Z(8.12345 50.12345 10)');
})->group('WKT Point');

test('can generate 3DZ WKT Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT Z(8.12345 50.12345 10)');
})->group('WKT Point');

test('can generate 3DM WKT Point', function () {
    $point = Point::make(8.12345, 50.12345, null, 10);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT M(8.12345 50.12345 10)');
})->group('WKT Point');

test('can generate 3DM WKT Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, null, 10);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT M(8.12345 50.12345 10)');
})->group('WKT Point');

test('can generate 4D WKT Point', function () {
    $point = Point::make(8.12345, 50.12345, 10, 20);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('POINT ZM(8.12345 50.12345 10 20)');
})->group('WKT Point');

test('can generate 4D WKT Point with SRID', function () {
    $point = Point::makeGeodetic(50.12345, 8.12345, 10, 20);

    $pointWKT = $this->generator->generate($point);

    expect($pointWKT)->toBe('SRID=4326;POINT ZM(8.12345 50.12345 10 20)');
})->group('WKT Point');
