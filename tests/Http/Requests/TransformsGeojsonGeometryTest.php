<?php

use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Tests\Extra\GeometryFormRequest;
use Illuminate\Contracts\Container\Container;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;

function createRequest(
    ?Container $container = null,
    array $parameters = [],
    string $method = Request::METHOD_POST
): GeometryFormRequest {
    $request = GeometryFormRequest::createFromBase(HttpFoundationRequest::create('', $method, $parameters));

    $request->setRedirector($container->make(\Illuminate\Routing\Redirector::class));
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
