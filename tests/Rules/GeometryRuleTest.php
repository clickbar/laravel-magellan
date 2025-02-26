<?php

use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Rules\GeometryGeojsonRule;

test('will validate any valid geometry', function () {
    $rule = new GeometryGeojsonRule;

    $ruleMessage = null;

    $rule->validate('attribute', [
        'type' => 'Point',
        'coordinates' => [8.12345, 50.12345, 10],
    ], function ($message) use (&$ruleMessage) {
        $ruleMessage = $message;
    });

    expect($ruleMessage)->toBeNull();
});

test('will reject any valid geometry', function () {
    $rule = new GeometryGeojsonRule;

    $ruleMessage = null;

    $rule->validate('attribute', [
        'type' => 'Invalid',
        'coordinates' => [8.12345, 50.12345, 10],
    ], function ($message) use (&$ruleMessage) {
        $ruleMessage = $message;
    });

    expect($ruleMessage)->toBeString();
});

test('will accept only allowed geometries', function () {
    $rule = new GeometryGeojsonRule([Point::class]);

    $ruleMessage = null;

    $rule->validate('attribute', [
        'type' => 'Point',
        'coordinates' => [8.12345, 50.12345, 10],
    ], function ($message) use (&$ruleMessage) {
        $ruleMessage = $message;
    });

    expect($ruleMessage)->toBeNull();
});

test('will reject disallowed geometries', function () {
    $rule = new GeometryGeojsonRule([Point::class]);

    $ruleMessage = null;

    $rule->validate('attribute', [
        'type' => 'LineString',
        'coordinates' => [[8.12345, 50.12345], [9.12345, 51.12345]],
    ], function ($message) use (&$ruleMessage) {
        $ruleMessage = $message;
    });

    expect($ruleMessage)->toBeString();
});
