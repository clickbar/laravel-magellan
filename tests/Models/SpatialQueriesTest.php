<?php

use Clickbar\Magellan\Data\Boxes\Box;
use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;
use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\MultiPoint;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Clickbar\Magellan\Database\Expressions\Aliased;
use Clickbar\Magellan\Database\Expressions\AsGeography;
use Clickbar\Magellan\Database\PostgisFunctions\ST;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Clickbar\Magellan\Tests\Models\Location;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {

    Schema::dropIfExists('locations');
    Schema::dropIfExists('locations_3d');

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
        ->where(ST::contains($germany, 'location'), true)
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
        ->select([
            'name',
            new Aliased(ST::distance(new AsGeography('location'), new AsGeography($berlinPoint)), 'distance_in_meters'),
        ])
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
        ->select(new Aliased(ST::closestPoint('location', $hamburg), 'closest_point'))
        ->withMagellanCasts()
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
        ->select(new Aliased(ST::shortestLine('location', $hamburg), 'shortest_line'))
        ->withMagellanCasts()
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
        ->select(new Aliased(ST::isSimple('location'), 'is_simple'))
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
        ->select([
            new Aliased(ST::coordDim('location'), 'coord_dim'),
            new Aliased(ST::nDims('location'), 'n_dims'),
        ])
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
        ->select(new Aliased(ST::distance3D('location', $hamburgWithAltitude), 'distance_3d'))
        ->first();

    expect((float) $distances->distance_3d)->toBeFloat();
    expect((float) $distances->distance_3d)->toBeGreaterThan(0);
});

test('it can query expression with column and provided parameters', function () {

    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $bufferedGeometry = Location::query()
        ->select(ST::buffer(new AsGeography('location'), 10))
        ->withMagellanCasts()
        ->first()
        ->st_buffer;

    expect($bufferedGeometry)->toBeInstanceOf(Polygon::class);
});

test('it can query expression with geometry object and provided parameters', function () {

    $bufferedRawGeometry = DB::query()
        ->select(ST::buffer(new AsGeography(Point::makeGeodetic(52.52, 13.405)), 10))
        ->first()
        ->st_buffer;

    $parser = App::make(WKBParser::class);
    $bufferedGeometry = $parser->parse($bufferedRawGeometry);

    expect($bufferedGeometry)->toBeInstanceOf(Polygon::class);
});

test('it can query expression with subquery using a closure and geometry object', function () {

    $bufferedRawGeometry = DB::query()
        ->select(ST::buffer(
            geometryOrGeography: new AsGeography(Point::makeGeodetic(52.52, 13.405)),
            radius: fn ($query) => $query->selectRaw(10),
        ),
        )
        ->first()
        ->st_buffer;

    $parser = App::make(WKBParser::class);
    $bufferedGeometry = $parser->parse($bufferedRawGeometry);

    expect($bufferedGeometry)->toBeInstanceOf(Polygon::class);
});

test('it can query expression with subquery using database query builder and geometry object', function () {

    $bufferedRawGeometry = DB::query()
        ->select(ST::buffer(
            geometryOrGeography: new AsGeography(Point::makeGeodetic(52.52, 13.405)),
            radius: DB::query()->selectRaw(10),
        ),
        )
        ->first()
        ->st_buffer;

    $parser = App::make(WKBParser::class);
    $bufferedGeometry = $parser->parse($bufferedRawGeometry);

    expect($bufferedGeometry)->toBeInstanceOf(Polygon::class);
})->skip(message: 'Currently we only support closures and no Builder or Relations in parameters');

// TODO: Add test case that uses relation and eloquent query builder instead of closure or query builder

test('it can query expression with array of geometry objects', function () {

    $collectedGeometriesRaw = DB::query()
        ->select(ST::collect([
            Point::makeGeodetic(52.52, 13.405),
            Point::makeGeodetic(53.551, 9.993),
        ]))
        ->first()
        ->st_collect;

    $parser = App::make(WKBParser::class);
    $collectedGeometries = $parser->parse($collectedGeometriesRaw);

    expect($collectedGeometries)->toBeInstanceOf(MultiPoint::class);
});

test('it can query expression with array of geometry object, column and closure', function () {

    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(53.551, 9.993),
    ]);

    $collectedGeometries = Location::query()
        ->select(ST::collect([
            Point::makeGeodetic(52.52, 13.405),
            'location',
            ST::setSrid('location', 4326),
            fn ($query) => $query->selectRaw('ST_SetSrid(ST_MakePoint(8.625468993349347, 49.87125600428305), 4326)'),
        ]))
        ->withCasts(['st_collect' => MultiPoint::class])
        ->first()
        ->st_collect;

    expect($collectedGeometries)->toBeInstanceOf(MultiPoint::class);
});

test('it can query expression with nested ST functions', function () {

    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(53.551, 9.993),
    ]);

    $area = Location::query()
        ->select(ST::area(ST::buffer(new AsGeography('location'), 10)))
        ->first()
        ->st_area;

    // buffer produces no perfect circle, so tolerance is a bit higher
    expect(abs($area - 10 * 10 * M_PI))->toBeLessThanOrEqual(5);
});

test('it can query expression with nested ST functions and closures', function () {

    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(53.551, 9.993),
    ]);

    $area = DB::query()
        ->select(ST::area(ST::buffer(
            geometryOrGeography: function ($query) {
                $query
                    ->select(ST::buffer(new AsGeography('location'), 10))
                    ->from('locations');
            },
            radius: 10,
        ),
        ))
        ->first()
        ->st_area;

    // buffer produces no perfect circle, so tolerance is a bit higher
    expect(abs($area - 20 * 20 * M_PI))->toBeLessThanOrEqual(8);
});

test('it can use ST functions in model::create', function () {

    Location::create([
        'name' => 'Berlin',
        'location' => ST::transform(Point::make(473086.880, 5524383.773, srid: 25832), srid: 4326),
    ]);

    expect(Location::query()->count())->toBe(1);
});

test('it can use geography type cast with geometry object', function () {

    $thrownException = null;

    try {
        Location::query()
            ->select(ST::coordDim(new AsGeography(Point::make(1, 2))))
            ->get();
    } catch (QueryException $exception) {
        $thrownException = $exception;
    }

    expect($thrownException)->toBeInstanceOf(QueryException::class);
    expect($thrownException->getMessage())->toContain('st_coorddim(geography) does not exist');
});

test('it can use geography type cast with geometry column', function () {

    $thrownException = null;

    try {
        Location::query()
            ->select(ST::coordDim(new AsGeography('location')))
            ->get();
    } catch (QueryException $exception) {
        $thrownException = $exception;
    }

    expect($thrownException)->toBeInstanceOf(QueryException::class);
    expect($thrownException->getMessage())->toContain('st_coorddim(geography) does not exist');
});

test('it can use geography type cast with ST expression', function () {

    $thrownException = null;

    try {
        Location::query()
            ->select(ST::coordDim(new AsGeography(ST::simplify(Point::make(1, 2), 10))))
            ->get();
    } catch (QueryException $exception) {
        $thrownException = $exception;
    }

    expect($thrownException)->toBeInstanceOf(QueryException::class);
    expect($thrownException->getMessage())->toContain('st_coorddim(geography) does not exist');
});

test('it can use geography type cast with subquery', function () {

    $thrownException = null;

    try {
        Location::query()
            ->select(ST::coordDim(
                new AsGeography(
                    function ($subquery) {
                        $subquery
                            ->select(Point::make(1, 2));
                    }
                )))
            ->get();
    } catch (QueryException $exception) {
        $thrownException = $exception;
    }

    expect($thrownException)->toBeInstanceOf(QueryException::class);
    expect($thrownException->getMessage())->toContain('st_coorddim(geography) does not exist');
});

test('it can automatically cast to Box2D', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $box = Location::query()
        ->select(ST::makeBox2D(Point::make(1, 2), Point::make(3, 4)))
        ->withMagellanCasts()
        ->first()
        ->st_makebox2d;

    expect($box)->toBeInstanceOf(Box2D::class);
});

test('it can automatically cast to Box3D', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $box = Location::query()
        ->select(ST::makeBox3D(Point::make(1, 2, 3), Point::make(3, 4, 5)))
        ->withMagellanCasts()
        ->first()
        ->st_3dmakebox;

    expect($box)->toBeInstanceOf(Box3D::class);
});

test('it can cast using Box', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $box = Location::query()
        ->select(ST::makeBox2D(Point::make(1, 2), Point::make(3, 4)))
        ->withCasts(['st_makebox2d' => Box::class])
        ->first()
        ->st_makebox2d;

    expect($box)->toBeInstanceOf(Box2D::class);

    $box3d = Location::query()
        ->select(ST::makeBox3D(Point::make(1, 2, 3), Point::make(3, 4, 5)))
        ->withCasts(['st_3dmakebox' => Box::class])
        ->first()
        ->st_3dmakebox;

    expect($box3d)->toBeInstanceOf(Box3D::class);
});

test('it can cast using Box2D', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $box = Location::query()
        ->select(ST::makeBox2D(Point::make(1, 2), Point::make(3, 4)))
        ->withCasts(['st_makebox2d' => Box2D::class])
        ->first()
        ->st_makebox2d;

    expect($box)->toBeInstanceOf(Box2D::class);
});

test('it can cast using Box3D', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $box3d = Location::query()
        ->select(ST::makeBox3D(Point::make(1, 2, 3), Point::make(3, 4, 5)))
        ->withCasts(['st_3dmakebox' => Box3D::class])
        ->first()
        ->st_3dmakebox;

    expect($box3d)->toBeInstanceOf(Box3D::class);
});

test('it throws when using Box3D instead of Box2D cast', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $thrownException = null;

    try {
        Location::query()
            ->select(ST::makeBox2D(Point::make(1, 2), Point::make(3, 4)))
            ->withCasts(['st_makebox2d' => Box3D::class])
            ->first()
        ->st_makebox2d;
    } catch (InvalidArgumentException $exception) {
        $thrownException = $exception;
    }

    expect($thrownException)->toBeInstanceOf(InvalidArgumentException::class);
    expect($thrownException->getMessage())->toContain('Invalid format for Box3D. Expected BOX3D(');

});

test('it throws when using Box2D instead of Box3D cast', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $thrownException = null;

    try {
        Location::query()
            ->select(ST::makeBox3D(Point::make(1, 2, 3), Point::make(3, 4, 5)))
            ->withCasts(['st_3dmakebox' => Box2D::class])
            ->first()
            ->st_3dmakebox;
    } catch (InvalidArgumentException $exception) {
        $thrownException = $exception;
    }

    expect($thrownException)->toBeInstanceOf(InvalidArgumentException::class);
    expect($thrownException->getMessage())->toContain('Invalid format for Box2D. Expected BOX(');

});

test('Box2D can handle null values', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $box = Location::query()
        ->selectRaw('null as st_makebox2d')
        ->withCasts(['st_makebox2d' => Box2D::class])
        ->first()
        ->st_makebox2d;

    expect($box)->toBeNull();
});

test('Box3D can handle null values', function () {
    Location::create([
        'name' => 'Berlin',
        'location' => Point::makeGeodetic(52.52, 13.405),
    ]);

    $box3d = Location::query()
        ->selectRaw('null as st_3dmakebox')
        ->withCasts(['st_3dmakebox' => Box3D::class])
        ->first()
        ->st_3dmakebox;

    expect($box3d)->toBeNull();
});
