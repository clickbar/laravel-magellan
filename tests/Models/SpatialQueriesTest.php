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

    // Create a separate table for 3D points
    Schema::create('locations_3d', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->magellanPointZ('location', 4326);
        $table->timestamps();
    });
});

afterEach(function () {
    Schema::dropIfExists('locations');
    Schema::dropIfExists('locations_3d');
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
    expect($distances[1]->distance_in_meters)->toBeGreaterThan(200_000); // Hamburg is > 200km from Berlin
    expect($distances[1]->distance_in_meters)->toBeLessThan(300_000); // but < 300km
});

test('it can find the closest point between geometries', function () {
    // Create test locations
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    Location::create([
        'name' => 'Hamburg',
        'location' => Point::makeGeodetic(53.551, 9.993),
    ]);

    // Find closest point from Hamburg to any other location
    $hamburg = Point::makeGeodetic(53.551, 9.993);

    $closestPoint = Location::query()
        ->where('name', 'Berlin')
        ->stSelect(ST::closestPoint('location', $hamburg), 'closest_point')
        ->first();

    expect($closestPoint->closest_point)->toBeInstanceOf(Point::class);
});

test('it can calculate the shortest line between points', function () {
    // Create test locations
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    Location::create([
        'name' => 'Hamburg',
        'location' => Point::makeGeodetic(53.551, 9.993),
    ]);

    // Find shortest line from Hamburg to Berlin
    $hamburg = Point::makeGeodetic(53.551, 9.993);

    $shortestLine = Location::query()
        ->where('name', 'Berlin')
        ->stSelect(ST::shortestLine('location', $hamburg), 'shortest_line')
        ->first();

    expect($shortestLine->shortest_line)->toBeInstanceOf(LineString::class);
});

test('it can validate geometry', function () {
    // Create a valid location
    $berlin = Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    // Check if the point is valid and simple
    $validation = Location::query()
        ->where('id', $berlin->id)
        ->stSelect(ST::isSimple('location'), 'is_simple')
        ->first();

    expect($validation->is_simple)->toBeTrue();
});

test('it can check coordinate dimensions', function () {
    // Create a 2D point
    $berlin = Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    // Check dimensions
    $dimensions = Location::query()
        ->where('id', $berlin->id)
        ->stSelect(ST::coordDim('location'), 'coord_dim')
        ->stSelect(ST::nDims('location'), 'n_dims')
        ->first();

    expect($dimensions->coord_dim)->toBe(2);
    expect($dimensions->n_dims)->toBe(2);
});

test('it can calculate 3D distances when altitude is provided', function () {
    // Create a model class for 3D locations.
    $location3d = new class extends Location
    {
        protected $table = 'locations_3d';
    };

    // Create points with altitude.
    $berlinWithAltitude = Point::make(52.52, 13.405, 100, srid: 4326); // Berlin at 100m altitude
    $hamburgWithAltitude = Point::make(53.551, 9.993, 200, srid: 4326); // Hamburg at 200m altitude

    $location3d::create([
        'name' => 'Berlin',
        'location' => $berlinWithAltitude,
    ]);

    $location3d::create([
        'name' => 'Hamburg',
        'location' => $hamburgWithAltitude,
    ]);

    // Calculate 3D distance
    $distances = $location3d::query()
        ->where('name', 'Berlin')
        ->stSelect(ST::distance3D('location', $hamburgWithAltitude), 'distance_3d')
        ->first();

    expect((float) $distances->distance_3d)->toBeFloat();
    expect((float) $distances->distance_3d)->toBeGreaterThan(0);
});
