<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 */
class PostgisGeometryAccessorMacros
{
    /*
     * ST_Boundary
     */

    public function selectBoundary(): \Closure
    {
        /**
         * Returns the closure of the combinatorial boundary of this Geometry. The combinatorial boundary is defined as described in section 3.12.3.2 of the OGC SPEC. Because the result of this function is a closure, and hence topologically closed, the resulting boundary can be represented using representational geometry primitives as discussed in the OGC SPEC, section 3.12.2.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Boundary.html
         */
        return function ($geometry, string $as = 'boundary') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getBoundaryExpression($this, 'select', $geometry, $as)
            );
        };
    }

    /*
     * ST_Boundary
     */

    public function selectBoundingDiagonal(): \Closure
    {
        /**
         * Orders by the diagonal of the supplied geometry's bounding box as a LineString. The diagonal is a 2-point LineString with the minimum values of each dimension in its start point and the maximum values in its end point. If the input geometry is empty, the diagonal line is a LINESTRING EMPTY.
         *
         * @param $geometry
         * @param bool|null $fits specifies if the best fit is needed. If false, the diagonal of a somewhat larger bounding box can be accepted (which is faster to compute for geometries with many vertices). In either case, the bounding box of the returned diagonal line always covers the input geometry.
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_BoundingDiagonal.html
         */
        return function ($geometry, ?bool $fits = null, string $as = 'boundingDiagonal') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getBoundingDiagonalExpression($this, 'select', $geometry, $fits, $as)
            );
        };
    }

    /*
     * ST_CoordDim
     */

    public function selectCoordDim(): \Closure
    {
        /**
         * Return the topological dimension of this Geometry object, which must be less than or equal to the coordinate dimension. OGC SPEC s2.1.1.1 - returns 0 for POINT, 1 for LINESTRING, 2 for POLYGON, and the largest dimension of the components of a GEOMETRYCOLLECTION. If the dimension is unknown (e.g. for an empty GEOMETRYCOLLECTION) 0 is returned.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_CoordDim.html
         */
        return function ($geometry, string $as = 'cordDim') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getCoordDimExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereCoordDim(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_CoordDim.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getCoordDimExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByCoordDim(): \Closure
    {
        /**
         * Orders by the topological dimension of this Geometry object, which must be less than or equal to the coordinate dimension. OGC SPEC s2.1.1.1 - returns 0 for POINT, 1 for LINESTRING, 2 for POLYGON, and the largest dimension of the components of a GEOMETRYCOLLECTION. If the dimension is unknown (e.g. for an empty GEOMETRYCOLLECTION) 0 is returned.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_CoordDim.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getCoordDimExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_Dimension
     */

    public function selectDimension(): \Closure
    {
        /**
         * Return the coordinate dimension of the ST_Geometry value.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Dimension.html
         */
        return function ($geometry, string $as = 'dimension') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getDimensionExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereDimension(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Dimension.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getDimensionExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByDimension(): \Closure
    {
        /**
         * Return the coordinate dimension of the ST_Geometry value.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Dimension.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getDimensionExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
    * ST_EndPoint
    */

    public function selectEndPoint(): \Closure
    {
        /**
         * Returns the last point of a LINESTRING or CIRCULARLINESTRING geometry as a POINT. Returns NULL if the input is not a LINESTRING or CIRCULARLINESTRING.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_EndPoint.html
         */
        return function ($geometry, string $as = 'endPoint') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getEndPointExpression($this, 'select', $geometry, $as)
            );
        };
    }

    /*
    * ST_Envelope
    */

    public function selectEnvelope(): \Closure
    {
        /**
         * Returns the double-precision (float8) minimum bounding box for the supplied geometry, as a geometry. The polygon is defined by the corner points of the bounding box ((MINX, MINY), (MINX, MAXY), (MAXX, MAXY), (MAXX, MINY), (MINX, MINY)). (PostGIS will add a ZMIN/ZMAX coordinate as well).
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Envelope.html
         */
        return function ($geometry, string $as = 'envelope') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getEnvelopeExpression($this, 'select', $geometry, $as)
            );
        };
    }

    /*
    * ST_ExteriorRing
    */

    public function selectExteriorRing(): \Closure
    {
        /**
         * Returns a LINESTRING representing the exterior ring (shell) of a POLYGON. Returns NULL if the geometry is not a polygon.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_ExteriorRing.html
         */
        return function ($geometry, string $as = 'exteriorRing') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getExteriorRingExpression($this, 'select', $geometry, $as)
            );
        };
    }

    /*
    * ST_GeometryN
    */

    public function selectGeometryN(): \Closure
    {
        /**
         * Return the 1-based Nth element geometry of an input geometry which is a GEOMETRYCOLLECTION, MULTIPOINT, MULTILINESTRING, MULTICURVE, MULTI)POLYGON, or POLYHEDRALSURFACE. Otherwise, returns NULL.
         *
         * @param $geometry
         * @param int $n
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_GeometryN.html
         */
        return function ($geometry, int $n, string $as = 'geometryN') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getGeometryNExpression($this, 'select', $geometry, $n, $as)
            );
        };
    }

    /*
    * ST_HasArc
    */

    public function selectHasArc(): \Closure
    {
        /**
         * Returns true if a geometry or geometry collection contains a circular string
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_HasArc.html
         */
        return function ($geometry, string $as = 'hasArc') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getHasArcExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereHasArc(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Dimension.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getHasArcExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    /*
    * ST_InteriorRingN
    */

    public function selectInteriorRingN(): \Closure
    {
        /**
         * Returns the Nth interior ring (hole) of a POLYGON geometry as a LINESTRING. The index starts at 1. Returns NULL if the geometry is not a polygon or the index is out of range.
         *
         * @param $geometry
         * @param int $n
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_InteriorRingN.html
         */
        return function ($geometry, int $n, string $as = 'interiorRingN') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getInteriorRingNExpression($this, 'select', $geometry, $n, $as)
            );
        };
    }

    /*
     * ST_IsClosed
     */

    public function selectIsClosed(): \Closure
    {
        /**
         * Returns TRUE if the LINESTRING's start and end points are coincident. For Polyhedral Surfaces, reports if the surface is areal (open) or volumetric (closed).
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsClosed.html
         */
        return function ($geometry, string $as = 'isClosed') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getIsClosedExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereIsClosed(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsClosed.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getIsClosedExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_IsCollection
     */

    public function selectIsCollection(): \Closure
    {
        /**
         * Returns TRUE if the geometry type of the argument a geometry collection type. Collection types are the following:
         * - GEOMETRYCOLLECTION
         * - MULTI{POINT,POLYGON,LINESTRING,CURVE,SURFACE}
         * - COMPOUNDCURVE
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsCollection.html
         */
        return function ($geometry, string $as = 'isCollection') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getIsCollectionExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereIsCollection(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsCollection.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getIsCollectionExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_IsEmpty
     */

    public function selectIsEmpty(): \Closure
    {
        /**
         * Returns true if this Geometry is an empty geometry. If true, then this Geometry represents an empty geometry collection, polygon, point etc.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsEmpty.html
         */
        return function ($geometry, string $as = 'isEmpty') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getIsEmptyExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereIsEmpty(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsEmpty.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getIsEmptyExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_IsPolygonCCW
     */

    public function selectIsPolygonCCW(): \Closure
    {
        /**
         * Returns true if all polygonal components of the input geometry use a counter-clockwise orientation for their exterior ring, and a clockwise direction for all interior rings.
         * Returns true if the geometry has no polygonal components.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsPolygonCCW.html
         */
        return function ($geometry, string $as = 'isPolygonCCW') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getIsPolygonCCWExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereIsPolygonCCW(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsPolygonCCW.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getIsPolygonCCWExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_IsPolygonCW
     */

    public function selectIsPolygonCW(): \Closure
    {
        /**
         * Returns true if all polygonal components of the input geometry use a clockwise orientation for their exterior ring, and a counter-clockwise direction for all interior rings.
         * Returns true if the geometry has no polygonal components.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsPolygonCW.html
         */
        return function ($geometry, string $as = 'isPolygonCW') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getIsPolygonCWExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereIsPolygonCW(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsPolygonCW.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getIsPolygonCWExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    /*
     * ST_IsRing
     */

    public function selectIsRing(): \Closure
    {
        /**
         * Returns TRUE if this LINESTRING is both ST_IsClosed (ST_StartPoint(g) ~= ST_Endpoint(g)) and ST_IsSimple (does not self intersect).
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsRing.html
         */
        return function ($geometry, string $as = 'isRing') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getIsRingExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereIsRing(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsRing.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getIsRingExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }


    /*
     * ST_IsSimple
     */

    public function selectIsSimple(): \Closure
    {
        /**
         * Returns true if this Geometry has no anomalous geometric points, such as self-intersection or self-tangency. For more information on the OGC's definition of geometry simplicity and validity, refer to "Ensuring OpenGIS compliancy of geometries"
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsSimple.html
         */
        return function ($geometry, string $as = 'isSimple') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getIsSimpleExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereIsSimple(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_IsSimple.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getIsSimpleExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }


    /*
     * ST_M
     */

    public function selectM(): \Closure
    {
        /**
         * Return the M coordinate of a Point, or NULL if not available. Input must be a Point.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_M.html
         */
        return function ($geometry, string $as = 'm') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getMExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereM(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_M.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getMExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByM(): \Closure
    {
        /**
         * Orders by the M coordinate of a Point, or NULL if not available. Input must be a Point.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_M.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getMExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }


    /*
     * ST_MemSize
     */

    public function selectMemSize(): \Closure
    {
        /**
         * Returns the amount of memory space (in bytes) the geometry takes.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_MemSize.html
         */
        return function ($geometry, string $as = 'memSize') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getMemSizeExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereMemSize(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_MemSize.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getMemSizeExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByMemSize(): \Closure
    {
        /**
         * Orders by the amount of memory space (in bytes) the geometry takes.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_MemSize.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getMemSizeExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_NDims
     */

    public function selectNDims(): \Closure
    {
        /**
         * Returns the coordinate dimension of the geometry. PostGIS supports 2 - (x,y) , 3 - (x,y,z) or 2D with measure - x,y,m, and 4 - 3D with measure space x,y,z,m
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NDims.html
         */
        return function ($geometry, string $as = 'nDims') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getNDimsExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereNDims(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NDims.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getNDimsExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByNDims(): \Closure
    {
        /**
         * Orders by the coordinate dimension of the geometry. PostGIS supports 2 - (x,y) , 3 - (x,y,z) or 2D with measure - x,y,m, and 4 - 3D with measure space x,y,z,m
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NDims.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getNDimsExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_NPoints
     */

    public function selectNPoints(): \Closure
    {
        /**
         * Return the number of points in a geometry. Works for all geometries.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NPoints.html
         */
        return function ($geometry, string $as = 'nPoints') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getNPointsExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereNPoints(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NPoints.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getNPointsExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByNPoints(): \Closure
    {
        /**
         * Orders by the number of points in a geometry. Works for all geometries.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NPoints.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getNPointsExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }
    /*
     * ST_NRings
     */

    public function selectNRings(): \Closure
    {
        /**
         * If the geometry is a polygon or multi-polygon returns the number of rings. Unlike NumInteriorRings, it counts the outer rings as well.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NRings.html
         */
        return function ($geometry, string $as = 'nRings') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getNRingsExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereNRings(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NRings.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getNRingsExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByNRings(): \Closure
    {
        /**
         * Orders by the number of rings. Unlike NumInteriorRings, it counts the outer rings as well.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NRings.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getNRingsExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_NumGeometries
     */

    public function selectNumGeometries(): \Closure
    {
        /**
         * Returns the number of Geometries. If geometry is a GEOMETRYCOLLECTION (or MULTI*) return the number of geometries, for single geometries will return 1, otherwise return NULL.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumGeometries.html
         */
        return function ($geometry, string $as = 'numGeometries') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getNumGeometriesExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereNumGeometries(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumGeometries.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getNumGeometriesExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByNumGeometries(): \Closure
    {
        /**
         * Orders by the number of Geometries. If geometry is a GEOMETRYCOLLECTION (or MULTI*) return the number of geometries, for single geometries will return 1, otherwise return NULL.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumGeometries.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getNumGeometriesExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_NumInteriorRings
     */

    public function selectNumInteriorRings(): \Closure
    {
        /**
         * Return the number of interior rings of a polygon geometry. Return NULL if the geometry is not a polygon.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumInteriorRings.html
         */
        return function ($geometry, string $as = 'numInterirorRings') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getNumInteriorRingsExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereNumInteriorRings(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumInteriorRings.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getNumInteriorRingsExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByNumInteriorRings(): \Closure
    {
        /**
         * Orders by the number of interior rings of a polygon geometry. Return NULL if the geometry is not a polygon.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumInteriorRings.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getNumInteriorRingsExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_NumPatches
     */

    public function selectNumPatches(): \Closure
    {
        /**
         * Return the number of faces on a Polyhedral Surface. Will return null for non-polyhedral geometries. This is an alias for ST_NumGeometries to support MM naming. Faster to use ST_NumGeometries if you don't care about MM convention.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumPatches.html
         */
        return function ($geometry, string $as = 'numPatches') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getNumPatchesExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereNumPatches(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumPatches.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getNumPatchesExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByNumPatches(): \Closure
    {
        /**
         * Orders by the number of faces on a Polyhedral Surface. Will return null for non-polyhedral geometries. This is an alias for ST_NumGeometries to support MM naming. Faster to use ST_NumGeometries if you don't care about MM convention.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_NumPatches.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getNumPatchesExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_PatchN
     */

    public function selectPatchN(): \Closure
    {
        /**
         * Returns the 1-based Nth geometry (face) if the geometry is a POLYHEDRALSURFACE or POLYHEDRALSURFACEM. Otherwise, returns NULL. This returns the same answer as ST_GeometryN for PolyhedralSurfaces. Using ST_GeometryN is faster.
         *
         * @param $geometry
         * @param int $n
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_PatchN.html
         */
        return function ($geometry, int $n, string $as = 'patchN') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getPatchNExpression($this, 'select', $geometry, $n, $as)
            );
        };
    }

    /*
     * ST_PointN
     */

    public function selectPointN(): \Closure
    {
        /**
         * Return the Nth point in a single linestring or circular linestring in the geometry. Negative values are counted backwards from the end of the LineString, so that -1 is the last point. Returns NULL if there is no linestring in the geometry.
         *
         * @param $geometry
         * @param int $n
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_PointN.html
         */
        return function ($geometry, int $n, string $as = 'pointN') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getPointNExpression($this, 'select', $geometry, $n, $as)
            );
        };
    }

    /*
     * ST_Points
     */

    public function selectPoints(): \Closure
    {
        /**
         * Returns a MultiPoint containing all the coordinates of a geometry. Duplicate points are preserved, including the start and end points of ring geometries. (If desired, duplicate points can be removed by calling ST_RemoveRepeatedPoints on the result).
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Points.html
         */
        return function ($geometry, string $as = 'points') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getPointsExpression($this, 'select', $geometry, $as)
            );
        };
    }

    /*
     * ST_StartPoint
     */

    public function selectStartPoint(): \Closure
    {
        /**
         * Returns the first point of a LINESTRING or CIRCULARLINESTRING geometry as a POINT. Returns NULL if the input is not a LINESTRING or CIRCULARLINESTRING.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_StartPoint.html
         */
        return function ($geometry, string $as = 'startPoint') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getStartPointExpression($this, 'select', $geometry, $as)
            );
        };
    }

    /*
     * ST_Summary
     */

    public function selectSummary(): \Closure
    {
        /**
         * Returns a text summary of the contents of the geometry.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Summary.html
         */
        return function ($geometry, string $as = 'summary') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getSummaryExpression($this, 'select', $geometry, $as)
            );
        };
    }


    /*
     * ST_X
     */

    public function selectX(): \Closure
    {
        /**
         * Return the X coordinate of the point, or NULL if not available. Input must be a point.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_X.html
         */
        return function ($geometry, string $as = 'x') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getXExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereX(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_X.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getXExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByX(): \Closure
    {
        /**
         * Orders by the X coordinate of the point, or NULL if not available. Input must be a point.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_X.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getXExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_Y
     */

    public function selectY(): \Closure
    {
        /**
         * Return the Y coordinate of the point, or NULL if not available. Input must be a point.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Y.html
         */
        return function ($geometry, string $as = 'y') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getYExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereY(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Y.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getYExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByY(): \Closure
    {
        /**
         * Orders by the Y coordinate of the point, or NULL if not available. Input must be a point.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Y.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getYExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_Z
     */

    public function selectZ(): \Closure
    {
        /**
         * Return the Z coordinate of the point, or NULL if not available. Input must be a point.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Z.html
         */
        return function ($geometry, string $as = 'z') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getZExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereZ(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Z.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getZExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByZ(): \Closure
    {
        /**
         * Orders by the Z coordinate of the point, or NULL if not available. Input must be a point.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Z.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getZExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

    /*
     * ST_Zmflag
     */

    public function selectZmflag(): \Closure
    {
        /**
         * Returns a code indicating the ZM coordinate dimension of a geometry.
         * Values are: 0 = 2D, 1 = 3D-M, 2 = 3D-Z, 3 = 4D.
         *
         * @param $geometry
         * @param string $as
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Zmflag.html
         */
        return function ($geometry, string $as = 'z') {
            return $this->addSelect(
                PostgisGeometryAccessorExpressions::getZmflagExpression($this, 'select', $geometry, $as)
            );
        };
    }

    public function whereZmflag(): \Closure
    {
        /**
         *
         * @param $geometry
         * @param null $operator
         * @param null $value
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Zmflag.html
         */
        return function ($geometry, $operator = null, $value = null) {
            return $this->where(
                PostgisGeometryAccessorExpressions::getZmflagExpression($this, 'where', $geometry, null),
                $operator,
                $value,
            );
        };
    }

    public function orderByZmflag(): \Closure
    {
        /**
         * Orders by a code indicating the ZM coordinate dimension of a geometry.
         * Values are: 0 = 2D, 1 = 3D-M, 2 = 3D-Z, 3 = 4D.
         *
         * @param $geometry
         * @param string $direction
         * @return PostgisOverlayMacros
         *
         * @see https://postgis.net/docs/ST_Zmflag.html
         */
        return function ($geometry, string $direction = 'ASC') {
            return $this->orderBy(
                PostgisGeometryAccessorExpressions::getZmflagExpression($this, 'order', $geometry, null),
                $direction,
            );
        };
    }

}
