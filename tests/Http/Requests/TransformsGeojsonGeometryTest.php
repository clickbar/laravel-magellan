<?php

use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Tests\Extra\GeometryFormRequest;
use Clickbar\Magellan\Tests\Extra\GeometryFormRequestWithCustomSrid;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

function createRequest(
    ?Container $container = null,
    array $parameters = [],
    string $method = Request::METHOD_POST,
    ?bool $useCustomSrid = false
): FormRequest {
    $request = $useCustomSrid
        ? GeometryFormRequestWithCustomSrid::createFromBase(HttpFoundationRequest::create('', $method, $parameters))
        : GeometryFormRequest::createFromBase(HttpFoundationRequest::create('', $method, $parameters));

    $request->setRedirector($container->make(Redirector::class));
    $request->setContainer($container);

    return $request;
}

test('transforms geojson geometry', function () {
    $request = createRequest($this->app, [
        'point' => '{"type":"Point","coordinates":[8.12345,50.12345]}',
        'nullable_point' => '{"type":"Point","coordinates":[8.12345,50.12345]}',
    ]);

    $request->validateResolved();

    expect($request->point)->toBeInstanceOf(Point::class);
    expect($request->nullable_point)->toBeInstanceOf(Point::class);

    $validated = $request->validated();
    expect($validated['point'])->toBeInstanceOf(Point::class);
    expect($validated['nullable_point'])->toBeInstanceOf(Point::class);

    $safe = $request->safe();
    expect($safe['point'])->toBeInstanceOf(Point::class);
    expect($safe['nullable_point'])->toBeInstanceOf(Point::class);
});

test('transforms nullable geojson geometry', function () {
    $request = createRequest($this->app, [
        'point' => '{"type":"Point","coordinates":[8.12345,50.12345]}',
        'nullable_point' => null,
    ]);

    $request->validateResolved();

    expect($request->point)->toBeInstanceOf(Point::class);
    expect($request->nullable_point)->toBeNull();

    $validated = $request->validated();
    expect($validated['point'])->toBeInstanceOf(Point::class);
    expect($validated['nullable_point'])->toBeNull();

    $safe = $request->safe();
    expect($safe['point'])->toBeInstanceOf(Point::class);
    expect($safe['nullable_point'])->toBeNull();
});

test('transforms geojson geometry with custom SRID from geometrySrids()', function () {
    $request = createRequest($this->app, [
        'point' => '{"type":"Point","coordinates":[8.12345,50.12345]}',
        'nullable_point' => '{"type":"Point","coordinates":[9.12345,51.12345]}',
    ],
        useCustomSrid: true
    );

    $request->validateResolved();

    expect($request->point)->toBeInstanceOf(Point::class);
    expect($request->point->getSrid())->toBe(25832);
    expect($request->nullable_point)->toBeInstanceOf(Point::class);
    expect($request->nullable_point->getSrid())->toBe(25832);

    $validated = $request->validated();
    expect($validated['point'])->toBeInstanceOf(Point::class);
    expect($validated['point']->getSrid())->toBe(25832);
});

test('transforms nullable geojson geometry with custom SRID from geometrySrids()', function () {
    $request = createRequest($this->app, [
        'point' => '{"type":"Point","coordinates":[8.12345,50.12345]}',
        'nullable_point' => null,
    ],
        useCustomSrid: true
    );

    $request->validateResolved();

    expect($request->point)->toBeInstanceOf(Point::class);
    expect($request->point->getSrid())->toBe(25832);
    expect($request->nullable_point)->toBeNull();

    $validated = $request->validated();
    expect($validated['point'])->toBeInstanceOf(Point::class);
    expect($validated['point']->getSrid())->toBe(25832);
    expect($validated['nullable_point'])->toBeNull();
});
