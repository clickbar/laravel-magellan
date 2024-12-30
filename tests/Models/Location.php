<?php

namespace Clickbar\Magellan\Tests\Models;

use Clickbar\Magellan\Data\Geometries\Point;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'location',
    ];

    protected $casts = [
        'location' => Point::class,
    ];
}
