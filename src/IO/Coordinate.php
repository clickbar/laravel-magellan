<?php

namespace Clickbar\Magellan\IO;

final class Coordinate
{
    public function __construct(
        public readonly float $x,
        public readonly float $y,
        public ?float $z = null,
        public ?float $m = null,
    ) {
    }

    public function setZ(float $z)
    {
        $this->z = $z;
    }

    public function setM(float $m)
    {
        $this->m = $m;
    }
}
