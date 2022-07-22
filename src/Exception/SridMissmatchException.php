<?php

namespace Clickbar\Magellan\Exception;

class SridMissmatchException extends \RuntimeException
{
    public function __construct(int $databaseSrid, int $geometrySrid)
    {
        parent::__construct("SRID mismatch: database has SRID {$databaseSrid}, geometry has SRID {$geometrySrid}. Consider enabling transform_on_insert in order to apply automatic transformation");
    }
}
