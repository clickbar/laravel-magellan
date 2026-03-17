<?php

namespace Clickbar\Magellan\Facades;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser as BaseWKBParser;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Geometry parse(mixed $input)
 *
 * @see BaseWKBParser
 */
class WKBParser extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return BaseWKBParser::class;
    }
}
