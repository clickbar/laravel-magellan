<?php

use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Facades\GeojsonGenerator;
use Clickbar\Magellan\Facades\GeojsonParser;
use Clickbar\Magellan\Facades\WKBGenerator;
use Clickbar\Magellan\Facades\WKBParser;
use Clickbar\Magellan\Facades\WKTGenerator;
use Clickbar\Magellan\Facades\WKTParser;

describe('Parser Facades', function () {
    it('can parse WKT using WKTParser facade', function () {
        $point = WKTParser::parse('POINT(1 2)');

        expect($point)->toBeInstanceOf(Point::class);
        expect($point->getX())->toBe(1.0);
        expect($point->getY())->toBe(2.0);
    });

    it('can parse WKT with SRID using WKTParser facade', function () {
        $point = WKTParser::parse('SRID=4326;POINT(1 2)');

        expect($point)->toBeInstanceOf(Point::class);
        expect($point->getSrid())->toBe(4326);
    });

    it('can parse WKB using WKBParser facade', function () {
        // WKB for POINT(1 2)
        $wkb = '0101000000000000000000f03f0000000000000040';
        $point = WKBParser::parse($wkb);

        expect($point)->toBeInstanceOf(Point::class);
        expect($point->getX())->toBe(1.0);
        expect($point->getY())->toBe(2.0);
    });

    it('can parse GeoJSON using GeojsonParser facade', function () {
        $geojson = '{"type":"Point","coordinates":[1,2]}';
        $point = GeojsonParser::parse($geojson);

        expect($point)->toBeInstanceOf(Point::class);
        expect($point->getX())->toBe(1.0);
        expect($point->getY())->toBe(2.0);
    });

    it('can parse GeoJSON array using GeojsonParser facade', function () {
        $geojson = ['type' => 'Point', 'coordinates' => [1, 2]];
        $point = GeojsonParser::parse($geojson);

        expect($point)->toBeInstanceOf(Point::class);
        expect($point->getX())->toBe(1.0);
        expect($point->getY())->toBe(2.0);
    });
});

describe('Generator Facades', function () {
    it('can generate WKT using WKTGenerator facade', function () {
        $point = Point::make(1, 2);
        $wkt = WKTGenerator::generate($point);

        expect($wkt)->toBe('POINT(1 2)');
    });

    it('can generate WKT with SRID using WKTGenerator facade', function () {
        // makeGeodetic takes (latitude, longitude) and stores as (x=lon, y=lat)
        $point = Point::makeGeodetic(2, 1); // lat=2, lon=1
        $wkt = WKTGenerator::generate($point);

        expect($wkt)->toBe('SRID=4326;POINT(1 2)');
    });

    it('can generate WKB using WKBGenerator facade', function () {
        $point = Point::make(1, 2);
        $wkb = WKBGenerator::generate($point);

        // Case-insensitive comparison for hex string
        expect(strtolower($wkb))->toBe('0101000000000000000000f03f0000000000000040');
    });

    it('can generate GeoJSON using GeojsonGenerator facade', function () {
        // makeGeodetic takes (latitude, longitude), GeoJSON uses [lon, lat]
        $point = Point::makeGeodetic(2, 1); // lat=2, lon=1
        $geojson = GeojsonGenerator::generate($point);

        expect($geojson)->toBe([
            'type' => 'Point',
            'coordinates' => [1.0, 2.0], // [lon, lat]
        ]);
    });
});
