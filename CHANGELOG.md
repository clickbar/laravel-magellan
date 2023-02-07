# Changelog

All notable changes to `laravel-magellan` will be documented in this file.  

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Added
- Laravel 10 support
- PHP 8.2 support

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
