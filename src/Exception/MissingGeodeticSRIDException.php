<?php

namespace Clickbar\Magellan\Exception;

class MissingGeodeticSRIDException extends \RuntimeException
{
    public function __construct(?int $geometrySrid = null, ?string $message = null)
    {
        $hint = ' Use the non-geodetic getters and setters instead or make sure to retrieve the geometry with SRID = 4326 or SRID = 0.';

        if ($geometrySrid === null) {
            $message = $message ?? "The geometry's SRID is not a geodetic SRID.".$hint;
        } else {
            $message = $message ?? "SRID {$geometrySrid} is not a geodetic SRID.".$hint;
        }

        parent::__construct($message);
    }
}
