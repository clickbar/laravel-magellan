<?php

use Clickbar\Magellan\Cast\BBoxCast;
use Clickbar\Magellan\Data\Boxes\Box;
use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;

test('BBoxCast get returns Box2D with null srid when no srid configured', function () {
    $cast = new BBoxCast(Box2D::class);

    $box = $cast->get(null, 'bbox', 'BOX(1 2,3 4)', []);

    expect($box)->toBeInstanceOf(Box2D::class);
    expect($box->getSrid())->toBeNull();
});

test('BBoxCast get returns Box3D with null srid when no srid configured', function () {
    $cast = new BBoxCast(Box3D::class);

    $box = $cast->get(null, 'bbox', 'BOX3D(1 2 3,4 5 6)', []);

    expect($box)->toBeInstanceOf(Box3D::class);
    expect($box->getSrid())->toBeNull();
});

test('BBoxCast get stamps configured SRID on Box2D', function () {
    $cast = new BBoxCast(Box2D::class, 4326);

    $box = $cast->get(null, 'bbox', 'BOX(1 2,3 4)', []);

    expect($box)->toBeInstanceOf(Box2D::class);
    expect($box->getSrid())->toBe(4326);
});

test('BBoxCast get stamps configured SRID on Box3D', function () {
    $cast = new BBoxCast(Box3D::class, 4326);

    $box = $cast->get(null, 'bbox', 'BOX3D(1 2 3,4 5 6)', []);

    expect($box)->toBeInstanceOf(Box3D::class);
    expect($box->getSrid())->toBe(4326);
});

test('BBoxCast get auto-detects Box2D from BOX string and stamps SRID', function () {
    $cast = new BBoxCast(Box::class, 4326);

    $box = $cast->get(null, 'bbox', 'BOX(1 2,3 4)', []);

    expect($box)->toBeInstanceOf(Box2D::class);
    expect($box->getSrid())->toBe(4326);
});

test('BBoxCast get auto-detects Box3D from BOX3D string and stamps SRID', function () {
    $cast = new BBoxCast(Box::class, 4326);

    $box = $cast->get(null, 'bbox', 'BOX3D(1 2 3,4 5 6)', []);

    expect($box)->toBeInstanceOf(Box3D::class);
    expect($box->getSrid())->toBe(4326);
});

test('BBoxCast get returns null for null value', function () {
    $cast = new BBoxCast(Box2D::class, 4326);

    expect($cast->get(null, 'bbox', null, []))->toBeNull();
});

test('BBoxCast set returns raw SQL string, SRID has no effect', function () {
    $cast = new BBoxCast(Box2D::class, 4326);
    $box = Box2D::make(1.0, 2.0, 3.0, 4.0, 4326);

    expect($cast->set(null, 'bbox', $box, []))->toBe('BOX(1 2,3 4)');
});

test('Box2D::castUsing without srid argument creates BBoxCast with null srid', function () {
    $cast = Box2D::castUsing([]);

    $box = $cast->get(null, 'bbox', 'BOX(1 2,3 4)', []);

    expect($box->getSrid())->toBeNull();
});

test('Box2D::castUsing with srid argument creates BBoxCast that stamps SRID', function () {
    $cast = Box2D::castUsing([4326]);

    $box = $cast->get(null, 'bbox', 'BOX(1 2,3 4)', []);

    expect($box)->toBeInstanceOf(Box2D::class);
    expect($box->getSrid())->toBe(4326);
});

test('Box3D::castUsing with srid argument creates BBoxCast that stamps SRID', function () {
    $cast = Box3D::castUsing([4326]);

    $box = $cast->get(null, 'bbox', 'BOX3D(1 2 3,4 5 6)', []);

    expect($box)->toBeInstanceOf(Box3D::class);
    expect($box->getSrid())->toBe(4326);
});
