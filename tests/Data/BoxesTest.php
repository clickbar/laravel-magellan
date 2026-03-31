<?php

use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;

test('Box2D::make without SRID has null srid', function () {
    $box = Box2D::make(1.0, 2.0, 3.0, 4.0);

    expect($box->getSrid())->toBeNull();
});

test('Box2D::make with SRID returns correct srid', function () {
    $box = Box2D::make(1.0, 2.0, 3.0, 4.0, 4326);

    expect($box->getSrid())->toBe(4326);
});

test('Box2D::fromString without SRID has null srid', function () {
    $box = Box2D::fromString('BOX(1 2,3 4)');

    expect($box->getSrid())->toBeNull();
});

test('Box2D::fromString with SRID returns correct srid', function () {
    $box = Box2D::fromString('BOX(1 2,3 4)', 4326);

    expect($box->getSrid())->toBe(4326);
});

test('Box2D SRID does not affect toRawSql output', function () {
    $box = Box2D::make(1.0, 2.0, 3.0, 4.0, 4326);

    expect($box->toRawSql())->toBe('BOX(1 2,3 4)');
});

test('Box3D::make without SRID has null srid', function () {
    $box = Box3D::make(1.0, 2.0, 3.0, 4.0, 5.0, 6.0);

    expect($box->getSrid())->toBeNull();
});

test('Box3D::make with SRID returns correct srid', function () {
    $box = Box3D::make(1.0, 2.0, 3.0, 4.0, 5.0, 6.0, 4326);

    expect($box->getSrid())->toBe(4326);
});

test('Box3D::fromString without SRID has null srid', function () {
    $box = Box3D::fromString('BOX3D(1 2 3,4 5 6)');

    expect($box->getSrid())->toBeNull();
});

test('Box3D::fromString with SRID returns correct srid', function () {
    $box = Box3D::fromString('BOX3D(1 2 3,4 5 6)', 4326);

    expect($box->getSrid())->toBe(4326);
});

test('Box3D SRID does not affect toRawSql output', function () {
    $box = Box3D::make(1.0, 2.0, 3.0, 4.0, 5.0, 6.0, 4326);

    expect($box->toRawSql())->toBe('BOX3D(1 2 3,4 5 6)');
});

test('Box2D correctly implements Stringable', function () {
    $box = Box2D::make(1.123456789012345, 2.123456789, 3.123456789, 4.123456789);

    expect((string) $box)->toBe('BOX(1.123456789012345 2.123456789,3.123456789 4.123456789)');
});

test('Box2D retains precision in toRawSql', function () {
    $box = Box2D::make(1.123456789012345, 2.123456789, 3.123456789, 4.123456789);

    expect($box->toRawSql())->toBe('BOX(1.123456789012345 2.123456789,3.123456789 4.123456789)');
});

test('Box3D correctly implements Stringable', function () {
    $box = Box3D::make(1.123456789012345, 2.123456789, 2.123456789, 3.123456789, 4.123456789, 6.123456789);

    expect((string) $box)->toBe('BOX3D(1.123456789012345 2.123456789 2.123456789,3.123456789 4.123456789 6.123456789)');
});

test('Box3D retains precision in toRawSql', function () {
    $box = Box3D::make(1.123456789012345, 2.123456789, 2.123456789, 3.123456789, 4.123456789, 6.123456789);

    expect($box->toRawSql())->toBe('BOX3D(1.123456789012345 2.123456789 2.123456789,3.123456789 4.123456789 6.123456789)');
});
