<?php

namespace Clickbar\Magellan\Eloquent\Builder;

trait MagellanOverlayExpressions
{
    /**
     * Clips a geometry by a 2D box in a fast and tolerant but possibly invalid way.
     * Topologically invalid input geometries do not result in exceptions being thrown.
     * The output geometry is not guaranteed to be valid (in particular, self-intersections for a polygon may be introduced).
     *
     * @param $geometry
     * @param $box2D
     * @return MagellanBaseExpression
     *
     * @see https://postgis.net/docs/ST_ClipByBox2D.html
     */
    public static function getClipByBox2DExpression($geometry, $box): MagellanBaseExpression
    {
        return MagellanBaseExpression::geometry('ST_ClipByBox2D', [$geometry, $box]);
    }

    /**
     * Returns a geometry representing the part of geometry A that does not intersect geometry B. This is equivalent to A - ST_Intersection(A,B). If A is completely contained in B then an empty atomic geometry of appropriate type is returned.
     *
     * @param $geometryA
     * @param $geometryB
     * @param  float|null  $gridSize If the optional gridSize argument is provided, the inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     * @return MagellanBaseExpression
     *
     * @see https://postgis.net/docs/ST_Difference.html
     */
    public static function getDifferenceExpression($geometryA, $geometryB, ?float $gridSize = null): MagellanBaseExpression
    {
        return MagellanBaseExpression::geometry('ST_Difference', [$geometryA, $geometryB], [$gridSize]);
    }
}
