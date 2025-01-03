<?php

use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;

test('Box2D retains precision in toString', function () {
    $box = Box2D::make(1.123456789012345, 2.123456789, 3.123456789, 4.123456789);

    expect($box->toString())->toBe('BOX(1.123456789012345 2.123456789,3.123456789 4.123456789)');
});

test('Box3D retains precision in toString', function () {
    $box = Box3D::make(1.123456789012345, 2.123456789, 2.123456789, 3.123456789, 4.123456789, 6.123456789);

    expect($box->toString())->toBe('BOX3D(1.123456789012345 2.123456789 2.123456789,3.123456789 4.123456789 6.123456789)');
});
