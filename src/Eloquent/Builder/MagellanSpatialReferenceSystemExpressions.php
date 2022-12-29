<?php

namespace Clickbar\Magellan\Eloquent\Builder;

trait MagellanSpatialReferenceSystemExpressions
{
    /**
     * Sets the SRID on a geometry to a particular integer value. Useful in constructing bounding boxes for queries.
     *
     * @param $geometry
     * @param  int  $srid
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_SetSRID.html
     */
    public static function setSrid($geometry, int $srid): MagellanExpression
    {
        return MagellanExpression::geometry('ST_SetSRID', [$geometry], [$srid]);
    }

    /**
     * Returns the spatial reference identifier for the ST_Geometry as defined in spatial_ref_sys table. Section 4.5, “Spatial Reference Systems”
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_SRID.html
     */
    public static function srid($geometry): MagellanExpression
    {
        return MagellanExpression::numeric('ST_SRID', [$geometry]);
    }

    /**
     * Returns a new geometry with its coordinates transformed to a different spatial reference system. The destination spatial reference to_srid may be identified by a valid SRID integer parameter (i.e. it must exist in the spatial_ref_sys table). Alternatively, a spatial reference defined as a PROJ.4 string can be used for to_proj and/or from_proj, however these methods are not optimized. If the destination spatial reference system is expressed with a PROJ.4 string instead of an SRID, the SRID of the output geometry will be set to zero. With the exception of functions with from_proj, input geometries must have a defined SRID.
     * ST_Transform is often confused with ST_SetSRID. ST_Transform actually changes the coordinates of a geometry from one spatial reference system to another, while ST_SetSRID() simply changes the SRID identifier of the geometry.
     *
     * Possible Calls:
     * - geometry ST_Transform(geometry g1, integer srid);
     * - geometry ST_Transform(geometry geom, text to_proj);
     * - geometry ST_Transform(geometry geom, text from_proj, text to_proj);
     * - geometry ST_Transform(geometry geom, text from_proj, integer to_srid);
     *
     * @param $geometry
     * @param  int|null  $srid
     * @param  string|null  $fromProjection
     * @param  string|null  $toProjection
     * @param  int|null  $toSrid
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_Transform.html
     */
    public static function transform($geometry, ?int $srid = null, ?string $fromProjection = null, ?string $toProjection = null, ?int $toSrid = null): MagellanExpression
    {
        // TODO: Consider throwing exception when the overloading does not suite the available possibilitirs:
        return MagellanExpression::geometry('ST_Transform', [$geometry], [$srid, $fromProjection, $toProjection, $toSrid]);
    }
}
