<?php

namespace Clickbar\Magellan\Database\MagellanExpressions;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Illuminate\Database\Query\Expression;

class GeoParam
{
    public function __construct(
        protected readonly \Closure|MagellanBaseExpression|Expression|Geometry|string|null $value,
    ) {
    }

    /**
     * @return Geometry|MagellanBaseExpression|\Closure|Expression|string|null
     */
    public function getValue(): Geometry|\Closure|string|Expression|null
    {
        return $this->value;
    }
}
