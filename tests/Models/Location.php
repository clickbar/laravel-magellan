<?php

namespace Clickbar\Magellan\Tests\Models;

use Clickbar\Magellan\Cast\GeometryCast;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'location',
    ];

    protected $casts = [
        'location' => GeometryCast::class,
    ];
}
