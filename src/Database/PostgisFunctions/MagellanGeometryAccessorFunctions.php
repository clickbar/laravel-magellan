<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanStringExpression;
use Illuminate\Database\Query\Expression;

trait MagellanGeometryAccessorFunctions
{
    /**
     * Returns the closure of the combinatorial boundary of this Geometry. The combinatorial boundary is defined as described in section 3.12.3.2 of the OGC SPEC. Because the result of this function is a closure, and hence topologically closed, the resulting boundary can be represented using representational geometry primitives as discussed in the OGC SPEC, section 3.12.2.
     *
     *
     * @see https://postgis.net/docs/ST_Boundary.html
     */
    public static function boundary($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Boundary', [GeoParam::wrap($geometry)]);
    }

    /**
     * Orders by the diagonal of the supplied geometry's bounding box as a LineString. The diagonal is a 2-point LineString with the minimum values of each dimension in its start point and the maximum values in its end point. If the input geometry is empty, the diagonal line is a LINESTRING EMPTY.
     *
     *
     * @see https://postgis.net/docs/ST_BoundingDiagonal.html
     */
    public static function boundingDiagonal($geometry, bool|Expression|\Closure|null $fits = null): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_BoundingDiagonal', [GeoParam::wrap($geometry), $fits]);
    }

    /**
     * Return the topological dimension of this Geometry object, which must be less than or equal to the coordinate dimension. OGC SPEC s2.1.1.1 - returns 0 for POINT, 1 for LINESTRING, 2 for POLYGON, and the largest dimension of the components of a GEOMETRYCOLLECTION. If the dimension is unknown (e.g. for an empty GEOMETRYCOLLECTION) 0 is returned.
     *
     *
     * @see https://postgis.net/docs/ST_CoordDim.html
     */
    public static function coordDim($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_CoordDim', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the coordinate dimension of the ST_Geometry value.
     *
     *
     * @see https://postgis.net/docs/ST_Dimension.html
     */
    public static function dimension($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_Dimension', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the last point of a LINESTRING or CIRCULARLINESTRING geometry as a POINT. Returns NULL if the input is not a LINESTRING or CIRCULARLINESTRING.
     *
     *
     * @see https://postgis.net/docs/ST_EndPoint.html
     */
    public static function endPoint($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_EndPoint', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the double-precision (float8) minimum bounding box for the supplied geometry, as a geometry. The polygon is defined by the corner points of the bounding box ((MINX, MINY), (MINX, MAXY), (MAXX, MAXY), (MAXX, MINY), (MINX, MINY)). (PostGIS will add a ZMIN/ZMAX coordinate as well).
     *
     *
     * @see https://postgis.net/docs/ST_Envelope.html
     */
    public static function envelope($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Envelope', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns a LINESTRING representing the exterior ring (shell) of a POLYGON. Returns NULL if the geometry is not a polygon.
     *
     *
     * @see https://postgis.net/docs/ST_ExteriorRing.html
     */
    public static function exteriorRing($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_ExteriorRing', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the 1-based Nth element geometry of an input geometry which is a GEOMETRYCOLLECTION, MULTIPOINT, MULTILINESTRING, MULTICURVE, MULTI)POLYGON, or POLYHEDRALSURFACE. Otherwise, returns NULL.
     *
     *
     * @see https://postgis.net/docs/ST_GeometryN.html
     */
    public static function geometryN($geometry, int|Expression|\Closure $n): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_GeometryN', [GeoParam::wrap($geometry), $n]);
    }

    /**
     * Returns true if a geometry or geometry collection contains a circular string
     *
     *
     * @see https://postgis.net/docs/ST_HasArc.html
     */
    public static function hasArc($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_HasArc', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the Nth interior ring (hole) of a POLYGON geometry as a LINESTRING. The index starts at 1. Returns NULL if the geometry is not a polygon or the index is out of range.
     *
     *
     * @see https://postgis.net/docs/ST_InteriorRingN.html
     */
    public static function interiorRingN($geometry, int|Expression|\Closure $n): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_InteriorRingN', [GeoParam::wrap($geometry), $n]);
    }

    /**
     * Returns TRUE if the LINESTRING's start and end points are coincident. For Polyhedral Surfaces, reports if the surface is areal (open) or volumetric (closed).
     *
     *
     * @see https://postgis.net/docs/ST_IsClosed.html
     */
    public static function isClosed($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsClosed', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns TRUE if the geometry type of the argument a geometry collection type. Collection types are the following:
     * - GEOMETRYCOLLECTION
     * - MULTI{POINT,POLYGON,LINESTRING,CURVE,SURFACE}
     * - COMPOUNDCURVE
     *
     *
     * @see https://postgis.net/docs/ST_IsCollection.html
     */
    public static function isCollection($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsCollection', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns true if this Geometry is an empty geometry. If true, then this Geometry represents an empty geometry collection, polygon, point etc.
     *
     *
     * @see https://postgis.net/docs/ST_IsEmpty.html
     */
    public static function isEmpty($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsEmpty', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns true if all polygonal components of the input geometry use a counter-clockwise orientation for their exterior ring, and a clockwise direction for all interior rings.
     * Returns true if the geometry has no polygonal components.
     *
     *
     * @see https://postgis.net/docs/ST_IsPolygonCCW.html
     */
    public static function isPolygonCCW($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsPolygonCCW', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns true if all polygonal components of the input geometry use a clockwise orientation for their exterior ring, and a counter-clockwise direction for all interior rings.
     * Returns true if the geometry has no polygonal components.
     *
     *
     * @see https://postgis.net/docs/ST_IsPolygonCW.html
     */
    public static function isPolygonCW($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsPolygonCW', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns TRUE if this LINESTRING is both ST_IsClosed (ST_StartPoint(g) ~= ST_Endpoint(g)) and ST_IsSimple (does not self intersect).
     *
     *
     * @see https://postgis.net/docs/ST_IsRing.html
     */
    public static function isRing($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsRing', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns true if this Geometry has no anomalous geometric points, such as self-intersection or self-tangency. For more information on the OGC's definition of geometry simplicity and validity, refer to "Ensuring OpenGIS compliancy of geometries"
     *
     *
     * @see https://postgis.net/docs/ST_IsSimple.html
     */
    public static function isSimple($geometry): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_IsSimple', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the M coordinate of a Point, or NULL if not available. Input must be a Point.
     *
     *
     * @see https://postgis.net/docs/ST_M.html
     */
    public static function m($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_M', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the amount of memory space (in bytes) the geometry takes.
     *
     *
     * @see https://postgis.net/docs/ST_MemSize.html
     */
    public static function memSize($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_MemSize', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the coordinate dimension of the geometry. PostGIS supports 2 - (x,y) , 3 - (x,y,z) or 2D with measure - x,y,m, and 4 - 3D with measure space x,y,z,m
     *
     *
     * @see https://postgis.net/docs/ST_NDims.html
     */
    public static function nDims($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_NDims', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the number of points in a geometry. Works for all geometries.
     *
     *
     * @see https://postgis.net/docs/ST_NPoints.html
     */
    public static function nPoints($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_NPoints', [GeoParam::wrap($geometry)]);
    }

    /**
     * If the geometry is a polygon or multi-polygon returns the number of rings. Unlike NumInteriorRings, it counts the outer rings as well.
     *
     *
     * @see https://postgis.net/docs/ST_NRings.html
     */
    public static function nRings($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_NRings', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the number of Geometries. If geometry is a GEOMETRYCOLLECTION (or MULTI*) return the number of geometries, for single geometries will return 1, otherwise return NULL.
     *
     *
     * @see https://postgis.net/docs/ST_NumGeometries.html
     */
    public static function numGeometries($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_NumGeometries', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the number of interior rings of a polygon geometry. Return NULL if the geometry is not a polygon.
     *
     *
     * @see https://postgis.net/docs/ST_NumInteriorRings.html
     */
    public static function numInteriorRings($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_NumInteriorRings', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the number of faces on a Polyhedral Surface. Will return null for non-polyhedral geometries. This is an alias for ST_NumGeometries to support MM naming. Faster to use ST_NumGeometries if you don't care about MM convention.
     *
     *
     * @see https://postgis.net/docs/ST_NumPatches.html
     */
    public static function numPatches($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_NumPatches', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the 1-based Nth geometry (face) if the geometry is a POLYHEDRALSURFACE or POLYHEDRALSURFACEM. Otherwise, returns NULL. This returns the same answer as ST_GeometryN for PolyhedralSurfaces. Using ST_GeometryN is faster.
     *
     *
     * @see https://postgis.net/docs/ST_PatchN.html
     */
    public static function patchN($geometry, int|Expression|\Closure $n): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_PatchN', [GeoParam::wrap($geometry), $n]);
    }

    /**
     * Return the Nth point in a single linestring or circular linestring in the geometry. Negative values are counted backwards from the end of the LineString, so that -1 is the last point. Returns NULL if there is no linestring in the geometry.
     *
     *
     * @see https://postgis.net/docs/ST_PointN.html
     */
    public static function pointN($geometry, int|Expression|\Closure $n): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_PointN', [GeoParam::wrap($geometry), $n]);
    }

    /**
     * Returns a MultiPoint containing all the coordinates of a geometry. Duplicate points are preserved, including the start and end points of ring geometries. (If desired, duplicate points can be removed by calling ST_RemoveRepeatedPoints on the result).
     *
     *
     * @see https://postgis.net/docs/ST_Points.html
     */
    public static function points($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Points', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the first point of a LINESTRING or CIRCULARLINESTRING geometry as a POINT. Returns NULL if the input is not a LINESTRING or CIRCULARLINESTRING.
     *
     *
     * @see https://postgis.net/docs/ST_StartPoint.html
     */
    public static function startPoint($geometry): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_StartPoint', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns a text summary of the contents of the geometry.
     *
     *
     * @see https://postgis.net/docs/ST_Summary.html
     */
    public static function summary($geometry): MagellanStringExpression
    {
        return MagellanBaseExpression::string('ST_Summary', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the X coordinate of the point, or NULL if not available. Input must be a point.
     *
     *
     * @see https://postgis.net/docs/ST_X.html
     */
    public static function x($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_X', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the Y coordinate of the point, or NULL if not available. Input must be a point.
     *
     *
     * @see https://postgis.net/docs/ST_Y.html
     */
    public static function y($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_Y', [GeoParam::wrap($geometry)]);
    }

    /**
     * Return the Z coordinate of the point, or NULL if not available. Input must be a point.
     *
     *
     * @see https://postgis.net/docs/ST_Z.html
     */
    public static function z($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_Z', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns a code indicating the ZM coordinate dimension of a geometry.
     * Values are: 0 = 2D, 1 = 3D-M, 2 = 3D-Z, 3 = 4D.
     *
     *
     * @see https://postgis.net/docs/ST_Zmflag.html
     */
    public static function zmflag($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_Zmflag', [GeoParam::wrap($geometry)]);
    }
}
