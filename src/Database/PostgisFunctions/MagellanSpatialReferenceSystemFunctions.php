<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\ColumnParameter;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\DB;

trait MagellanSpatialReferenceSystemFunctions
{
    /**
     * Sets the SRID on a geometry to a particular integer value. Useful in constructing bounding boxes for queries.
     *
     * NOTE: If you use an Expression or Closure for the SRID, you might need to add an `::int` cast.
     * See https://stackoverflow.com/questions/66625661/cannot-bind-value-as-int-with-pdo-pgsql-driver
     *
     * @see https://postgis.net/docs/ST_SetSRID.html
     */
    public static function setSrid($geometry, int|Expression|\Closure $srid): MagellanGeometryExpression
    {
        if (is_int($srid)) {
            $srid = DB::raw($srid.'::int');
        }

        return MagellanBaseExpression::geometry('ST_SetSRID', [ColumnParameter::wrap($geometry), $srid]);
    }

    /**
     * Returns the spatial reference identifier for the ST_Geometry as defined in spatial_ref_sys table. Section 4.5, “Spatial Reference Systems”
     *
     *
     * @see https://postgis.net/docs/ST_SRID.html
     */
    public static function srid($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_SRID', [ColumnParameter::wrap($geometry)]);
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
     * NOTE: If you use an Expression or Closure for the SRID, you might need to add an `::int` cast.
     * See https://stackoverflow.com/questions/66625661/cannot-bind-value-as-int-with-pdo-pgsql-driver
     *
     * @see https://postgis.net/docs/ST_Transform.html
     */
    public static function transform($geometry, int|Expression|\Closure|null $srid = null, string|Expression|\Closure|null $fromProjection = null, string|Expression|\Closure|null $toProjection = null, int|Expression|\Closure|null $toSrid = null): MagellanGeometryExpression
    {
        if ($srid === null && $toProjection === null && ($fromProjection === null || $toSrid === null)) {
            throw new \InvalidArgumentException('Invalid parameters: At least one valid parameter combination must be provided. See the DocBlock for possible calls.');
        }

        if (is_int($srid)) {
            $srid = DB::raw($srid.'::int');
        }

        return MagellanBaseExpression::geometry('ST_Transform', [ColumnParameter::wrap($geometry), $srid, $fromProjection, $toProjection, $toSrid]);
    }
}
