<?php

namespace Clickbar\Postgis\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Clickbar\Postgis\Postgis
 */
class Postgis extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'postgis';
    }
}
