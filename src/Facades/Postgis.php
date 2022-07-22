<?php

namespace Clickbar\Magellan\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Clickbar\Magellan\Magellan
 */
class Postgis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'postgis';
    }
}
