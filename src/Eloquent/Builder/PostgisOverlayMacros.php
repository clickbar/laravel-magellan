<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class PostgisOverlayMacros
{
    /*
     * ST_Difference
     */

    public function selectDifference(): \Closure
    {
        /**
         * Returns a geometry representing the part of geometry A that does not intersect geometry B. This is equivalent to A - ST_Intersection(A,B). If A is completely contained in B then an empty atomic geometry of appropriate type is returned.
         *
         * @param $geometryA
         * @param $geometryB
         * @param  float|null  $gridSize If the optional gridSize argument is provided, the inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
         * @param  string  $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Difference.html
         */
        return function ($geometryA, $geometryB, ?float $gridSize = null, string $as = 'difference') {
            return $this->addSelect(
                PostgisOverlayExpressions::getDifferenceExpression($this, 'select', $geometryA, $geometryB, $gridSize, $as)
            );
        };
    }
}
