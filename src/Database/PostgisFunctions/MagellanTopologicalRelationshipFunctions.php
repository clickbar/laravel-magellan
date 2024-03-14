<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanStringExpression;
use Clickbar\Magellan\Enums\GeometryType;
use Illuminate\Database\Query\Expression;

trait MagellanTopologicalRelationshipFunctions
{
    /**
     * Overlaps, Touches, Within all imply spatial intersection. If any of the aforementioned returns true, then the geometries also spatially intersect. Disjoint implies false for spatial intersection.
     *
     *
     * @see https://postgis.net/docs/ST_3DIntersects.html
     */
    public static function intersects3D($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_3DIntersects', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns TRUE if geometry B is completely inside geometry A. A contains B if and only if no points of B lie in the exterior of A, and at least one point of the interior of B lies in the interior of A.
     * A subtlety of the definition is that a geometry does not contain things in its boundary. Thus polygons and lines do not contain lines and points lying in their boundary. For further details see Subtleties of OGC Covers, Contains, Within. (The ST_Covers predicate provides a more inclusive relationship.) However, a geometry does contain itself. (In contrast, in the ST_ContainsProperly predicate a geometry does not properly contain itself.)
     * ST_Contains is the inverse of ST_Within. So, ST_Contains(A,B) = ST_Within(B,A).
     *
     *
     * @see https://postgis.net/docs/ST_Contains.html
     */
    public static function contains($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Contains', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns true if B intersects the interior of A but not the boundary or exterior.
     * A does not properly contain itself, but does contain itself.
     * Every point of the other geometry is a point of this geometry's interior. The DE-9IM Intersection Matrix for the two geometries matches [T**FF*FF*] used in ST_Relate
     * An example use case for this predicate is computing the intersections of a set of geometries with a large polygonal geometry. Since intersection is a fairly slow operation, it can be more efficient to use containsProperly to filter out test geometries which lie wholly inside the area. In these cases the intersection is known a priori to be exactly the original test geometry.
     *
     *
     * @see https://postgis.net/docs/ST_ContainsProperly.html
     */
    public static function containsProperly($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_ContainsProperly', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns true if no point in Geometry/Geography A lies outside Geometry/Geography B. Equivalently, tests if every point of geometry A is inside (i.e. intersects the interior or boundary of) geometry B.
     *
     *
     * @see https://postgis.net/docs/ST_CoveredBy.html
     */
    public static function coveredBy($geometryA, $geometryB, ?GeometryType $geometryType = GeometryType::Geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_CoveredBy', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)], $geometryType);
    }

    /**
     * Returns true if no point in Geometry/Geography B is outside Geometry/Geography A. Equivalently, tests if every point of geometry B is inside (i.e. intersects the interior or boundary of) geometry A.
     *
     *
     * @see https://postgis.net/docs/ST_Covers.html
     */
    public static function covers($geometryA, $geometryB, ?GeometryType $geometryType = GeometryType::Geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Covers', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)], $geometryType);
    }

    /**
     * Compares two geometry objects and returns true if their intersection "spatially cross", that is, the geometries have some, but not all interior points in common. The intersection of the interiors of the geometries must be non-empty and must have dimension less than the maximum dimension of the two input geometries. Additionally, the intersection of the two geometries must not equal either of the source geometries. Otherwise, it returns false.
     * In mathematical terms, this is:
     * Geometries cross if their DE-9IM Intersection Matrix matches:
     * - T*T****** for Point/Line, Point/Area, and Line/Area situations
     * - T*****T** for Line/Point, Area/Point, and Area/Line situations
     * - 0******** for Line/Line situations
     * For Point/Point and Area/Area situations this predicate returns false.
     * The OpenGIS Simple Features Specification defines this predicate only for Point/Line, Point/Area, Line/Line, and Line/Area situations. JTS / GEOS extends the definition to apply to Line/Point, Area/Point and Area/Line situations as well. This makes the relation symmetric.
     *
     *
     * @see https://postgis.net/docs/ST_Crosses.html
     */
    public static function crosses($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Crosses', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Overlaps, Touches, Within all imply geometries are not spatially disjoint. If any of the aforementioned returns true, then the geometries are not spatially disjoint. Disjoint implies false for spatial intersection.
     *
     *
     * @see https://postgis.net/docs/ST_Disjoint.html
     */
    public static function disjoint($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Disjoint', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns true if the given geometries are "spatially equal". Use this for a 'better' answer than '='. Note by spatially equal we mean ST_Within(A,B) = true and ST_Within(B,A) = true and also mean ordering of points can be different but represent the same geometry structure. To verify the order of points is consistent, use ST_OrderingEquals (it must be noted ST_OrderingEquals is a little more stringent than simply verifying order of points are the same).
     *
     *
     * @see https://postgis.net/docs/ST_Equals.html
     */
    public static function equals($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Equals', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Compares two geometries and returns true if they intersect. Geometries intersect if they have any point in common.
     * For geography, a distance tolerance of 0.00001 meters is used (so points that are very close are considered to intersect).
     * Geometries intersect if their DE-9IM Intersection Matrix matches one of:
     * - T********
     * - *T*******
     * - ***T*****
     * - ****T****
     * Spatial intersection is implied by all the other spatial relationship tests, except ST_Disjoint, which tests that geometries do NOT intersect.
     *
     *
     * @see https://postgis.net/docs/ST_Intersects.html
     */
    public static function intersects($geometryA, $geometryB, ?GeometryType $geometryType = GeometryType::Geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Intersects', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)], $geometryType);
    }

    /**
     * Given two linestrings returns an integer between -3 and 3 indicating what kind of crossing behavior exists between them. 0 indicates no crossing. This is only supported for LINESTRINGs.
     * The crossing number has the following meaning:
     * - 0: LINE NO CROSS
     * - -1: LINE CROSS LEFT
     * - 1: LINE CROSS RIGHT
     * - -2: LINE MULTICROSS END LEFT
     * - 2: LINE MULTICROSS END RIGHT
     * - -3: LINE MULTICROSS END SAME FIRST LEFT
     * - 3: LINE MULTICROSS END SAME FIRST RIGHT
     *
     *
     * @see https://postgis.net/docs/ST_LineCrossingDirection.html
     */
    public static function lineCrossingDirection($lineStringA, $lineStringB): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_LineCrossingDirection', [GeoParam::wrap($lineStringA), GeoParam::wrap($lineStringB)]);
    }

    /**
     * ST_OrderingEquals compares two geometries and returns t (TRUE) if the geometries are equal and the coordinates are in the same order; otherwise it returns f (FALSE).
     *
     *
     * @see https://postgis.net/docs/ST_OrderingEquals.html
     */
    public static function orderingEquals($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_OrderingEquals', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns TRUE if geometry A and B "spatially overlap". Two geometries overlap if they have the same dimension, each has at least one point not shared by the other (or equivalently neither covers the other), and the intersection of their interiors has the same dimension. The overlaps relationship is symmetrical.
     *
     *
     * @see https://postgis.net/docs/ST_Overlaps.html
     */
    public static function overlaps($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Overlaps', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * These functions allow testing and evaluating the spatial (topological) relationship between two geometries, as defined by the Dimensionally Extended 9-Intersection Model (DE-9IM).
     * The DE-9IM is specified as a 9-element matrix indicating the dimension of the intersections between the Interior, Boundary and Exterior of two geometries. It is represented by a 9-character text string using the symbols 'F', '0', '1', '2' (e.g. 'FF1FF0102').
     * A specific kind of spatial relationships is evaluated by comparing the intersection matrix to an intersection matrix pattern. A pattern can include the additional symbols 'T' and '*'. Common spatial relationships are provided by the named functions ST_Contains, ST_ContainsProperly, ST_Covers, ST_CoveredBy, ST_Crosses, ST_Disjoint, ST_Equals, ST_Intersects, ST_Overlaps, ST_Touches, and ST_Within. Using an explicit pattern allows testing multiple conditions of intersects, crosses, etc in one step. It also allows testing spatial relationships which do not have a named spatial relationship function. For example, the relationship "Interior-Intersects" has the DE-9IM pattern T********, which is not evaluated by any named predicate.
     * For more information refer to Section 5.1, “Determining Spatial Relationships”.
     * Variant 1: Tests if two geometries are spatially related according to the given intersectionMatrixPattern.
     *
     *
     * @see https://postgis.net/docs/ST_Relate.html
     */
    public static function relate($geometryA, $geometryB, string|Expression|\Closure|null $intersectionMatrixPattern = null, int|Expression|\Closure|null $boundaryNodeRule = null): MagellanBooleanExpression|MagellanStringExpression
    {
        if ($intersectionMatrixPattern != null) {
            return MagellanBaseExpression::boolean('ST_Relate', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $intersectionMatrixPattern]);
        }

        return MagellanBaseExpression::string('ST_Relate', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $boundaryNodeRule]);
    }

    /**
     * Tests if a Dimensionally Extended 9-Intersection Model (DE-9IM) intersectionMatrix value satisfies an intersectionMatrixPattern. Intersection matrix values can be computed by ST_Relate.
     *
     *
     * @see https://postgis.net/docs/ST_RelateMatch.html
     */
    public static function relateMatch(string|Expression|\Closure $intersectionMatrix, string|Expression|\Closure $intersectionMatrixPattern): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_RelateMatch', [$intersectionMatrix, $intersectionMatrixPattern]);
    }

    /**
     * Returns TRUE if A and B intersect, but their interiors do not intersect. Equivalently, A and B have at least one point in common, and the common points lie in at least one boundary. For Point/Point inputs the relationship is always FALSE, since points do not have a boundary.
     * In mathematical terms, this relationship is:
     * This relationship holds if the DE-9IM Intersection Matrix for the two geometries matches one of:
     * - FT*******
     * - F**T*****
     * - F***T****
     *
     *
     * @see https://postgis.net/docs/ST_Touches.html
     */
    public static function touches($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Touches', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }

    /**
     * Returns TRUE if geometry A is completely inside geometry B. For this function to make sense, the source geometries must both be of the same coordinate projection, having the same SRID. It is a given that if ST_Within(A,B) is true and ST_Within(B,A) is true, then the two geometries are considered spatially equal.
     * A subtlety of this definition is that the boundary of a geometry is not within the geometry. This means that lines and points lying in the boundary of a polygon or line are not within the geometry. For further details see Subtleties of OGC Covers, Contains, Within. (The ST_CoveredBy predicate provides a more inclusive relationship).
     * ST_Within is the inverse of ST_Contains. So, ST_Within(A,B) = ST_Contains(B,A).
     *
     *
     * @see https://postgis.net/docs/ST_Within.html
     */
    public static function within($geometryA, $geometryB): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_Within', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB)]);
    }
}
