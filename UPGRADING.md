# Upgrading

## From 1.x to 2.x

Magellan 2.x introduce some breaking changes, which enforces some migrations.  
The following 5 Steps provide all the necessary information by examples.

### Step 1: Migrate Model

With Magellan 2.x we move from having a trait with casting logic to the [default Laravel Way of using casters](https://laravel.com/docs/master/eloquent-mutators#attribute-casting) and made all
`Geometry` classes available as casters for a better syntax.

#### In Magellan 1.x

```php
use Clickbar\Magellan\Database\Eloquent\HasPostgisColumns;
use Clickbar\Magellan\Cast\BBoxCast;

class Port extends Model
{
    use HasPostgisColumns;

    protected array $postgisColumns = [
        'location' => [
            'type' => 'geometry',
            'srid' => 4326,
        ],
    ];
    
    protected array $casts = [
        'bounding_box' => BBoxCast::class
    ];
}
```

#### In Magellan 2.x

```php
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Boxes\Box2D;

class Port extends Model
{
    protected array $casts = [
        'location' => Point::class,
        'bounding_box' => Box2D::class,
    ];
}
```

Remove the `HasPostgisColumns` trait and the
`$postgisColumns` array and add the appropriate cast based on the geometry in the database (e.g. Point::class, Geometry::class, LineString::class, ...).
Also change from the generic `BBoxCast` to the appropriate Box model class (e.g. `Box2D::class` or `Box3D::class`).

### Step 2: Migrate stSelect, stWhere, ...

With the 2.x release we've removed the custom
`st`-prefixed query builder methods. You can now use the default Laravel methods.
The `ST` functions can now be used directly in the query builder methods.
For renaming the results we added a new
`Aliased` expression class, which can be used to rename the result of a function.
But we also added a utility function on all `ST` expressions: `ST::distanceSphere(...)->as("myalias")`.

#### In Magellan 1.x:

```php
$currentShipPosition = Point::makeGeodetic(50.107471773560114, 8.679861151457937);
$portsWithDistance = Port::select(['name', 'country'])
    ->stSelect(ST::distanceSphere($currentShipPosition, 'location'), as: 'distance_to_ship')
    ->stWhere(ST::distanceSphere($currentShipPosition, 'location'), '<=', 50000)
    ->stOrderBy(ST::distanceSphere($currentShipPosition, 'location'))
    ->get();
```

##### In Magellan 2.x:

```php
$currentShipPosition = Point::makeGeodetic(50.107471773560114, 8.679861151457937);
$portsWithDistance = Port::select(['name', 'country'])
    ->addSelect(ST::distanceSphere($currentShipPosition, 'location')->as('distance_to_ship'))
    ->where(ST::distanceSphere($currentShipPosition, 'location'), '<=', 50000)
    ->orderBy(ST::distanceSphere($currentShipPosition, 'location'))
    ->withMagellanCasts() // <-- automatically adds casts for st functions returning geometry or bbox
    ->get();    
```

For almost all methods, we can simply remove the `st-` prefix and use the default Laravel methods.

`stSelect` is a special case: It acted like an
`addSelect` and also automatically took care of the casting. Therefore, it must be replaced by a call to
`addSelect` and we also need to add a call to `withMagellanCasts()`.

In fact, in this concrete example, we can now completely get rid of the `addSelect` by merging it into the other
`select`:

```php
$portsWithDistance = Port::select([
    'name', 
    'country',
    ST::distanceSphere($currentShipPosition, 'location')->as('distance_to_ship'),
])
```

### Step 3: Migrate `GeometryType` Enum

The 1.x version of Magellan always used a
`::geometry` cast under the hood for all parameters that expected any kind of geometry. In case there was a function that could also take geography, the function had an optional parameter named
`$geometryType` which could be used to tell Magellan to use `::geography` instead.
With Magellan 2.x we decided to remove this. With the most amount of
`ST_` functions of PostGIS being defined on geometry only anyway, we wanted to give back the developer a bit more control and also responsibility.

If you want to cast your input, you now need to use the `AsGeometry` and
`AsGeography` cast expressions wrapping the geometry or function you want to cast.

But let's look at this using an example.
If you want to use st_buffer to buffer a geometry using a radius in meters, you need a geography as input instead of geometry.

#### In Magellan 1.x

```php
$bufferedPorts = Port::query()
    ->stSelect(ST::buffer('location', 50, geometryType: GeometryType::geography), as: 'buffered_location'))
    ->get();
```

#### In Magellan 2.x

```php
$bufferedPorts = Port::query()
->select(ST::buffer(new AsGeography('location'), 50)->as('buffered_location'))
->withCasts(['buffered_location' => Polygon::class]) // or ->withMagellanCasts()
->get();
```

### Step 4: Migrate SRID transformation on insert/update

> **_NOTE_** Only relevant if you used magellan.eloquent.transform_to_database_projection = true in config.

#### In Magellan 1.x

```php
Port::create([
    'name' => 'Magellan Home Port',
    'country' => 'Germany',
    'location' => Point::make(473054.9891044726, 5524365.310057224, srid: 25832),
]);

// -- or --

$port = Port::find(1);
$port->location = Point::make(473054.9891044726, 5524365.310057224, srid: 25832);
$port->save();
```

#### In Magellan 2.x

With the removal of the `HasPostgisColumns` trait we also removed the auto transform feature.  
If you relied on this, you now need to transform it directly on insert or update using `ST::transform`:

```php
$point = Point::make(473054.9891044726, 5524365.310057224, srid: 25832);

Port::create([
    'name' => 'Magellan Home Port',
    'country' => 'Germany',
    'location' => ST::transform($point, 4326),
]);

// -- or --

$port = Port::find(1);

$port->query()->update([
    'location' => ST::transform(Point::make(473054.9891044726, 5524365.310057224, srid: 25832), 4326),
]);
```

### Step 5: Migrate GeometryWKBCast

> **_NOTE_** Only relevant if you used the `GeometryWKBCast` directly somewhere

#### In Magellan 1.x

```php
$hullWithArea = Port::select('country')
    ->stSelect(ST::convexHull(ST::collect('location')), 'hull')
    ->stSelect(ST::area(ST::convexHull(ST::collect('location'))))
    ->groupBy('country')
    ->withCasts(['hull' => GeometryWKBCast::class]) 
    ->first();
```

#### In Magellan 2.x

```php
$hullWithArea = Port::query()
    ->select([
        'country',
        ST::convexHull(ST::collect('location'))->as('hull'),
        ST::area(ST::convexHull(ST::collect('location'))),
    ])
    ->groupBy('country')
    ->withCasts(['hull' => Polygon::class]) 
    ->first();
```

We removed the `GeometryWKBCast` class and added new casters using the appropriate Geometry class.

### Step 6: Other Changes

You can also go ahead and remove the `magellan.eloquent` config and the `magellan.model_directories` configs.

We also renamed the `toString` function on Box classes to `toRawSql`.

The `BBoxCast` class was refactored and the Box2D / Box3D classes should be used as casts directly instead.

For a more detailed list of changes, please refer to the [CHANGELOG](CHANGELOG.md).

