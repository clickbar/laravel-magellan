<?php

namespace Clickbar\Magellan\Tests\Models;

use Clickbar\Magellan\Cast\GeometryCast;
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
        'point' => GeometryCast::class,
        'line' => GeometryCast::class,
        'area' => GeometryCast::class,
    ];
}
