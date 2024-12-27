<?php

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Database\PostgisFunctions\ST;
use Clickbar\Magellan\Enums\GeometryType;
use Clickbar\Magellan\Tests\Models\Location;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('locations', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->magellanPoint('location', 4326);
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('locations');
});

test('it can find points within a polygon', function () {
    // Create a polygon representing roughly the area of Germany
    $germanBorder = LineString::make([
        Point::makeGeodetic(47.3024876979, 5.98865807458),
        Point::makeGeodetic(47.2740155945, 15.0169958839),
        Point::makeGeodetic(54.9830291271, 14.9709595817),
        Point::makeGeodetic(54.9830291271, 5.98865807458),
        Point::makeGeodetic(47.3024876979, 5.98865807458),
    ]);

    $germany = Polygon::make([$germanBorder]);

    // Create test locations
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    Location::create([
        'name' => 'Paris',
        'location' => Point::makeGeodetic(48.8566, 2.3522),
    ]);

    Location::create([
        'name' => 'Munich',
        'location' => Point::makeGeodetic(48.137, 11.576),
    ]);

    // Find locations within Germany
    $locationsInGermany = Location::query()
        ->stWhere(ST::contains($germany, 'location'), true)
        ->get();

    expect($locationsInGermany)->toHaveCount(2);
    expect($locationsInGermany->pluck('name'))->toContain('Berlin', 'Munich');
    expect($locationsInGermany->pluck('name'))->not->toContain('Paris');
});

test('it can calculate distances between points', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    Location::create([
        'name' => 'Hamburg',
        'location' => Point::makeGeodetic(53.551, 9.993),
    ]);

    // Get distances from Berlin to other cities in meters
    $berlinPoint = Point::makeGeodetic(52.52, 13.405);

    $distances = Location::query()
        ->select('name')
        ->stSelect(ST::distance('location', $berlinPoint, geometryType: GeometryType::Geography), 'distance_in_meters')
        ->orderBy('distance_in_meters')
        ->get();

    expect($distances)->toHaveCount(2);
    expect($distances[0]->name)->toBe('Berlin');
    expect($distances[0]->distance_in_meters)->toBeLessThan(1); // Should be very close to 0
    expect($distances[1]->name)->toBe('Hamburg');
    expect($distances[1]->distance_in_meters)->toBeGreaterThan(200000); // Hamburg is > 200km from Berlin
    expect($distances[1]->distance_in_meters)->toBeLessThan(300000); // but < 300km
});
