<?php

use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\Geometries\LineString;
use Clickbar\Magellan\Geometries\MultiLineString;
use Clickbar\Magellan\Geometries\MultiPoint;
use Clickbar\Magellan\Geometries\MultiPolygon;
use Clickbar\Magellan\Geometries\Point;
use Clickbar\Magellan\Geometries\Polygon;
use Clickbar\Magellan\IO\Parser\Geojson\GeojsonParser;
use Illuminate\Support\Facades\App;

beforeEach(function () {
    $this->parser = App::make(GeojsonParser::class);
});

test('can parse 2D Geojson Point', function () {
    $pointGeojson = '{"type":"Point","coordinates":[8.12345,50.12345]}';

    $point = $this->parser->parse($pointGeojson);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
})->group('Geojson Point');

test('can parse 3D Geojson Point', function () {
    $pointGeojson = '{"type":"Point","coordinates":[8.12345,50.12345,10]}';

    $point = $this->parser->parse($pointGeojson);

    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);
    expect($point->getAltitude())->toBe(10.0);
})->group('Geojson Point');

test('can parse 2D Geojson LineString', function () {
    $lineStringGeojson = '{"type":"LineString","coordinates":[[8.12345,50.12345],[9.12345,51.12345]]}';

    $lineString = $this->parser->parse($lineStringGeojson);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
})->group('Geojson LineString');

test('can parse 3D Geojson LineString', function () {
    $lineStringGeojson = '{"type":"LineString","coordinates":[[8.12345,50.12345,10],[9.12345,51.12345,20]]}';

    $lineString = $this->parser->parse($lineStringGeojson);

    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($lineString->getPoints()[1]->getAltitude())->toBe(20.0);
})->group('Geojson LineString');

test('can parse 2D Geojson MultiLineString', function () {
    $multiLineStringGeojson = '{"type":"MultiLineString","coordinates":[[[8.12345,50.12345],[9.12345,51.12345]],[[7.12345,49.12345],[6.12345,48.12345]]]}';

    $multiLineString = $this->parser->parse($multiLineStringGeojson);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
})->group('Geojson MultiLineString');

test('can parse 3D Geojson MultiLineString', function () {
    $multiLineStringGeojson = '{"type":"MultiLineString","coordinates":[[[8.12345,50.12345,10],[9.12345,51.12345,20]],[[7.12345,49.12345,30],[6.12345,48.12345,40]]]}';

    $multiLineString = $this->parser->parse($multiLineStringGeojson);

    expect($multiLineString)->toBeInstanceOf(MultiLineString::class);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiLineString->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(7.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(49.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[0]->getAltitude())->toBe(30.0);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(6.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(48.12345);
    expect($multiLineString->getLineStrings()[1]->getPoints()[1]->getAltitude())->toBe(40.0);
})->group('Geojson MultiLineString');

test('can parse 2D Geojson Simple Polygon', function () {
    $polygonGeojson = '{"type":"Polygon","coordinates":[[[8.12345,50.12345],[9.12345,51.12345],[7.12345,48.12345],[8.12345,50.12345]]]}';

    $polygon = $this->parser->parse($polygonGeojson);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
})->group('Geojson Polygon');

test('can parse 3D Geojson Simple Polygon', function () {
    $polygonGeojson = '{"type":"Polygon","coordinates":[[[8.12345,50.12345,10],[9.12345,51.12345,20],[7.12345,48.12345,30],[8.12345,50.12345,10]]]}';

    $polygon = $this->parser->parse($polygonGeojson);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(30.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
})->group('Geojson Polygon');

test('can parse 2D Geojson Polygon with single hole', function () {
    $polygonGeojson = '{"type":"Polygon","coordinates":[[[8.12345,50.12345],[9.12345,51.12345],[7.12345,48.12345],[8.12345,50.12345]],[[8.27133,50.16634],[8.198547,50.035091],[8.267211,50.050966],[8.27133,50.16634]]]}';

    $polygon = $this->parser->parse($polygonGeojson);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);
})->group('Geojson Polygon');

test('can parse 2D Geojson Polygon with multi hole', function () {
    $polygonGeojson = '{"type":"Polygon","coordinates":[[[8.12345,50.12345],[9.12345,51.12345],[7.12345,48.12345],[8.12345,50.12345]],[[8.27133,50.16634],[8.198547,50.035091],[8.267211,50.050966],[8.27133,50.16634]],[[8.393554,50.322669],[8.367462,50.229637],[8.491058,50.341078],[8.393554,50.322669]]]}';

    $polygon = $this->parser->parse($polygonGeojson);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLongitude())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLatitude())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLongitude())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLatitude())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLatitude())->toBe(50.322669);
})->group('Geojson Polygon');

test('can parse 3D Geojson Polygon with multi hole', function () {
    $polygonGeojson = '{"type":"Polygon","coordinates":[[[8.12345,50.12345,10],[9.12345,51.12345,10],[7.12345,48.12345,10],[8.12345,50.12345,10]],[[8.27133,50.16634,10],[8.198547,50.035091,10],[8.267211,50.050966,10],[8.27133,50.16634,10]],[[8.393554,50.322669,10],[8.367462,50.229637,10],[8.491058,50.341078,10],[8.393554,50.322669,10]]]}';

    $polygon = $this->parser->parse($polygonGeojson);

    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);

    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLongitude())->toBe(8.198547);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getLatitude())->toBe(50.035091);
    expect($polygon->getLineStrings()[1]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLongitude())->toBe(8.267211);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getLatitude())->toBe(50.050966);
    expect($polygon->getLineStrings()[1]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLongitude())->toBe(8.27133);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getLatitude())->toBe(50.16634);
    expect($polygon->getLineStrings()[1]->getPoints()[3]->getAltitude())->toBe(10.0);

    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLongitude())->toBe(8.367462);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getLatitude())->toBe(50.229637);
    expect($polygon->getLineStrings()[2]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLongitude())->toBe(8.491058);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getLatitude())->toBe(50.341078);
    expect($polygon->getLineStrings()[2]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLongitude())->toBe(8.393554);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getLatitude())->toBe(50.322669);
    expect($polygon->getLineStrings()[2]->getPoints()[3]->getAltitude())->toBe(10.0);
})->group('Geojson Polygon');

test('can parse 2D Geojson MultiPoint', function () {
    $multiPointGeojson = '{"type":"MultiPoint","coordinates":[[8.12345,50.12345],[9.12345,51.12345],[7.12345,49.12345],[6.12345,48.12345]]}';

    $multiPoint = $this->parser->parse($multiPointGeojson);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);
})->group('Geojson MultiPoint');

test('can parse 3D Geojson MultiPoint', function () {
    $multiPointGeojson = '{"type":"MultiPoint","coordinates":[[8.12345,50.12345,10],[9.12345,51.12345,20],[7.12345,49.12345,30],[6.12345,48.12345,40]]}';

    $multiPoint = $this->parser->parse($multiPointGeojson);

    expect($multiPoint)->toBeInstanceOf(MultiPoint::class);
    expect($multiPoint->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPoint->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPoint->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPoint->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPoint->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPoint->getPoints()[1]->getAltitude())->toBe(20.0);
    expect($multiPoint->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPoint->getPoints()[2]->getLatitude())->toBe(49.12345);
    expect($multiPoint->getPoints()[2]->getAltitude())->toBe(30.0);
    expect($multiPoint->getPoints()[3]->getLongitude())->toBe(6.12345);
    expect($multiPoint->getPoints()[3]->getLatitude())->toBe(48.12345);
    expect($multiPoint->getPoints()[3]->getAltitude())->toBe(40.0);
})->group('Geojson MultiPoint');

test('can parse 2D Geojson Simple MultiPolygon', function () {
    $multiPolygonGeojson = '{"type":"MultiPolygon","coordinates":[[[[8.12345,50.12345],[9.12345,51.12345],[7.12345,48.12345],[8.12345,50.12345]]],[[[10.12345,50.12345],[11.12345,51.12345],[9.12345,48.12345],[10.12345,50.12345]]]]}';

    $multiPolygon = $this->parser->parse($multiPolygonGeojson);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
})->group('Geojson MultiPolygon');

test('can parse 3D Geojson Simple MultiPolygon', function () {
    $multiPolygonGeojson = '{"type":"MultiPolygon","coordinates":[[[[8.12345,50.12345,10],[9.12345,51.12345,10],[7.12345,48.12345,10],[8.12345,50.12345,10]]],[[[10.12345,50.12345,10],[11.12345,51.12345,10],[9.12345,48.12345,10],[10.12345,50.12345,10]]]]}';

    $multiPolygon = $this->parser->parse($multiPolygonGeojson);

    expect($multiPolygon)->toBeInstanceOf(MultiPolygon::class);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[0]->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);

    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[0]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(11.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[1]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(9.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[2]->getAltitude())->toBe(10.0);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(10.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
    expect($multiPolygon->getPolygons()[1]->getLineStrings()[0]->getPoints()[3]->getAltitude())->toBe(10.0);
})->group('Geojson MultiPolygon');

test('can parse 2D Geojson GeometryCollection', function () {
    $geometryCollectionGeojson = '{"type":"GeometryCollection","geometries":[{"type":"Point","coordinates":[8.12345,50.12345]},{"type":"LineString","coordinates":[[8.12345,50.12345],[9.12345,51.12345]]},{"type":"Polygon","coordinates":[[[8.12345,50.12345],[9.12345,51.12345],[7.12345,48.12345],[8.12345,50.12345]]]}]}';

    $geometryCollection = $this->parser->parse($geometryCollectionGeojson);

    expect($geometryCollection)->toBeInstanceOf(GeometryCollection::class);

    $point = $geometryCollection->getGeometries()[0];
    expect($point)->toBeInstanceOf(Point::class);
    expect($point->getLongitude())->toBe(8.12345);
    expect($point->getLatitude())->toBe(50.12345);

    $lineString = $geometryCollection->getGeometries()[1];
    expect($lineString)->toBeInstanceOf(LineString::class);
    expect($lineString->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($lineString->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($lineString->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($lineString->getPoints()[1]->getLatitude())->toBe(51.12345);

    $polygon = $geometryCollection->getGeometries()[2];
    expect($polygon)->toBeInstanceOf(Polygon::class);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[0]->getLatitude())->toBe(50.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLongitude())->toBe(9.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[1]->getLatitude())->toBe(51.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLongitude())->toBe(7.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[2]->getLatitude())->toBe(48.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLongitude())->toBe(8.12345);
    expect($polygon->getLineStrings()[0]->getPoints()[3]->getLatitude())->toBe(50.12345);
})->group('Geojson GeometryCollection');
