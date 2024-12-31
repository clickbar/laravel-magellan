<div align="center">
    <br>
    <br>
    <br>
    <br>
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="art/logo_dark.svg">
        <source media="(prefers-color-scheme: light)" srcset="art/logo_light.svg">
        <img width="60%" alt="The logo for laravel-magellan, which shows a small blue paper ship with the package name right beside it." src="art/logo_light.png">
    </picture>
    <br>
    <br>
    <p style="font-size: 1.75rem;">A modern PostGIS toolbox for Laravel</p>
    <br>

[![Latest Version on Packagist](https://img.shields.io/packagist/v/clickbar/laravel-magellan.svg?style=flat-square)](https://packagist.org/packages/clickbar/laravel-magellan)
[![Total Downloads](https://img.shields.io/packagist/dt/clickbar/laravel-magellan.svg?style=flat-square)](https://packagist.org/packages/clickbar/laravel-magellan)
<br>
<br>
</div>

## Introduction

Every sailor needs a nice ship to travel the seven seas ⛵️

This package will have you prepared for accessing [PostGIS](https://postgis.net) related functionality within Laravel.
It was heavily inspired by [mstaack/laravel-postgis](https://github.com/mstaack/laravel-postgis), but has evolved into
much more since. Other than some slight changes, you should be familiar with Magellan very quickly.

Magellan comes with paddles included and also provides parsers/generators for GeoJson, WKB & WKT out of the box.
Easily use all PostGIS datatypes in your migrations and avoid raw SQL to access PostGIS functions by using our Builder
functions.

Additionally `laravel-magellan` provides extensions to the Schema, Query Builder and Postgres Grammar for easy access of
PostGIS database functions like `ST_EXTENT`. It does all this without breaking compatibility to other packages,
like [tpetry/laravel-postgresql-enhanced](https://github.com/tpetry/laravel-postgresql-enhanced), which has to extend
the Grammar and Connection.

## Requirements

Magellan supports Laravel projects, which meet the following requirements:

- Laravel `^10.0` or `^11.0`
- PHP `^8.1`

## Installation

You can install the package via composer:

```bash
composer require clickbar/laravel-magellan
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="magellan-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="magellan-config"
```

You may find the contents of the published config file here:
[config/magellan.php](config/magellan.php)

## What's included

- [x] Migration Schema Blueprints
- [x] Geometry Data Classes
- [x] WKT Generator & Parser
- [x] WKB Generator & Parser
- [x] GeoJson Generator & Parser
- [x] Eloquent Model Trait
- [x] Command to automatically add the PostGIS trait to models
- [x] GeoJson Request Validation Rule
- [x] Transforms Geometry for Form Requests
- [x] Exposes nearly all PostGIS functions as typed functions that can be used in select, where, orderBy, groupBy, having, from
- [x] Geometry and BBox Cast classes
- [x] Auto Cast when using functions that return geometry or bbox
- [x] Empty Geometry Support
- [ ] Custom update Builder method for conversion safety
- [ ] Automatic PostGIS Function Doc Generator
- [ ] BBox support within $postgisColumns & trait (currently with cast only)
- [ ] Custom Geometry Factories & Models
- [ ] More tests
- ...

## Before you start

We highly recommend using the [laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper) from barryvdh to be
able to see everything included in the IDEs auto-completion.

## Creating Tables with PostGIS Columns

Laravel-magellan extends the default Schema Blueprint with all PostGIS functions. Since Laravel has introduced basic
geometry support, all methods are prefixed with `magellan`. e.g.

```php
$table->magellanPoint('location', 4326);
```

![List of all schema methods](art/magellan_schema.png)

## Preparing the Model

In order to properly integrate everything with the model you only need to add the appropriate cast (each Geometry can be used):

```php
protected $casts = [
    /** ... */
    'location' => Point::class,
];
```

## Using the geometry data classes

We've included data classes for the following common geometries:

- Point
- LineString
- Polygon
- MultiPoint
- MultiLineString
- MultiPolygon
- GeometryCollection

To create a geometry object manually, use the suited `<GeometryClass>::make` method. e.g.

```php
$point = Point::make(51.087, 8.76);
```

You will notice that there are 3 different make methods for the Point class with different parameters:

1. `make(...)`
2. `makeGeodetic(...)`
3. `makeEmpty(...)`

Let's take a closer look to the first two:

This is the default factory method that can be used to fill all possible values. This method is considered the "plain"
way. You should consider using this method when you use a non LNG/LAT projection
(e.g. something different from WGS84:SRID=4326).

```php
function make(float $x, float $y, ?float $z = null, ?float $m = null, ?int $srid = null): self
```

Most of the common web use cases use the WGS84 projection. Therefore, most of the time the terms that are used will be
latitude, longitude and altitude instead of x, y and z. To provide more comfort we have included a factory method that
accepts those terms and automatically sets the SRID to the default geodetic SRID, which can be set in the config file.

```php
function makeGeodetic(float $latitude, float $longitude, ?float $altitude = null, ?float $m = null): self
```

When using a Point class that uses a geodetic projection, you can access the latitude, longitude and altitude with
properly named getters and setters:

- `function getLatitude(): float`
- `function setLatitude(float $latitude): void`
- `function getLongitude(): float`
- `function setLongitude(float $longitude): void`
- `function getAltitude(): ?float`
- `function setAltitude(float $altitude): void`

An exception will be thrown if you try to use this functions on a Point without a srid listed in the geodetic_srids config. Use the default x, y, z, m getters and setters instead.

## Generators & Parsers

We currently provide parsers & generators for the following formats:

- EWKB
- EWKT
- GeoJson

These are also used to format our data classes to strings, convert the return value from the database (which comes in EWKB format) and output our data to the frontend as GeoJson for example.

> **Note**
> In the following we will use EWKB & WBK or EWKT & WKT interchangeably, even though we always use the extended version of each.

The config file allows you to customize which representation you would like to be used eg. when JSON serialization is done for our data classes, where GeoJson is otherwise the default.

```php
$point = Point::makeGeodetic(51.087, 8.76);

json_encode($point); // returns GeoJson
// "{"type":"Point","coordinates":[8.76,51.087]}"
```

You can always use instances of each parser / generator and parse / generate on your own behalf.  
While Generators have to be created on demand, Parsers are already instanciated in the app container as singletons and you can use them as follows:

```php
$parser = app(WKTParser::class);

$point = $parser->parse('SRID=4326;POINT (2, 2)');

$generator = new WKBGenerator();

$generator->generate($point);
// "0101000020E610000000000000000000400000000000000040"
```

In this example we obtain an instance of the `WKTParser` and convert the string to one of our data classes.
`$point` is then a valid `Point` instance, and we can use any other generator e.g. the `WKBGenerator` to output the
`$point` in hexadecimal WKB format.

## Request Validation and Transformation

When a form request contains a geometry in GeoJson format, you can use the `GeometryGeojsonRule` for validation. You can
even limit the types of allowed geometries by passing an array with the classes.

In order to properly continue working with the received geometry you can use the `TransformsGeojsonGeometry` trait to
use automatic transformation of the GeoJson to the proper geometry object. Therefore, return the keys in
the `geometries(): array` function.

> **Note**
> Currently we only support simple field transformation. Arrays & wildcard notation support will follow.

```php
class StorePortRequest extends FormRequest
{
    use TransformsGeojsonGeometry;

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'country' => ['required', 'string'],
            'location' => ['required', new GeometryGeojsonRule([Point::class])],
        ];
    }

    public function geometries(): array
    {
        return ['location'];
    }
}
```

## Interaction with the database

### Example Setup

For demo purpose we consider the following fictional scenario:
> We are a sails person with a lovely boat and a database of several ports all over the world.  
> For each port we store the name, the country and the location.

Here is the migration we use to create the ports table:

```php
Schema::create('ports', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('country');
    $table->magellanPoint('location');
    $table->timestamps();
});
```

and the model implementation:

```php
class Port extends Model
{
    use HasFactory;
    use HasPostgisColumns;

    protected $guarded = [];

    protected $casts = [
        'location' => Point::class,
    ];
}
```

### Insert/Update

Magellan geometry objects can be inserted directly as long as they are specified in the
`$casts` of the affected model.
In our case, we can insert a new Port like this:

```php
Port::create([
    'name' => 'Magellan Home Port',
    'country' => 'Germany',
    'location' => Point::makeGeodetic(49.87108851299202, 8.625026485851762),
]);
```

When you want to update a geometry you can either assign the new location to the model and call `save()` or use the
`update()` method on the query builder:

```php
$port->location = Point::makeGeodetic(55, 11);
$port->save();

// -- or --

Port::where('name', 'Magellan Home Port')
    ->update(['location' => Point::makeGeodetic(55, 11)]);
```

### Insert/Update with different SRID

When getting Geometries from external systems you might receive them in another projection than the one in the database.
Consider we want to insert or update a geometry with a different SRID. To do so, we have to use the `ST::transform(...)`
function first.

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

### Select

When selecting data from a model that uses the
`GeometryCast`, the attributes will directly be parsed to the internal Geometry data classes:

```php
$port = Port::first();
dd($port->location);
```

```bash
Clickbar\Magellan\Data\Geometries\Point {#1732
  #srid: 4326
  #dimension: Clickbar\Magellan\Data\Geometries\Dimension {#740
    +name: "DIMENSION_2D"
    +value: "2D"
  }
  #x: 8.6250264858452
  #y: 49.87108851299
  #z: null
  #m: null
}
```

There might be cases where you also want to use Box2D or Box3D as column types. Currently, we don't support boxes within the
`GeometryCast`.
Please use the `BBoxCast` instead.

### Using PostGIS functions in queries

A big part of laravel-magellan is its extensive query building feature. To provide a seamless and easy use of PostGIS functions, we have
included a wide scope of the typically ST-prefixed functions that can directly be used with Laravel's query builder.

Whenever you want to use a PostGIS function on a query builder, you can use the default build-in select, where, groupBy, ... functions. 

> **Note**  
> Using the `where` with a `MagellanExpression` that returns a boolean always requires a following true or false.
>
> That's Laravel default behaviour when using the `$query->where()`, but since PHP supports stuff like
`if($boolean)` without the explicit
`$boolean == true` condition, the true/false will easily be forgotten, resulting in a null check query instead a boolean query.

```php
->where(ST::contains('location', 'polygon'), true)
```

So you can just use the ST::functions. Each of these functions return a _MagellanExpression_.  
A
_MagellanExpression_ is a wrapper around a
`ST`-prefixed function from PostGIS. When sailing with Magellan, you should never have to write
`ST_xxx` in raw SQL for yourself. Therefore, we have included some paddles.

Most of the `ST`-prefixed functions can be accessed using the static functions on the
`ST` class. But enough talk, let's start sailing (with some examples):

**Note:** The necessary classes can be imported as follows:

```php
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Database\PostgisFunctions\ST;
use Clickbar\Magellan\Database\Expressions\Aliased;
```

Assuming we have our ships current position and want to query all ports with their distance:

```php
$currentShipPosition = Point::makeGeodetic(50.107471773560114, 8.679861151457937);
$portsWithDistance = Port::select() // use select() because we want SELECT *, distance and not only the distance
    ->addSelect(new Aliased(ST::distanceSphere($currentShipPosition, 'location'), 'distance_to_ship'))
    ->get();
```

Since we cannot sail over the whole world, let's limit the distance to max. 50.000 meters:

```php
$currentShipPosition = Point::makeGeodetic(50.107471773560114, 8.679861151457937);
$portsWithDistance = Port::select()
    ->addSelect(new Aliased(ST::distanceSphere($currentShipPosition, 'location'), 'distance_to_ship'))
    ->where(ST::distanceSphere($currentShipPosition, 'location'), '<=', 50000)
    ->get();
```

Now let us order them based on the distance:

```php
$currentShipPosition = Point::makeGeodetic(50.107471773560114, 8.679861151457937);
$portsWithDistance = Port::select()
    ->addSelect(new Aliased(ST::distanceSphere($currentShipPosition, 'location'), 'distance_to_ship'))
    ->where(ST::distanceSphere($currentShipPosition, 'location'), '<=', 50000)
    ->orderBy(ST::distanceSphere($currentShipPosition, 'location'))
    ->get();
```

But what about more complex queries?
What about the convex hull of all ports grouped by the country including the area of the hull?
No problem:

```php
$hullsWithArea = Port::query()
    ->select([
        'country',
        new Aliased(ST::convexHull(ST::collect('location')), 'hull'),
        new Aliased(ST::area(ST::convexHull(ST::collect('location'))), 'area')
    ])
    ->groupBy('country')
    ->get();
```
### Alias in select
Since we use Laravel Database Expressions for a seamless integration into the default select(...), where(..) and so on, you need to add an Alias using the Aliased Expression Class:
```php
 ->select(new Aliased(ST::distanceSphere($currentShipPosition, 'location'), 'distance_to_ship'))
//--> leads to SELECT ST_DistanceSphere(<<currentShipPosition, 'location') AS distance_to_ship
```

### Geometry or Geography
Using PostGIS, you will encounter those two types of geometries. Most of the functions in PostGIS are only defined with parameters of the type `Geometry`. But sometimes you explicitly want to add casts to your parameters. Therefore, we added two cast expressions:
- `Geometry` => `\Clickbar\Magellan\Database\Expressions\AsGeometry` 
- `Geography` => `\Clickbar\Magellan\Database\Expressions\AsGeography`

Considering we want to buffer the location of our ports by 50 meters. Looking into the PostGIS documentation we can see the following: 
> For geometry, the distance is specified in the units of the Spatial Reference System of the geometry. For geography, the distance is specified in meters.
> [https://postgis.net/docs/ST_Buffer.html](https://postgis.net/docs/ST_Buffer.html)

Therefore, we need to cast our points from the location colum to geography before handing them over to the buffer function:
```php
$bufferedPorts = Port::query()
    ->select(new Aliased(ST::buffer(new AsGeography('location'), 50), alias: 'buffered_location'))
    ->withCasts(['buffered_location' => Polygon::class])
    ->get();
```



### Autocast for BBox or geometries

In the previous section we used some PostGIS functions. In the first examples, the return types only consist out of scalar values.
But in the more complex example we received a geometry as return value.

Since "hull" will return a geometry we need a cast for it. Instead of adding each cast by hand, we can use the `withMagellanCasts()` method, which adds all the relevant casts by itself:

```php
$hullWithArea = Port::query()
    ->select([
        'country',
        new Aliased(ST::convexHull(ST::collect('location')), 'hull'),
        new Aliased(ST::area(ST::convexHull(ST::collect('location'))), 'area')
    ])
    ->groupBy('country')
    ->withMagellanCasts() /* <======= */
    ->first();

// ⬆️ instead of ⬇️

$hullWithArea = Port::query()
    ->select([
        'country',
        new Aliased(ST::convexHull(ST::collect('location')), 'hull'),
        new Aliased(ST::area(ST::convexHull(ST::collect('location'))), 'area')
    ])
    ->groupBy('country')
    ->withCasts(['hull' => Polygon::class]) /* <======= */
    ->first();

```
The `withMagellanCasts()` method adds the cast for all selects that return **geometry**, **Box2D** or **Box3D**

## Limitations

### Database Name Prepending (Cross Database Connections)

The Laravel Query Builder adds the name of database as prefix for columns, when there is a different connection between the base query and a subquery (see `prependDatabaseNameIfCrossDatabaseQuery()` function in `Builder` for more details).

Since we use Laravel Database Expressions, we don't have any access to the builder when creating the SQL string query and therefore cannot check if the connection is different to the one in the subquery.


## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please see [SECURITY](SECURITY.md) for details.

## Thanks

- [geo-io](https://github.com/geo-io)
- [mstaack/laravel-postgis](https://github.com/mstaack/laravel-postgis)
- [jmikola/geojson](https://github.com/jmikola/geojson)
- [jsor/doctrine-postgis](https://github.com/jsor/doctrine-postgis)

## Credits

- [Adrian](https://github.com/ahawlitschek)
- [saibotk](https://github.com/saibotk)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License file](LICENSE.md) for more information.
