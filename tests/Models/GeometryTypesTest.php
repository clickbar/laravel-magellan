<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Tests\Models\Geometry;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('geometries', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->magellanPoint('point', 4326);
        $table->magellanLineString('line', 4326);
        $table->magellanPolygon('area', 4326);
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('geometries');
});

test('it can store and retrieve different geometry types', function () {
    // Create a line string for our test
    $line = LineString::make([
        Point::makeGeodetic(51.087, 8.76),
        Point::makeGeodetic(51.088, 8.77),
        Point::makeGeodetic(51.089, 8.78),
    ]);

    // Create a polygon border
    $border = LineString::make([
        Point::makeGeodetic(51.087, 8.76),
        Point::makeGeodetic(51.088, 8.77),
        Point::makeGeodetic(51.089, 8.78),
        Point::makeGeodetic(51.087, 8.76), // Close the polygon
    ]);

    $geometry = Geometry::create([
        'name' => 'Test Geometries',
        'point' => Point::makeGeodetic(51.087, 8.76),
        'line' => $line,
        'area' => Polygon::make([$border]),
    ]);

    expect($geometry->point)->toBeInstanceOf(Point::class);
    expect($geometry->line)->toBeInstanceOf(LineString::class);
    expect($geometry->area)->toBeInstanceOf(Polygon::class);

    // Test after fresh retrieval
    $geometry = $geometry->fresh();

    expect($geometry->point)->toBeInstanceOf(Point::class);
    expect($geometry->line)->toBeInstanceOf(LineString::class);
    expect($geometry->area)->toBeInstanceOf(Polygon::class);
});

test('it can handle empty geometries', function () {
    $geometry = Geometry::create([
        'name' => 'Empty Geometries',
        'point' => Point::make(NAN, NAN, null, null, 4326),
        'line' => LineString::make([], 4326),
        'area' => Polygon::make([], 4326),
    ]);

    expect($geometry->point->isEmpty())->toBeTrue();
    expect($geometry->line->isEmpty())->toBeTrue();
    expect($geometry->area->isEmpty())->toBeTrue();

    // Test after fresh retrieval
    $geometry = $geometry->fresh();

    expect($geometry->point->isEmpty())->toBeTrue();
    expect($geometry->line->isEmpty())->toBeTrue();
    expect($geometry->area->isEmpty())->toBeTrue();
});

test('it throws an exception when the wrong caster is used', function () {

    $geometry = Geometry::create([
        'name' => 'Empty Geometries',
        'point' => Point::make(NAN, NAN, null, null, 4326),
        'line' => LineString::make([], 4326),
        'area' => Polygon::make([], 4326),
    ]);

    Geometry::withCasts([
        'point' => Polygon::class,
    ])
        ->first()
        ->point;
})->throws(\InvalidArgumentException::class);
