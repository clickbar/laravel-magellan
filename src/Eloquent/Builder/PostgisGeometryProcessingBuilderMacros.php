<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Enums\DelaunayTrianglesOutput;
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
        /**
         * Computes a POLYGON or MULTIPOLYGON that represents all points whose distance from a geometry/geography is less than or equal to a given distance. A negative distance shrinks the geometry rather than expanding it. A negative distance may shrink a polygon completely, in which case POLYGON EMPTY is returned. For points and lines negative distances always return empty results.
         *
         * @see https://postgis.net/docs/ST_Buffer.html
         */
        return function ($geo, float $radius, ?int $numSegQuarterCircle = null, ?int $styleQuadSegs = null, ?EndCap $styleEndCap = null, ?Join $styleJoin = null, ?float $styleMitreLevel = null, ?Side $styleSide = null, string $as = 'buffer'): Builder {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getBufferExpression($this, 'select', $geo, $as, $radius, $numSegQuarterCircle, $styleQuadSegs, $styleEndCap, $styleJoin, $styleMitreLevel, $styleSide));
        };
    }

    /*
     * ST_BuildArea
     */

    public function selectBuildArea(): Closure
    {
        /**
         * Creates an areal geometry formed by the constituent linework of the input geometry. The input can be LINESTRINGS, MULTILINESTRINGS, POLYGONS, MULTIPOLYGONS, and GeometryCollections. The result is a Polygon or MultiPolygon, depending on input. If the input linework does not form polygons, NULL is returned.
         *
         * @see https://postgis.net/docs/ST_BuildArea.html
         */
        return function ($geometry, $as = 'buildArea') {
            return $this->addSelect(
                PostgisGeometryProcessingExpressions::getBuildAreaExpression($this, 'select', $geometry, $as)
            );
        };
    }

    /*
     * ST_Centroid
     */

    public function selectCentroid(): Closure
    {
        /**
         * Computes a point which is the geometric center of mass of a geometry. For [MULTI]POINTs, the centroid is the arithmetic mean of the input coordinates. For [MULTI]LINESTRINGs, the centroid is computed using the weighted length of each line segment. For [MULTI]POLYGONs, the centroid is computed in terms of area. If an empty geometry is supplied, an empty GEOMETRYCOLLECTION is returned. If NULL is supplied, NULL is returned. If CIRCULARSTRING or COMPOUNDCURVE are supplied, they are converted to linestring with CurveToLine first, then same than for LINESTRING
         *
         * @see https://postgis.net/docs/ST_Centroid.html
         */
        return function ($geo, ?bool $useSpheroid = null, string $as = 'centroid', ?string $type = null): Builder {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getCentroidExpression($this, 'select', $geo, $useSpheroid, $as, $type));
        };
    }

    /*
     * ST_ChaikinSmoothing
     */

    public function selectChaikinSmoothing(): Closure
    {
        /**
         * Returns a "smoothed" version of the given geometry using the Chaikin algorithm. See Chaikins-Algorithm for an explanation of the process. For each iteration the number of vertex points will double. The function puts new vertex points at 1/4 of the line before and after each point and removes the original point. To reduce the number of points use one of the simplification functions on the result. The new points gets interpolated values for all included dimensions, also z and m.
         *
         * @param $geomentry
         * @param  int|null  $iterations
         * @param  bool|null  $preserveEndPoints
         * @param  string  $as
         *
         * @see https://postgis.net/docs/ST_ChaikinSmoothing.html
         */
        return function ($geomentry, ?int $iterations = null, ?bool $preserveEndPoints = null, string $as = 'chaikinSmoothing') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getChaikinSmoothingExpression($this, 'select', $geomentry, $iterations, $preserveEndPoints, $as));
        };
    }

    /*
     * ST_ConcaveHull
     */

    public function selectConcaveHull(): Closure
    {
        /**
         * A concave hull of a geometry is a possibly concave geometry that encloses the vertices of the input geometry. In the general case the concave hull is a Polygon. The polygon will not contain holes unless the optional param_allow_holes argument is specified as true. The concave hull of two or more collinear points is a two-point LineString. The concave hull of one or more identical points is a Point.
         *
         * @param $geomentry
         * @param  float  $pctconvex controls the concaveness of the computed hull. A value of 1 produces the convex hull. A value of 0 produces a hull of maximum concaveness (but still a single polygon). Values between 1 and 0 produce hulls of increasing concaveness. Choosing a suitable value depends on the nature of the input data, but often values between 0.3 and 0.1 produce reasonable results.
         * @param  bool|null  $allowHoles
         * @param  string  $as
         *
         * @see https://postgis.net/docs/ST_ConcaveHull.html
         */
        return function ($geomentry, float $pctconvex, ?bool $allowHoles = null, string $as = 'concaveHull') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getConvaeHullExpression($this, 'select', $geomentry, $pctconvex, $allowHoles, $as));
        };
    }

    /*
     * ST_ConvexHull
     */

    public function selectConvexHull(): Closure
    {
        /**
         * Computes the convex hull of a geometry. The convex hull is the smallest convex geometry that encloses all geometries in the input.
         * One can think of the convex hull as the geometry obtained by wrapping an rubber band around a set of geometries. This is different from a concave hull which is analogous to "shrink-wrapping" the geometries. A convex hull is often used to determine an affected area based on a set of point observations.
         *
         * @param $geomentry
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_ConvexHull.html
         */
        return function ($geomentry, string $as = 'convexHull') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getConvexHullExpression($this, 'select', $geomentry, $as));
        };
    }

    /*
     * ST_DelaunayTriangles
     */

    public function selectDelaunayTriangles(): Closure
    {
        /**
         * Return the Delaunay triangulation of the vertices of the input geometry. Output is a COLLECTION of polygons (for flags=0) or a MULTILINESTRING (for flags=1) or TIN (for flags=2). The tolerance, if any, is used to snap input vertices together.
         *
         * @param $geomentry
         * @param  float|null  $tolerance
         * @param  DelaunayTrianglesOutput|null  $output
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_DelaunayTriangles.html
         */
        return function ($geomentry, ?float $tolerance = null, ?DelaunayTrianglesOutput $output = null, string $as = 'delaunayTriangles') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getDelaunayTrianglesExpression($this, 'select', $geomentry, $tolerance, $output, $as));
        };
    }

    /*
     * ST_FilterByM
     */

    public function selectFilterByM(): Closure
    {
        /**
         * Filters out vertex points based on their M-value. Returns a geometry with only vertex points that have a M-value larger or equal to the min value and smaller or equal to the max value. If max-value argument is left out only min value is considered. If fourth argument is left out the m-value will not be in the resulting geometry. If resulting geometry have too few vertex points left for its geometry type an empty geometry will be returned. In a geometry collection geometries without enough points will just be left out silently.
         *
         * @param $geomentry
         * @param  float  $min
         * @param  float|null  $max
         * @param  bool|null  $returnM
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_FilterByM.html
         */
        return function ($geomentry, float $min, ?float $max = null, ?bool $returnM = null, string $as = 'filterByM') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getFilterByMExpression($this, 'select', $geomentry, $min, $max, $returnM, $as));
        };
    }

    /*
     * ST_FilterByM
     */

    public function selectGeneratePoints(): Closure
    {
        /**
         * Generates a given number of pseudo-random points which lie within the input area.
         *
         * @param $geomentry
         * @param  int  $numberOfPoints
         * @param  int|null  $seed is used to regenerate a deterministic sequence of points, and must be greater than zero.
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_GeneratePoints.html
         */
        return function ($geomentry, int $numberOfPoints, ?int $seed = null, string $as = 'generatePoints') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getGeneratePointsExpression($this, 'select', $geomentry, $numberOfPoints, $seed, $as));
        };
    }

    /*
     * ST_GeometricMedian
     */

    public function selectGeometricMedian(): Closure
    {
        /**
         * Computes the approximate geometric median of a MultiPoint geometry using the Weiszfeld algorithm. The geometric median is the point minimizing the sum of distances to the input points. It provides a centrality measure that is less sensitive to outlier points than the centroid (center of mass).
         *
         * @param $geomentry
         * @param  float|null  $tolerance
         * @param  int|null  $maxIterations
         * @param  bool|null  $failIfNotConverged
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_GeometricMedian.html
         */
        return function ($geomentry, ?float $tolerance = null, ?int $maxIterations = null, ?bool $failIfNotConverged = null, string $as = 'geometricMedian') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getGeometricMedianExpression($this, 'select', $geomentry, $tolerance, $maxIterations, $failIfNotConverged, $as));
        };
    }

    /*
     * ST_LineMerge
     */

    public function selectLineMerge(): Closure
    {
        /**
         * Returns a LineString or MultiLineString formed by joining together the line elements of a MultiLineString. Lines are joined at their endpoints at 2-way intersections. Lines are not joined across intersections of 3-way or greater degree.
         *
         * @param $geomentry
         * @param  bool|null  $directed if TRUE, then ST_LineMerge will not change point order within LineStrings, so lines with opposite directions will not be merged
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_LineMerge.html
         */
        return function ($geomentry, ?bool $directed = null, string $as = 'lineMerge') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getLineMergeExpression($this, 'select', $geomentry, $directed, $as));
        };
    }

    /*
     * ST_MinimumBoundingCircle
     */

    public function selectMinimumBoundingCircle(): Closure
    {
        /**
         * Returns the smallest circle polygon that contains a geometry.
         *
         * @param $geomentry
         * @param  int|null  $numberOfSegmentsPerQuarterCircle The bounding circle is approximated by a polygon with a default of 48 segments per quarter circle. Because the polygon is an approximation of the minimum bounding circle, some points in the input geometry may not be contained within the polygon. The approximation can be improved by increasing the number of segments. For applications where an approximation is not suitable ST_MinimumBoundingRadius may be used.
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_MinimumBoundingCircle.html
         */
        return function ($geomentry, ?int $numberOfSegmentsPerQuarterCircle = null, string $as = 'minimumBoundingCircle') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getMinimumBoundingCircleExpression($this, 'select', $geomentry, $numberOfSegmentsPerQuarterCircle, $as));
        };
    }
}
