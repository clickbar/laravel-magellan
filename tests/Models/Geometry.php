<?php

namespace Clickbar\Magellan\Tests\Models;

use Clickbar\Magellan\Data\Geometries\LineString;
use Clickbar\Magellan\Data\Geometries\Point;
use Clickbar\Magellan\Data\Geometries\Polygon;
use Illuminate\Database\Eloquent\Model;

class Geometry extends Model
{
    protected $fillable = [
        'name',
        'point',
        'line',
        'area',
    ];

    protected $casts = [
        'point' => Point::class,
        'line' => LineString::class,
        'area' => Polygon::class,
    ];
}
