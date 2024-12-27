<?php

use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Rules\GeometryGeojsonRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

test('it validates valid geojson point', function () {
    $validator = Validator::make(
        ['location' => [
            'type' => 'Point',
            'coordinates' => [13.405, 52.52],
        ]],
        ['location' => [new GeometryGeojsonRule([Point::class])]]
    );

    expect($validator->passes())->toBeTrue();
});

test('it rejects invalid geojson', function () {
    $validator = Validator::make(
        ['location' => [
            'type' => 'Point',
            'coordinates' => 'not an array',
        ]],
        ['location' => [new GeometryGeojsonRule([Point::class])]]
    );

    expect($validator->fails())->toBeTrue();
});

test('it rejects wrong geometry type', function () {
    $validator = Validator::make(
        ['location' => [
            'type' => 'LineString',
            'coordinates' => [[13.405, 52.52], [13.406, 52.53]],
        ]],
        ['location' => [new GeometryGeojsonRule([Point::class])]]
    );

    expect($validator->fails())->toBeTrue();
});

test('it transforms geojson to geometry object in request', function () {
    // Create a test route that uses our form request
    Route::post('/test-geojson', function (TestGeoJsonRequest $request) {
        return response()->json([
            'location_type' => get_class($request->location),
            'latitude' => $request->location->getLatitude(),
            'longitude' => $request->location->getLongitude(),
        ]);
    });

    // Make the request
    $response = test()->postJson('/test-geojson', [
        'location' => [
            'type' => 'Point',
            'coordinates' => [13.405, 52.52], // [longitude, latitude] in GeoJSON
        ],
    ]);

    $response->assertOk()
        ->assertJson([
            'location_type' => Point::class,
            'latitude' => 52.52,
            'longitude' => 13.405,
        ]);
});

// Test Form Request class
class TestGeoJsonRequest extends FormRequest
{
    use \Clickbar\Magellan\Http\Requests\TransformsGeojsonGeometry;

    public function rules(): array
    {
        return [
            'location' => ['required', new GeometryGeojsonRule([Point::class])],
        ];
    }

    public function geometries(): array
    {
        return ['location'];
    }
}
