<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Enums\EndCap;
use Clickbar\Magellan\Enums\Join;
use Clickbar\Magellan\Enums\Side;
use Closure;
use Illuminate\Contracts\Database\Query\Builder;

/**
 * @mixin Builder
 */
class PostgisGeometryProcessingBuilderMacros
{
    /*
     * ST_Buffer
     */

    public function selectBuffer(): Closure
    {
        return function ($geo, float $radius, ?int $numSegQuarterCircle = null, ?int $styleQuadSegs = null, ?EndCap $styleEndCap = null, ?Join $styleJoin = null, ?float $styleMitreLevel = null, ?Side $styleSide = null, string $as = 'buffer'): Builder {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getBufferExpression($this, 'select', $geo, $as, $radius, $numSegQuarterCircle, $styleQuadSegs, $styleEndCap, $styleJoin, $styleMitreLevel, $styleSide));
        };
    }

    public function whereBuffer(): Closure
    {
        return function ($a, $b, $operator = null, $value = null): Builder {
            return $this->where(PostgisMeasurementExpressions::getDistanceSphereExpression($this, 'where', $a, $b, null),
                $operator,
                $value,
            );
        };
    }
}
