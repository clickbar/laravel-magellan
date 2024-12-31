# Changelog

All notable changes to `laravel-magellan` will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Breaking

- Removed support for Laravel 9.x
- Removed the `HasPostgisColumns` trait & `$postgisColumns` property.
- Removed `GeometryWKBCast`
- Removed automatic SRID transformation
- Removed st prefixed builder functions (e.g. `stSelect`, `stWhere`, ...)
- Removed `GeometryType` Enum

### Added

- Added `Castable` to all geometries to use them as casters, instead of the `GeometryWKBCast`
- Added `Aliased` Expression class as wrapper for `AS` in query selects
- Added `withMagellanCasts()` as EloquentBuilder macro
- Added `AsGeometry` and `AsGeography` database expressions

### Improved

- Validate the structure of Geometry coordinates to be an array in the `GeojsonParser` and fail if not
- Use of ST functions directly in the Laravel default builder methods
- Use of ST functions directly in Model::create array
- Renamed parameters of ST functions that can receive geometry or geography from `$geometry` to `$geometryOrGeography`
- Geometry & Box implements `Expression` and therefore can be used in `->select(...)` directly now 

### Removed

- Removed `magellan:update-postgis-columns` command
- Removed `magellan.eloquent` & `magellan.model_directories` configs

## [1.7.0](https://github.com/clickbar/laravel-magellan/tree/1.7.0) - 2024-12-27

### Added

- PostGIS ST_LineSubstring support
- PostGIS ST_LineFromEncodedPolyline support
- PostGIS ST_LineLocatePoint support
- Added `ST::asGeoJson` function to convert geometries to GeoJSON via the database

### Fixed

- Fixed `Expression` types in SRID-related functions
- Fixed missing schema prefix for generated SQL of first-level `ST` functions
- Fixed numeric SRID queries in `ST::transform` and `ST::setSRID` (thanks @BezBIS #91)
- Fixed `ST::buffer` & `ST:offsetCurve`: Correctly renamed `styleMitreLevel` to `styleMitreLimit`

## [1.6.1](https://github.com/clickbar/laravel-magellan/tree/1.6.1) - 2024-08-08

### Improved

- Only override attribute preparation functions on models instead of `performInsert` or
  `performUpdate` entirely (thanks @RomainMazB #89)

### Fixed

- Fixed geometries not being passed to model events (fixes #87) (thanks @RomainMazB #89)

## [1.6.0](https://github.com/clickbar/laravel-magellan/tree/1.6.0) - 2024-03-17

### Added

- Laravel 11.x support

### Improved

- Updated `spatie/invade` to v2, works without reflection now 🥳

### Fixed

- Fixed not updating `Point` dimension when using `setAltitude`

## [1.5.0](https://github.com/clickbar/laravel-magellan/tree/1.5.0) - 2024-01-19

### Added

- Added `as` parameter for `stFrom` function
- Aligned `stHaving` with `stWhere` to also accept string column and ST expressions, boxes and geometries as value

### Fixed

- Fixed builder macro docblocks to use FQDN for an issue within laravel-ide-helper
- Fixed `ST_PROJECT` function not being migrated to the GeometryType enum.
- Fixed model attribute equality checks for geometries, reduces unnecessary database updates (thanks @adamczykpiotr)

## [1.4.0](https://github.com/clickbar/laravel-magellan/tree/1.4.0) - 2023-07-02

### Improved

- Added Typing for Box2D and Box3D in GeoParam allowing Box objects as params in Magellan Expressions
- Added evaluation of parameters in stWhere in order to also allow magellan expressions in value and regular string, boxes or geometries in first parameter

## [1.3.0](https://github.com/clickbar/laravel-magellan/tree/1.3.0) - 2023-06-28

### Added

- Added Missing Distance Relationships Functions (Fixes #46)

### Fixed

- Fixed nullable handling in `TransformsGeojsonGeometry` trait (Fixes #37)

## [1.2.2](https://github.com/clickbar/laravel-magellan/tree/1.2.2) - 2023-03-28

### Fixed

- Fixed missing handling of expressions in GeoParam

## [1.2.1](https://github.com/clickbar/laravel-magellan/tree/1.2.1) - 2023-03-28

### Fixed

- Fixed generation of invalid FeatureCollection in case of zero database rows (thanks @djfhe)

## [1.2.0](https://github.com/clickbar/laravel-magellan/tree/1.2.0) - 2023-03-02

### Improved

- Added config for geodetic SRIDs (most of the time WGS84 is sufficient, but there are much more geodetic SRIDs than 4326)

### Fixed

- Fixed sql syntax error when trying to create magellanGeography (fixes #27, thanks @tanabi)
- Fix getValue calls on Expressions in Laravel 10

## [1.1.0](https://github.com/clickbar/laravel-magellan/tree/1.1.0) - 2023-02-07

### Added

- Laravel 10 support
- PHP 8.2 support

### Improved

- Optional geometry type param for ST_Buffer enabling easier control about metric buffering

### Fixed

- Typing bug when trying to use a Closure as Geoparam

## [1.0.2](https://github.com/clickbar/laravel-magellan/tree/1.0.2) - 2023-01-05

### Fixed

- Fix some ST functions not being migrated to the GeometryType enum.

## [1.0.1](https://github.com/clickbar/laravel-magellan/tree/1.0.1) - 2023-01-05

### Fixed

- Fix bindings being wrongly converted to SQL function statements, eg. in `query()->update()` calls.

## [1.0.0](https://github.com/clickbar/laravel-magellan/tree/1.0.0) - 2023-01-04

- Initial Release 🎉
