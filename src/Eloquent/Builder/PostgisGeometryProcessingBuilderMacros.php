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
         * @param $geometry
         * @param  int|null  $iterations
         * @param  bool|null  $preserveEndPoints
         * @param  string  $as
         *
         * @see https://postgis.net/docs/ST_ChaikinSmoothing.html
         */
        return function ($geometry, ?int $iterations = null, ?bool $preserveEndPoints = null, string $as = 'chaikinSmoothing') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getChaikinSmoothingExpression($this, 'select', $geometry, $iterations, $preserveEndPoints, $as));
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
         * @param $geometry
         * @param  float  $pctconvex controls the concaveness of the computed hull. A value of 1 produces the convex hull. A value of 0 produces a hull of maximum concaveness (but still a single polygon). Values between 1 and 0 produce hulls of increasing concaveness. Choosing a suitable value depends on the nature of the input data, but often values between 0.3 and 0.1 produce reasonable results.
         * @param  bool|null  $allowHoles
         * @param  string  $as
         *
         * @see https://postgis.net/docs/ST_ConcaveHull.html
         */
        return function ($geometry, float $pctconvex, ?bool $allowHoles = null, string $as = 'concaveHull') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getConvaeHullExpression($this, 'select', $geometry, $pctconvex, $allowHoles, $as));
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
         * @param $geometry
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_ConvexHull.html
         */
        return function ($geometry, string $as = 'convexHull') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getConvexHullExpression($this, 'select', $geometry, $as));
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
         * @param $geometry
         * @param  float|null  $tolerance
         * @param  DelaunayTrianglesOutput|null  $output
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_DelaunayTriangles.html
         */
        return function ($geometry, ?float $tolerance = null, ?DelaunayTrianglesOutput $output = null, string $as = 'delaunayTriangles') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getDelaunayTrianglesExpression($this, 'select', $geometry, $tolerance, $output, $as));
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
         * @param $geometry
         * @param  float  $min
         * @param  float|null  $max
         * @param  bool|null  $returnM
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_FilterByM.html
         */
        return function ($geometry, float $min, ?float $max = null, ?bool $returnM = null, string $as = 'filterByM') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getFilterByMExpression($this, 'select', $geometry, $min, $max, $returnM, $as));
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
         * @param $geometry
         * @param  int  $numberOfPoints
         * @param  int|null  $seed is used to regenerate a deterministic sequence of points, and must be greater than zero.
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_GeneratePoints.html
         */
        return function ($geometry, int $numberOfPoints, ?int $seed = null, string $as = 'generatePoints') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getGeneratePointsExpression($this, 'select', $geometry, $numberOfPoints, $seed, $as));
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
         * @param $geometry
         * @param  float|null  $tolerance
         * @param  int|null  $maxIterations
         * @param  bool|null  $failIfNotConverged
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_GeometricMedian.html
         */
        return function ($geometry, ?float $tolerance = null, ?int $maxIterations = null, ?bool $failIfNotConverged = null, string $as = 'geometricMedian') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getGeometricMedianExpression($this, 'select', $geometry, $tolerance, $maxIterations, $failIfNotConverged, $as));
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
         * @param $geometry
         * @param  bool|null  $directed if TRUE, then ST_LineMerge will not change point order within LineStrings, so lines with opposite directions will not be merged
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_LineMerge.html
         */
        return function ($geometry, ?bool $directed = null, string $as = 'lineMerge') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getLineMergeExpression($this, 'select', $geometry, $directed, $as));
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
         * @param $geometry
         * @param  int|null  $numberOfSegmentsPerQuarterCircle The bounding circle is approximated by a polygon with a default of 48 segments per quarter circle. Because the polygon is an approximation of the minimum bounding circle, some points in the input geometry may not be contained within the polygon. The approximation can be improved by increasing the number of segments. For applications where an approximation is not suitable ST_MinimumBoundingRadius may be used.
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_MinimumBoundingCircle.html
         */
        return function ($geometry, ?int $numberOfSegmentsPerQuarterCircle = null, string $as = 'minimumBoundingCircle') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getMinimumBoundingCircleExpression($this, 'select', $geometry, $numberOfSegmentsPerQuarterCircle, $as));
        };
    }

    /*
     * ST_OrientedEnvelope
     */

    public function selectOrientedEnvelope(): Closure
    {
        /**
         * Returns the minimum-area rotated rectangle enclosing a geometry. Note that more than one such rectangle may exist. May return a Point or LineString in the case of degenerate inputs.
         *
         * @param $geometry
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_OrientedEnvelope.html
         */
        return function ($geometry, string $as = 'orientedEnvelope') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getOrientedEnvelopeExpression($this, 'select', $geometry, $as));
        };
    }

    /*
     * ST_OffsetCurve
     */

    public function selectOffsetCurve(): Closure
    {
        /**
         * Return an offset line at a given distance and side from an input line. All points of the returned geometries are not further than the given distance from the input geometry. Useful for computing parallel lines about a center line.
         *
         * @param $geometry
         * @param  float  $signedDistance
         * @param  int|null  $numSegQuarterCircle
         * @param  Join|null  $styleJoin
         * @param  float|null  $styleMitreLevel
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_OffsetCurve.html
         */
        return function ($geometry, float $signedDistance, ?int $numSegQuarterCircle = null, ?Join $styleJoin = null, ?float $styleMitreLevel = null, string $as = 'offsetCurve') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getOffsetCurveExpression($this, 'select', $geometry, $as, $signedDistance, $numSegQuarterCircle, $styleJoin, $styleMitreLevel));
        };
    }

    /*
     * ST_PointOnSurface
     */

    public function selectPointOnSurface(): Closure
    {
        /**
         * Returns a POINT which is guaranteed to lie in the interior of a surface (POLYGON, MULTIPOLYGON, and CURVED POLYGON). In PostGIS this function also works on line and point geometries.
         *
         * @param $geometry
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_PointOnSurface.html
         */
        return function ($geometry, string $as = 'pointOnSurface') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getPointOnSurfaceExpression($this, 'select', $geometry, $as));
        };
    }

    /*
     * ST_ReducePrecision
     */

    public function selectReducePrecision(): Closure
    {
        /**
         * Returns a valid geometry with all points rounded to the provided grid tolerance, and features below the tolerance removed.
         *
         * @param $geometry
         * @param  float  $gridSize
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_ReducePrecision.html
         */
        return function ($geometry, float $gridSize, string $as = 'reducePrecision') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getReducePrecisionExpression($this, 'select', $geometry, $gridSize, $as));
        };
    }

    /*
     * ST_SharedPaths
     */

    public function selectSharedPaths(): Closure
    {
        /**
         * Returns a collection containing paths shared by the two input geometries. Those going in the same direction are in the first element of the collection, those going in the opposite direction are in the second element. The paths themselves are given in the direction of the first geometry.
         *
         * @param $geometryA
         * @param $geometryB
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_SharedPaths.html
         */
        return function ($geometryA, $geometryB, string $as = 'sharedPaths') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getSharedPathsExpression($this, 'select', $geometryA, $geometryB, $as));
        };
    }

    /*
     * ST_Simplify
     */

    public function selectSimplify(): Closure
    {
        /**
         * Returns a "simplified" version of the given geometry using the Douglas-Peucker algorithm. Will actually do something only with (multi)lines and (multi)polygons but you can safely call it with any kind of geometry. Since simplification occurs on a object-by-object basis you can also feed a GeometryCollection to this function.
         *
         * @param $geometry
         * @param  float  $tolerance
         * @param  bool|null  $preserveCollapsed
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_Simplify.html
         */
        return function ($geometry, float $tolerance, ?bool $preserveCollapsed = null, string $as = 'simplify') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getSimplifyExpression($this, 'select', $geometry, $tolerance, $preserveCollapsed, $as));
        };
    }

    /*
     * ST_SimplifyPolygonHull
     */

    public function selectSimplifyPolygonHull(): Closure
    {
        /**
         * Computes a simplified topology-preserving outer or inner hull of a polygonal geometry. An outer hull completely covers the input geometry. An inner hull is completely covered by the input geometry. The result is a polygonal geometry formed by a subset of the input vertices. MultiPolygons and holes are handled and produce a result with the same structure as the input.
         *
         * @param $geometry
         * @param  float  $vertexFraction The reduction in vertex count is controlled by the vertex_fraction parameter, which is a number in the range 0 to 1. Lower values produce simpler results, with smaller vertex count and less concaveness. For both outer and inner hulls a vertex fraction of 1.0 produces the orginal geometry. For outer hulls a value of 0.0 produces the convex hull (for a single polygon); for inner hulls it produces a triangle.
         * @param  bool|null  $isOuter
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_SimplifyPolygonHull.html
         */
        return function ($geometry, float $vertexFraction, ?bool $isOuter = null, string $as = 'simplifyPolygonHull') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getSimplifyPolygonHullExpression($this, 'select', $geometry, $vertexFraction, $isOuter, $as));
        };
    }

    /*
     * ST_SimplifyPreserveTopology
     */

    public function selectSimplifyPreserveTopology(): Closure
    {
        /**
         * Returns a "simplified" version of the given geometry using the Douglas-Peucker algorithm. Will avoid creating derived geometries (polygons in particular) that are invalid. Will actually do something only with (multi)lines and (multi)polygons but you can safely call it with any kind of geometry. Since simplification occurs on a object-by-object basis you can also feed a GeometryCollection to this function.
         *
         * @param $geometry
         * @param  float  $tolerance
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_SimplifyPreserveTopology.html
         */
        return function ($geometry, float $tolerance, string $as = 'simplifyPreserveTopology') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getSimplifyPreserveTopologyExpression($this, 'select', $geometry, $tolerance, $as));
        };
    }

    /*
     * ST_SimplifyVW
     */

    public function selectSimplifyVW(): Closure
    {
        /**
         * Returns a "simplified" version of the given geometry using the Visvalingam-Whyatt algorithm. Will actually do something only with (multi)lines and (multi)polygons but you can safely call it with any kind of geometry. Since simplification occurs on a object-by-object basis you can also feed a GeometryCollection to this function.
         *
         * @param $geometry
         * @param  float  $tolerance
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_SimplifyVW.html
         */
        return function ($geometry, float $tolerance, string $as = 'simplifyVW') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getSimplifyVWExpression($this, 'select', $geometry, $tolerance, $as));
        };
    }

    /*
     * ST_SetEffectiveArea
     */

    public function selectSetEffectiveArea(): Closure
    {
        /**
         * Sets the effective area for each vertex, using the Visvalingam-Whyatt algorithm. The effective area is stored as the M-value of the vertex. If the optional "theshold" parameter is used, a simplified geometry will be returned, containing only vertices with an effective area greater than or equal to the threshold value.
         *
         * @param $geometry
         * @param  float|null  $threshold
         * @param  int|null  $setArea
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_SetEffectiveArea.html
         */
        return function ($geometry, ?float $threshold = null, ?int $setArea = null, string $as = 'simplifyVW') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getSetEffecitiveAreaExpression($this, 'select', $geometry, $threshold, $setArea, $as));
        };
    }

    /*
     * ST_TriangulatePolygon
     */

    public function selectTriangulatePolygon(): Closure
    {
        /**
         * Computes the constrained Delaunay triangulation of polygons. Holes and Multipolygons are supported.
         * The "constrained Delaunay triangulation" of a polygon is a set of triangles formed from the vertices of the polygon, and covering it exactly, with the maximum total interior angle over all possible triangulations. It provides the "best quality" triangulation of the polygon.
         *
         * @param $geometry
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_TriangulatePolygon.html
         */
        return function ($geometry, string $as = 'triangulatePolygon') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getTriangulatePolygonExpression($this, 'select', $geometry, $as));
        };
    }

    /*
     * ST_VoronoiLines
     */

    public function selectVoronoiLines(): Closure
    {
        /**
         * ST_VoronoiLines computes a two-dimensional Voronoi diagram from the vertices of the supplied geometry and returns the boundaries between cells in that diagram as a MultiLineString. Returns null if input geometry is null. Returns an empty geometry collection if the input geometry contains only one vertex. Returns an empty geometry collection if the extend_to envelope has zero area.
         *
         * @param $geometry
         * @param  float|null  $tolerance The distance within which vertices will be considered equivalent. Robustness of the algorithm can be improved by supplying a nonzero tolerance distance. (default = 0.0)
         * @param  null  $extendToGeometry If a geometry is supplied as the "extend_to" parameter, the diagram will be extended to cover the envelope of the "extend_to" geometry, unless that envelope is smaller than the default envelope (default = NULL, default envelope is boundingbox of input geometry extended by about 50% in each direction).
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_VoronoiLines.html
         */
        return function ($geometry, ?float $tolerance = null, $extendToGeometry = null, string $as = 'voronoiLines') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getVoronoiLinesExpression($this, 'select', $geometry, $tolerance, $extendToGeometry, $as));
        };
    }

    /*
     * ST_VoronoiPolygons
     */

    public function selectVoronoiPolygons(): Closure
    {
        /**
         * ST_VoronoiPolygons computes a two-dimensional Voronoi diagram from the vertices of the supplied geometry. The result is a GeometryCollection of Polygons that covers an envelope larger than the extent of the input vertices. Returns null if input geometry is null. Returns an empty geometry collection if the input geometry contains only one vertex. Returns an empty geometry collection if the extend_to envelope has zero area.
         *
         * @param $geometry
         * @param  float|null  $tolerance The distance within which vertices will be considered equivalent. Robustness of the algorithm can be improved by supplying a nonzero tolerance distance. (default = 0.0)
         * @param  null  $extendToGeometry If a geometry is supplied as the "extend_to" parameter, the diagram will be extended to cover the envelope of the "extend_to" geometry, unless that envelope is smaller than the default envelope (default = NULL, default envelope is boundingbox of input geometry extended by about 50% in each direction).
         * @param  string  $as
         * @return PostgisGeometryProcessingBuilderMacros
         *
         * @see https://postgis.net/docs/ST_VoronoiPolygons.html
         */
        return function ($geometry, ?float $tolerance = null, $extendToGeometry = null, string $as = 'voronoiPolygons') {
            return $this->addSelect(PostgisGeometryProcessingExpressions::getVoronoiPolygonsExpression($this, 'select', $geometry, $tolerance, $extendToGeometry, $as));
        };
    }
}
