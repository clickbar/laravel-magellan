<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression;
use Clickbar\Magellan\Enums\DelaunayTrianglesOutput;
use Clickbar\Magellan\Enums\EndCap;
use Clickbar\Magellan\Enums\GeometryType;
use Clickbar\Magellan\Enums\Join;
use Clickbar\Magellan\Enums\Side;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Str;
use RuntimeException;

trait MagellanGeometryProcessingFunctions
{
    /**
     * Computes a POLYGON or MULTIPOLYGON that represents all points whose distance from a geometry/geography is less than or equal to a given distance.
     * A negative distance shrinks the geometry rather than expanding it. A negative distance may shrink a polygon completely, in which case POLYGON EMPTY is returned.
     * For points and lines negative distances always return empty results.
     *
     *
     * @see https://postgis.net/docs/ST_Buffer.html
     */
    public static function buffer($geometry, float|Expression|\Closure $radius, ?int $numSegQuarterCircle = null, ?int $styleQuadSegs = null, ?EndCap $styleEndCap = null, ?Join $styleJoin = null, float|Expression|\Closure|null $styleMitreLevel = null, ?Side $styleSide = null, ?GeometryType $geometryType = null): MagellanGeometryExpression
    {
        $arguments = [
            GeoParam::wrap($geometry),
            $radius,
        ];

        $styleParts = [
            "quad_segs=$styleQuadSegs",
            "endcap=$styleEndCap?->value",
            "join=$styleJoin?->value",
            "mitre_level=$styleMitreLevel",
            "side=$styleSide?->value",
        ];

        $styleParameter = collect($styleParts)
            ->filter(fn ($part) => ! Str::endsWith($part, '='))
            ->join(' ');

        if (! empty($styleParameter) && $numSegQuarterCircle !== null) {
            // TODO: Add propper exception class
            throw new RuntimeException('Cannot use style and numSegQuarterCircle at the same time');
        }

        if (! empty($styleParameter)) {
            $arguments[] = $styleParameter;
        }

        if ($numSegQuarterCircle !== null) {
            $arguments[] = $numSegQuarterCircle;
        }

        return MagellanBaseExpression::geometry('ST_Buffer', $arguments, $geometryType);
    }

    /**
     * Creates an areal geometry formed by the constituent linework of the input geometry. The input can be LINESTRINGS, MULTILINESTRINGS, POLYGONS, MULTIPOLYGONS, and GeometryCollections. The result is a Polygon or MultiPolygon, depending on input. If the input linework does not form polygons, NULL is returned.
     *
     *
     * @see https://postgis.net/docs/ST_BuildArea.html
     */
    public static function buildArea($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_BuildArea', [GeoParam::wrap($geometry)]);
    }

    /**
     * Computes a point which is the geometric center of mass of a geometry. For [MULTI]POINTs, the centroid is the arithmetic mean of the input coordinates. For [MULTI]LINESTRINGs, the centroid is computed using the weighted length of each line segment. For [MULTI]POLYGONs, the centroid is computed in terms of area. If an empty geometry is supplied, an empty GEOMETRYCOLLECTION is returned. If NULL is supplied, NULL is returned. If CIRCULARSTRING or COMPOUNDCURVE are supplied, they are converted to linestring with CurveToLine first, then same than for LINESTRING
     *
     *
     * @see https://postgis.net/docs/ST_Centroid.html
     */
    public static function centroid($geometry, bool|Expression|\Closure|null $useSpheroid = null, ?GeometryType $geometryType = null): MagellanGeometryExpression
    {
        if ($geometryType === null && $useSpheroid !== null) {
            $geometryType = GeometryType::Geography;
        }

        $useSpheroid = $useSpheroid ?? true;
        $optionalParamters = $geometryType === GeometryType::Geography ? [$useSpheroid] : [];

        return MagellanBaseExpression::geometry('ST_Centroid', [GeoParam::wrap($geometry), ...$optionalParamters], $geometryType);
    }

    /**
     * Returns a "smoothed" version of the given geometry using the Chaikin algorithm. See Chaikins-Algorithm for an explanation of the process. For each iteration the number of vertex points will double. The function puts new vertex points at 1/4 of the line before and after each point and removes the original point. To reduce the number of points use one of the simplification functions on the result. The new points gets interpolated values for all included dimensions, also z and m.
     *
     *
     * @see https://postgis.net/docs/ST_ChaikinSmoothing.html
     */
    public static function chaikinSmoothing($geometry, int|Expression|\Closure|null $iterations = null, bool|Expression|\Closure|null $preserveEndPoints = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ChaikinSmoothing', [GeoParam::wrap($geometry), $iterations, $preserveEndPoints]);
    }

    /**
     * A concave hull of a geometry is a possibly concave geometry that encloses the vertices of the input geometry. In the general case the concave hull is a Polygon. The polygon will not contain holes unless the optional param_allow_holes argument is specified as true. The concave hull of two or more collinear points is a two-point LineString. The concave hull of one or more identical points is a Point.
     *
     * @param  float|Expression|\Closure  $pctconvex  controls the concaveness of the computed hull. A value of 1 produces the convex hull. A value of 0 produces a hull of maximum concaveness (but still a single polygon). Values between 1 and 0 produce hulls of increasing concaveness. Choosing a suitable value depends on the nature of the input data, but often values between 0.3 and 0.1 produce reasonable results.
     *
     * @see https://postgis.net/docs/ST_ConcaveHull.html
     */
    public static function concaveHull($geometry, float|Expression|\Closure $pctconvex, bool|Expression|\Closure|null $allowHoles = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ConcaveHull', [GeoParam::wrap($geometry), $pctconvex, $allowHoles]);
    }

    /**
     * Computes the convex hull of a geometry. The convex hull is the smallest convex geometry that encloses all geometries in the input.
     * One can think of the convex hull as the geometry obtained by wrapping an rubber band around a set of geometries. This is different from a concave hull which is analogous to "shrink-wrapping" the geometries. A convex hull is often used to determine an affected area based on a set of point observations.
     *
     *
     * @see https://postgis.net/docs/ST_ConvexHull.html
     */
    public static function convexHull($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ConvexHull', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the Delaunay triangulation of the vertices of the input geometry. Output is a COLLECTION of polygons (for flags=0) or a MULTILINESTRING (for flags=1) or TIN (for flags=2). The tolerance, if any, is used to snap input vertices together.
     *
     *
     * @see https://postgis.net/docs/ST_DelaunayTriangles.html
     */
    public static function delaunayTriangles($geometry, float|Expression|\Closure|null $tolerance = null, ?DelaunayTrianglesOutput $output = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_DelaunayTriangles', [GeoParam::wrap($geometry), $tolerance, $output?->value]);
    }

    /**
     * Filters out vertex points based on their M-value. Returns a geometry with only vertex points that have a M-value larger or equal to the min value and smaller or equal to the max value. If max-value argument is left out only min value is considered. If fourth argument is left out the m-value will not be in the resulting geometry. If resulting geometry have too few vertex points left for its geometry type an empty geometry will be returned. In a geometry collection geometries without enough points will just be left out silently.
     *
     *
     * @see https://postgis.net/docs/ST_FilterByM.html
     */
    public static function filterByM($geometry, float|Expression|\Closure $min, float|Expression|\Closure|null $max = null, bool|Expression|\Closure|null $returnM = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_FilterByM', [GeoParam::wrap($geometry), $min, $max, $returnM]);
    }

    /**
     * Generates a given number of pseudo-random points which lie within the input area.
     *
     * @param  int|Expression|\Closure|null  $seed  is used to regenerate a deterministic sequence of points, and must be greater than zero.
     *
     * @see https://postgis.net/docs/ST_GeneratePoints.html
     */
    public static function generatePoints($geometry, int|Expression|\Closure $numberOfPoints, int|Expression|\Closure|null $seed = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_GeneratePoints', [GeoParam::wrap($geometry), $numberOfPoints, $seed]);
    }

    /**
     * Computes the approximate geometric median of a MultiPoint geometry using the Weiszfeld algorithm. The geometric median is the point minimizing the sum of distances to the input points. It provides a centrality measure that is less sensitive to outlier points than the centroid (center of mass).
     *
     *
     * @see https://postgis.net/docs/ST_GeometricMedian.html
     */
    public static function geometricMedian($geometry, float|Expression|\Closure|null $tolerance = null, int|Expression|\Closure|null $maxIterations = null, bool|Expression|\Closure|null $failIfNotConverged = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_GeometricMedian', [GeoParam::wrap($geometry), $tolerance, $maxIterations, $failIfNotConverged]);
    }

    /**
     * Returns a LineString or MultiLineString formed by joining together the line elements of a MultiLineString. Lines are joined at their endpoints at 2-way intersections. Lines are not joined across intersections of 3-way or greater degree.
     *
     *
     * @see https://postgis.net/docs/ST_LineMerge.html
     */
    public static function lineMerge($geometry, bool|Expression|\Closure|null $directed = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_LineMerge', [GeoParam::wrap($geometry), $directed]);
    }

    /**
     * Returns the smallest circle polygon that contains a geometry.
     *
     * @param  int|Expression|\Closure|null  $numberOfSegmentsPerQuarterCircle  The bounding circle is approximated by a polygon with a default of 48 segments per quarter circle. Because the polygon is an approximation of the minimum bounding circle, some points in the input geometry may not be contained within the polygon. The approximation can be improved by increasing the number of segments. For applications where an approximation is not suitable ST_MinimumBoundingRadius may be used.
     *
     * @see https://postgis.net/docs/ST_MinimumBoundingCircle.html
     */
    public static function minimumBoundingCircle($geometry, int|Expression|\Closure|null $numberOfSegmentsPerQuarterCircle = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_MinimumBoundingCircle', [GeoParam::wrap($geometry), $numberOfSegmentsPerQuarterCircle]);
    }

    /**
     * Returns the minimum-area rotated rectangle enclosing a geometry.
     * Note that more than one such rectangle may exist.
     * May return a Point or LineString in the case of degenerate inputs.
     *
     *
     * @see https://postgis.net/docs/ST_OrientedEnvelope.html
     */
    public static function orientedEnvelope($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_OrientedEnvelope', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return an offset line at a given distance and side from an input line.
     * All points of the returned geometries are not further than the given distance from the input geometry.
     * Useful for computing parallel lines about a center line.
     *
     *
     * @see https://postgis.net/docs/ST_OffsetCurve.html
     */
    public static function offsetCurve($geometry, float|Expression|\Closure $signedDistance, int|Expression|\Closure|null $numSegQuarterCircle = null, ?Join $styleJoin = null, float|Expression|\Closure|null $styleMitreLevel = null): MagellanGeometryExpression
    {
        $arguments = [
            GeoParam::wrap($geometry),
            $signedDistance,
        ];

        $styleParts = [
            "quad_segs=$numSegQuarterCircle",
            "join=$styleJoin?->value",
            "mitre_level=$styleMitreLevel",
        ];

        $styleParameter = collect($styleParts)
            ->filter(fn ($part) => ! Str::endsWith($part, '='))
            ->join(' ');

        if (! empty($styleParameter)) {
            $arguments[] = $styleParameter;
        }

        return MagellanBaseExpression::geometry('ST_OffsetCurve', $arguments);
    }

    /**
     * Returns a POINT which is guaranteed to lie in the interior of a surface (POLYGON, MULTIPOLYGON, and CURVED POLYGON). In PostGIS this function also works on line and point geometries.
     *
     *
     * @see https://postgis.net/docs/ST_PointOnSurface.html
     */
    public static function pointOnSurface($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_PointOnSurface', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns a valid geometry with all points rounded to the provided grid tolerance, and features below the tolerance removed.
     *
     *
     * @see https://postgis.net/docs/ST_ReducePrecision.html
     */
    public static function reducePrecision($geometry, float|Expression|\Closure $gridSize): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ReducePrecision', [GeoParam::wrap($geometry), $gridSize]);
    }

    /**
     * Returns a collection containing paths shared by the two input geometries. Those going in the same direction are in the first element of the collection, those going in the opposite direction are in the second element. The paths themselves are given in the direction of the first geometry.
     *
     *
     * @see https://postgis.net/docs/ST_SharedPaths.html
     */
    public static function sharedPaths($geometryA, $geometryB): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_SharedPaths', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns a "simplified" version of the given geometry using the Douglas-Peucker algorithm. Will actually do something only with (multi)lines and (multi)polygons but you can safely call it with any kind of geometry. Since simplification occurs on a object-by-object basis you can also feed a GeometryCollection to this function.
     *
     *
     * @see https://postgis.net/docs/ST_Simplify.html
     */
    public static function simplify($geometry, float|Expression|\Closure $tolerance, bool|Expression|\Closure|null $preserveCollapsed = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Simplify', [GeoParam::wrap($geometry), $tolerance, $preserveCollapsed]);
    }

    /**
     * Computes a simplified topology-preserving outer or inner hull of a polygonal geometry. An outer hull completely covers the input geometry. An inner hull is completely covered by the input geometry. The result is a polygonal geometry formed by a subset of the input vertices. MultiPolygons and holes are handled and produce a result with the same structure as the input.
     *
     * @param  float|Expression|\Closure  $vertexFraction  The reduction in vertex count is controlled by the vertex_fraction parameter, which is a number in the range 0 to 1. Lower values produce simpler results, with smaller vertex count and less concaveness. For both outer and inner hulls a vertex fraction of 1.0 produces the orginal geometry. For outer hulls a value of 0.0 produces the convex hull (for a single polygon); for inner hulls it produces a triangle.
     *
     * @see https://postgis.net/docs/ST_SimplifyPolygonHull.html
     */
    public static function simplifyPolygonHull($geometry, float|Expression|\Closure $vertexFraction, bool|Expression|\Closure|null $isOuter = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_SimplifyPolygonHull', [GeoParam::wrap($geometry), $vertexFraction, $isOuter]);
    }

    /**
     * Returns a "simplified" version of the given geometry using the Douglas-Peucker algorithm. Will avoid creating derived geometries (polygons in particular) that are invalid. Will actually do something only with (multi)lines and (multi)polygons but you can safely call it with any kind of geometry. Since simplification occurs on a object-by-object basis you can also feed a GeometryCollection to this function.
     *
     *
     * @see https://postgis.net/docs/ST_SimplifyPreserveTopology.html
     */
    public static function simplifyPreserveTopology($geometry, float|Expression|\Closure $tolerance): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_SimplifyPreserveTopology', [GeoParam::wrap($geometry), $tolerance]);
    }

    /**
     * Returns a "simplified" version of the given geometry using the Visvalingam-Whyatt algorithm.
     * Will actually do something only with (multi)lines and (multi)polygons but you can safely call it with any kind of geometry.
     * Since simplification occurs on a object-by-object basis you can also feed a GeometryCollection to this function.
     *
     *
     * @see https://postgis.net/docs/ST_SimplifyVW.html
     */
    public static function simplifyVW($geometry, float|Expression|\Closure $tolerance): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_SimplifyVW', [GeoParam::wrap($geometry), $tolerance]);
    }

    /**
     * Sets the effective area for each vertex, using the Visvalingam-Whyatt algorithm.
     * The effective area is stored as the M-value of the vertex.
     * If the optional "theshold" parameter is used, a simplified geometry will be returned,
     * containing only vertices with an effective area greater than or equal to the threshold value.
     *
     *
     * @see https://postgis.net/docs/ST_SetEffectiveArea.html
     */
    public static function setEffectiveArea($geometry, float|Expression|\Closure|null $threshold = null, int|Expression|\Closure|null $setArea = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_SetEffectiveArea', [GeoParam::wrap($geometry), $threshold, $setArea]);
    }

    /**
     * Computes the constrained Delaunay triangulation of polygons. Holes and Multipolygons are supported.
     * The "constrained Delaunay triangulation" of a polygon is a set of triangles formed from the vertices of the polygon, and covering it exactly, with the maximum total interior angle over all possible triangulations. It provides the "best quality" triangulation of the polygon.
     *
     *
     * @see https://postgis.net/docs/ST_TriangulatePolygon.html
     */
    public static function triangulatePolygon($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_TriangulatePolygon', [GeoParam::wrap($geometry)]);
    }

    /**
     * ST_VoronoiLines computes a two-dimensional Voronoi diagram from the vertices of the supplied geometry and returns the boundaries between cells in that diagram as a MultiLineString. Returns null if input geometry is null. Returns an empty geometry collection if the input geometry contains only one vertex. Returns an empty geometry collection if the extend_to envelope has zero area.
     *
     * @param  float|Expression|\Closure|null  $tolerance  The distance within which vertices will be considered equivalent. Robustness of the algorithm can be improved by supplying a nonzero tolerance distance. (default = 0.0)
     * @param  mixed  $extendToGeometry  If a geometry is supplied as the "extend_to" parameter, the diagram will be extended to cover the envelope of the "extend_to" geometry, unless that envelope is smaller than the default envelope (default = NULL, default envelope is boundingbox of input geometry extended by about 50% in each direction).
     *
     * @see https://postgis.net/docs/ST_VoronoiLines.html
     */
    public static function voronoiLines($geometry, float|Expression|\Closure|null $tolerance = null, $extendToGeometry = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_VoronoiLines', [GeoParam::wrap($geometry), $tolerance, GeoParam::wrap($extendToGeometry)]);
    }

    /**
     * ST_VoronoiPolygons computes a two-dimensional Voronoi diagram from the vertices of the supplied geometry. The result is a GeometryCollection of Polygons that covers an envelope larger than the extent of the input vertices. Returns null if input geometry is null. Returns an empty geometry collection if the input geometry contains only one vertex. Returns an empty geometry collection if the extend_to envelope has zero area.
     *
     * @param  float|Expression|\Closure|null  $tolerance  The distance within which vertices will be considered equivalent. Robustness of the algorithm can be improved by supplying a nonzero tolerance distance. (default = 0.0)
     * @param  mixed  $extendToGeometry  If a geometry is supplied as the "extend_to" parameter, the diagram will be extended to cover the envelope of the "extend_to" geometry, unless that envelope is smaller than the default envelope (default = NULL, default envelope is boundingbox of input geometry extended by about 50% in each direction).
     *
     * @see https://postgis.net/docs/ST_VoronoiPolygons.html
     */
    public static function voronoiPolygons($geometry, float|Expression|\Closure|null $tolerance = null, $extendToGeometry = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_VoronoiPolygons', [GeoParam::wrap($geometry), $tolerance, GeoParam::wrap($extendToGeometry)]);
    }
}
