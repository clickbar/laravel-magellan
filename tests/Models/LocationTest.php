<?php

use Clickbar\Magellan\Data\Geometries\Point;
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

test('it can store and retrieve a point', function () {
    $location = Location::create([
        'name' => 'Test Location',
        'location' => Point::makeGeodetic(51.087, 8.76),
    ]);

    expect($location->location)
        ->toBeInstanceOf(Point::class)
        ->and($location->location->getLatitude())->toBe(51.087)
        ->and($location->location->getLongitude())->toBe(8.76);

    // Test after fresh retrieval
    $location = $location->fresh();
    expect($location->location)
        ->toBeInstanceOf(Point::class)
        ->and($location->location->getLatitude())->toBe(51.087)
        ->and($location->location->getLongitude())->toBe(8.76);
});

test('it can query points within distance', function () {
    // Create test locations
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    Location::create([
        'name' => 'Hamburg',
        'location' => Point::makeGeodetic(53.551, 9.993),
    ]);

    Location::create([
        'name' => 'Munich',
        'location' => Point::makeGeodetic(48.137, 11.576),
    ]);

    // Find locations within 300km of Berlin
    $nearbyLocations = Location::whereRaw('ST_DWithin(location::geography, ST_SetSRID(ST_MakePoint(13.405, 52.52), 4326)::geography, 300000)')
        ->get();

    expect($nearbyLocations)->toHaveCount(2)
        ->and($nearbyLocations->pluck('name')->contains('Berlin'))->toBeTrue()
        ->and($nearbyLocations->pluck('name')->contains('Hamburg'))->toBeTrue()
        ->and($nearbyLocations->pluck('name')->contains('Munich'))->toBeFalse();
});
