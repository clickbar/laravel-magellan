<?php

namespace Clickbar\Magellan\Database\MagellanExpressions;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Illuminate\Database\Query\Expression;

class GeoParam
{
    protected function __construct(
        protected readonly array|\Closure|MagellanBaseExpression|Expression|Geometry|string|null $value,
    ) {
    }

    public static function wrap(array|\Closure|MagellanBaseExpression|Expression|Geometry|string|null $value)
    {
        return new self($value);
    }

    public function getValue(): array|\Closure|MagellanBaseExpression|Expression|Geometry|string|null
    {
        return $this->value;
    }
}
