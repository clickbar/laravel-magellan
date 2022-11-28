<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Enums\EndCap;
use Clickbar\Magellan\Enums\Join;
use Clickbar\Magellan\Enums\Side;
use Illuminate\Support\Str;

class PostgisGeometryProcessingExpressions
{
    public static function getBufferExpression($builder, string $bindingType, $geo, ?string $as, float $radius, ?int $numSegQuarterCircle = null, ?int $styleQuadSegs = null, ?EndCap $styleEndCap = null, ?Join $styleJoin = null, ?float $styleMitreLevel = null, ?Side $styleSide = null)
    {
        $arguments = [
            $geo,
            new BindingExpression($radius),
        ];

        $styleParts = [
            "quad_segs=$styleQuadSegs",
            "endcap=$styleEndCap?->value",
            "join=$styleJoin?->value",
            "mitre_level=$styleMitreLevel",
            "side=$styleSide?->value",
        ];

        $styleParameter = collect($styleParts)
            ->filter(fn ($part) => ! Str::endsWith($part, 'null'))
            ->join(',');

        if (! empty($styleParameter) && $numSegQuarterCircle != null) {
            // TODO: Add propper exception class
            throw new \RuntimeException('Cannot use style and numSegQuarterCircle at the same time');
        }

        if (! empty($styleParameter)) {
            $arguments[] = new BindingExpression($styleParameter);
        }

        if ($numSegQuarterCircle != null) {
            $arguments[] = new BindingExpression($numSegQuarterCircle);
        }

        return $builder->buildPostgisFunction($bindingType, null, 'ST_Buffer', $as, ...$arguments);
    }
}
