<?php

use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;

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
