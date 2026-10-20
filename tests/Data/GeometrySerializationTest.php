<?php

use Clickbar\Magellan\Data\Geometries\GeometryCollection;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Exception\MissingGeodeticSRIDException;
use Clickbar\Magellan\IO\Generator\WKT\WKTGenerator;

test('explicit GeoJSON arrays are independent of configured serialization', function () {
    config()->set('magellan.json_generator', WKTGenerator::class);
    config()->set('magellan.string_generator', WKTGenerator::class);

    $point = Point::makeGeodetic(51.087, 8.76, 123);

    expect($point->toGeoJsonArray())->toBe([
        'type' => 'Point',
        'coordinates' => [8.76, 51.087, 123.0],
    ]);
    expect($point->jsonSerialize())->toBe('SRID=4326;POINT Z(8.76 51.087 123)');
    expect((string) $point)->toBe('SRID=4326;POINT Z(8.76 51.087 123)');
});

test('GeoJSON arrays include nested collections and empty geometries', function () {
    $geometry = GeometryCollection::make([
        Point::makeGeodetic(51.087, 8.76),
        GeometryCollection::make([Point::makeEmpty()]),
        GeometryCollection::make([]),
    ]);

    expect($geometry->toGeoJsonArray())->toBe([
        'type' => 'GeometryCollection',
        'geometries' => [
            ['type' => 'Point', 'coordinates' => [8.76, 51.087]],
            [
                'type' => 'GeometryCollection',
                'geometries' => [['type' => 'Point', 'coordinates' => []]],
            ],
            ['type' => 'GeometryCollection', 'geometries' => []],
        ],
    ]);
});

test('GeoJSON array conversion rejects non-geodetic coordinates', function () {
    Point::make(100, 200, srid: 3857)->toGeoJsonArray();
})->throws(MissingGeodeticSRIDException::class);
