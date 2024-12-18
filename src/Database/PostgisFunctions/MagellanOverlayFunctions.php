<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Data\Boxes\Box2D;
use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanSetExpression;
use Clickbar\Magellan\Enums\GeometryType;
use Illuminate\Database\Query\Expression;

trait MagellanOverlayFunctions
{
    /**
     * Clips a geometry by a 2D box in a fast and tolerant but possibly invalid way.
     * Topologically invalid input geometries do not result in exceptions being thrown.
     * The output geometry is not guaranteed to be valid (in particular, self-intersections for a polygon may be introduced).
     *
     *
     * @see https://postgis.net/docs/ST_ClipByBox2D.html
     */
    public static function clipByBox2D($geometry, Box2D|Expression|\Closure $box): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ClipByBox2D', [GeoParam::wrap($geometry), $box]);
    }

    /**
     * Returns a geometry representing the part of geometry A that does not intersect geometry B. This is equivalent to A - ST_Intersection(A,B). If A is completely contained in B then an empty atomic geometry of appropriate type is returned.
     *
     * @param  float|Expression|\Closure|null  $gridSize  If the optional gridSize argument is provided, the inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     *
     * @see https://postgis.net/docs/ST_Difference.html
     */
    public static function difference($geometryA, $geometryB, float|Expression|\Closure|null $gridSize = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Difference', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $gridSize]);
    }

    /**
     * Returns a geometry representing the point-set intersection of two geometries. In other words, that portion of geometry A and geometry B that is shared between the two geometries.
     * If the geometries have no points in common (i.e. are disjoint) then an empty atomic geometry of appropriate type is returned.
     *
     * Note: GridSize is only supported for geometry inputs, not geography.
     *
     * @param  float|Expression|\Closure|null  $gridSize  If the optional gridSize argument is provided, the inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     * @param  GeometryType|null  $geometryType  Defines the type of the input geometries. Which those values will be casted to.
     *
     * @see https://postgis.net/docs/ST_Intersection.html
     */
    public static function intersection($geometryA, $geometryB, float|Expression|\Closure|null $gridSize = null, ?GeometryType $geometryType = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Intersection', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $gridSize], $geometryType);
    }

    /**
     * An aggregate function that unions the input geometries, merging them to produce a result geometry with no overlaps.
     * The output may be a single geometry, a MultiGeometry, or a Geometry Collection.
     *
     * Produces the same result as ST_Union, but uses less memory and more processor time.
     * This aggregate function works by unioning the geometries incrementally, as opposed to the ST_Union aggregate which first accumulates an array and then unions the contents using a fast algorithm.
     *
     *
     * @see https://postgis.net/docs/ST_MemUnion.html
     */
    public static function memUnion($geometryField): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_MemUnion', [GeoParam::wrap($geometryField)]);
    }

    /**
     * Returns a (Multi)LineString representing the fully noded version of a collection of linestrings. The noding preserves all of the input nodes, and introduces the least possible number of new nodes. The resulting linework is dissolved (duplicate lines are removed).
     * This is a good way to create fully-noded linework suitable for use as input to ST_Polygonize.
     * This function supports 3d and will not drop the z-index.
     *
     *
     * @see https://postgis.net/docs/ST_Node.html
     */
    public static function node($lineString): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Node', [GeoParam::wrap($lineString)]);
    }

    /**
     * The function supports splitting a LineString by a (Multi)Point, (Multi)LineString or (Multi)Polygon boundary, or a (Multi)Polygon by a LineString.
     * When a (Multi)Polygon is used as as the blade, its linear components (the boundary) are used for splitting the input.
     * The result geometry is always a collection. This function is in a sense the opposite of ST_Union.
     * Applying ST_Union to the returned collection should theoretically yield the original geometry (although due to numerical rounding this may not be exactly the case).
     *
     *
     * @see https://postgis.net/docs/ST_Split.html
     */
    public static function split($geometryInput, $geometryBlade): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Split', [GeoParam::wrap($geometryInput), GeoParam::wrap($geometryBlade)]);
    }

    /**
     * Returns a set of geometries that are the result of dividing geom into parts using rectilinear lines, with each part containing no more than max_vertices.
     * max_vertices must be 5 or more, as 5 points are needed to represent a closed box. gridSize can be specified to have clipping work in fixed-precision space (requires GEOS-3.9.0+).
     * Point-in-polygon and other spatial operations are normally faster for indexed subdivided datasets. Since the bounding boxes for the parts usually cover a smaller area than the original geometry bbox, index queries produce fewer "hit" cases.
     * The "hit" cases are faster because the spatial operations executed by the index recheck process fewer points.
     *
     * @param  float|Expression|\Closure|null  $gridSize  If the optional gridSize argument is provided, the inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     *
     * @see https://postgis.net/docs/ST_Subdivide.html
     */
    public static function subdivide($geometry, int|Expression|\Closure|null $max_vertices = null, float|Expression|\Closure|null $gridSize = null): MagellanSetExpression
    {
        return MagellanBaseExpression::set('ST_Subdivide', [GeoParam::wrap($geometry), $max_vertices, $gridSize]);
    }

    /**
     * Returns a geometry representing the portions of geometries A and B that do not intersect.
     * This is equivalent to ST_Union(A,B) - ST_Intersection(A,B).
     * It is called a symmetric difference because ST_SymDifference(A,B) = ST_SymDifference(B,A).
     *
     * @param  float|Expression|\Closure|null  $gridSize  If the optional gridSize argument is provided, the inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     *
     * @see https://postgis.net/docs/ST_SymDifference.html
     */
    public static function symDifference($geometryA, $geometryB, float|Expression|\Closure|null $gridSize = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_SymDifference', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $gridSize]);
    }

    /**
     * A single-input variant of ST_Union. The input may be a single geometry, a MultiGeometry, or a GeometryCollection. The union is applied to the individual elements of the input.
     * This function can be used to fix MultiPolygons which are invalid due to overlapping components.
     * However, the input components must each be valid. An invalid input component such as a bow-tie polygon may cause an error. For this reason it may be better to use ST_MakeValid.
     * Another use of this function is to node and dissolve a collection of linestrings which cross or overlap to make them simple. (To add nodes but not dissolve duplicate linework use ST_Node.)
     * It is possible to combine ST_UnaryUnion with ST_Collect to fine-tune how many geometries are be unioned at once. This allows trading off between memory usage and compute time, striking a balance between ST_Union and ST_MemUnion.
     *
     * @param  float|Expression|\Closure|null  $gridSize  If the optional gridSize argument is provided, the inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     *
     * @see https://postgis.net/docs/ST_UnaryUnion.html
     */
    public static function unaryUnion($geometry, float|Expression|\Closure|null $gridSize = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_UnaryUnion', [GeoParam::wrap($geometry), $gridSize]);
    }

    /**
     * Unions the input geometries, merging geometry to produce a result geometry with no overlaps. The output may be an atomic geometry, a MultiGeometry, or a Geometry Collection. Comes in several variants:
     * Two-input variant: returns a geometry that is the union of two input geometries. If either input is NULL, then NULL is returned.
     * Array variant: returns a geometry that is the union of an array of geometries.
     * Aggregate variant: returns a geometry that is the union of a rowset of geometries. The ST_Union() function is an "aggregate" function in the terminology of PostgreSQL. That means that it operates on rows of data, in the same way the SUM() and AVG() functions do and like most aggregates, it also ignores NULL geometries.
     * See ST_UnaryUnion for a non-aggregate, single-input variant.
     *
     * @param  float|Expression|\Closure|null  $gridSize  A gridSize can be specified to work in fixed-precision space. The inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     *
     * @see https://postgis.net/docs/ST_Union.html
     */
    public static function unionFromGeometries($geometryA, $geometryB, float|Expression|\Closure|null $gridSize = null): MagellanGeometryExpression
    {
        // TODO: Think about standardizing the naming of the methods to be more consistent (where multiple methods are available for the same function)
        return MagellanBaseExpression::geometry('ST_Union', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $gridSize]);
    }

    /**
     * Unions the input geometries, merging geometry to produce a result geometry with no overlaps. The output may be an atomic geometry, a MultiGeometry, or a Geometry Collection. Comes in several variants:
     * Two-input variant: returns a geometry that is the union of two input geometries. If either input is NULL, then NULL is returned.
     * Array variant: returns a geometry that is the union of an array of geometries.
     * Aggregate variant: returns a geometry that is the union of a rowset of geometries. The ST_Union() function is an "aggregate" function in the terminology of PostgreSQL. That means that it operates on rows of data, in the same way the SUM() and AVG() functions do and like most aggregates, it also ignores NULL geometries.
     * See ST_UnaryUnion for a non-aggregate, single-input variant.
     *
     * @param  float|Expression|\Closure|null  $gridSize  A gridSize can be specified to work in fixed-precision space. The inputs are snapped to a grid of the given size, and the result vertices are computed on that same grid. (Requires GEOS-3.9.0 or higher)
     *
     * @see https://postgis.net/docs/ST_Union.html
     */
    public static function union($geometryArrayOrSet, float|Expression|\Closure|null $gridSize = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Union', [GeoParam::wrap($geometryArrayOrSet), $gridSize]);
    }
}
