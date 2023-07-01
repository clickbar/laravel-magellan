<?php

namespace Clickbar\Magellan\Database\MagellanExpressions;

use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Data\Boxes\Box3D;
use Clickbar\Magellan\Data\Geometries\Geometry;
use Illuminate\Database\Query\Expression;

class GeoParam
{
    protected function __construct(
        protected readonly array|\Closure|MagellanBaseExpression|Expression|Geometry|Box2D|Box3D|string|null $value,
    ) {
    }

    public static function wrap(array|\Closure|MagellanBaseExpression|Expression|Geometry|Box2D|Box3D|string|null $value)
    {
        return new self($value);
    }

    public function getValue(): array|\Closure|MagellanBaseExpression|Expression|Geometry|Box2D|Box3D|string|null
    {
        return $this->value;
    }
}
