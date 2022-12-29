<?php

use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Tests\Extra\GeometryFormRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;

test('transforms geojson geometry', function () {
    $request = GeometryFormRequest::createFrom(createRequest(request: [
        'point' => '{"type":"Point","coordinates":[8.12345,50.12345]}',
    ]));

    $request->setContainer($this->app)->validateResolved();

    expect($request->point)->toBeInstanceOf(Point::class);

    $validated = $request->validated();
    expect($validated['point'])->toBeInstanceOf(Point::class);

    $safe = $request->safe();
    expect($safe['point'])->toBeInstanceOf(Point::class);
});

function createRequest(
    array $query = [],
    string $method = Request::METHOD_GET,
    array $request = []
): Request {
    $uri = 'http://localhost/en/test';
    $server = [
        'REQUEST_URI' => $uri,
        'CONTENT_TYPE' => 'application/json',
    ];

    $request = new Request(
        $query,
        $request,
        [],
        [],
        [],
        $server,
        json_encode($request)
    );
    $request->setMethod($method);

    $route = (new Route($method, '{locale}/test', [
        'uses' => 'Controller@index',
    ]))->bind($request);
    $request->setRouteResolver(function () use ($route) {
        return $route;
    });

    return $request;
}
