<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBBoxExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryOrBboxExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanNumericExpression;
use Illuminate\Database\Query\Expression;
use RuntimeException;

trait MagellanBoundingBoxFunctions
{
    /**
     * Returns a box2d representing the 2D extent of the geometry.
     *
     *
     * @see https://postgis.net/docs/Box2D.html
     */
    public static function box2D($geometry): MagellanBBoxExpression
    {
        return MagellanBaseExpression::bbox('Box2D', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns a box3d representing the 3D extent of the geometry.
     *
     *
     * @see https://postgis.net/docs/Box3D.html
     */
    public static function box3D($geometry): MagellanBBoxExpression
    {
        return MagellanBaseExpression::bbox('Box3D', [GeoParam::wrap($geometry)]);
    }

    /**
     * An aggregate function that returns a box2d bounding box that bounds a set of geometries.
     * The bounding box coordinates are in the spatial reference system of the input geometries.
     *
     * @return MagellanBBoxExpression
     *
     * @see https://postgis.net/docs/ST_Extent.html
     */
    public static function extent($geometry): MagellanBaseExpression
    {
        return MagellanBaseExpression::bbox('ST_Extent', [GeoParam::wrap($geometry)]);
    }

    /**
     * An aggregate function that returns a box3d (includes Z ordinate) bounding box that bounds a set of geometries.
     * The bounding box coordinates are in the spatial reference system of the input geometries.
     *
     *
     * @see https://postgis.net/docs/ST_3DExtent.html
     */
    public static function extent3D($geometry): MagellanBBoxExpression
    {
        return MagellanBaseExpression::bbox('ST_3DExtent', [GeoParam::wrap($geometry)]);
    }

    /**
     * Creates a box2d defined by two Point geometries. This is useful for doing range queries.
     *
     *
     * @see https://postgis.net/docs/ST_MakeBox2D.html
     */
    public static function makeBox2D($pointLowLeft, $pointUpRight): MagellanBBoxExpression
    {
        return MagellanBaseExpression::bbox('ST_MakeBox2D', [GeoParam::wrap($pointLowLeft), GeoParam::wrap($pointUpRight)]);
    }

    /**
     * Creates a box3d defined by two 3D Point geometries.
     *
     *
     * @see https://postgis.net/docs/ST_3DMakeBox.html
     */
    public static function makeBox3D($pointLowLeft, $pointUpRight): MagellanBBoxExpression
    {
        return MagellanBaseExpression::bbox('ST_3DMakeBox', [GeoParam::wrap($pointLowLeft), GeoParam::wrap($pointUpRight)]);
    }

    /**
     * Returns the X maxima of a 2D or 3D bounding box or a geometry.
     *
     *
     * @see https://postgis.net/docs/ST_XMax.html
     */
    public static function xMax($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_XMax', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the X minima of a 2D or 3D bounding box or a geometry.
     *
     *
     * @see https://postgis.net/docs/ST_XMin.html
     */
    public static function xMin($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_XMin', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the Y maxima of a 2D or 3D bounding box or a geometry.
     *
     *
     * @see https://postgis.net/docs/ST_YMax.html
     */
    public static function yMax($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_YMax', [GeoParam::wrap($geometry)]);
    }

    /**
     * Returns the Y minima of a 2D or 3D bounding box or a geometry.
     *
     *
     * @see https://postgis.net/docs/ST_YMin.html
     */
    public static function yMin($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_YMin', [$geometry]);
    }

    /**
     * Returns the Z maxima of a 2D or 3D bounding box or a geometry.
     *
     *
     * @see https://postgis.net/docs/ST_ZMax.html
     */
    public static function zMax($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_ZMax', [$geometry]);
    }

    /**
     * Returns the Z minima of a 2D or 3D bounding box or a geometry.
     *
     *
     * @see https://postgis.net/docs/ST_ZMin.html
     */
    public static function zMin($geometry): MagellanNumericExpression
    {
        return MagellanBaseExpression::numeric('ST_ZMin', [$geometry]);
    }

    /**
     * Returns a bounding box expanded from the bounding box of the input, either by specifying a single distance with which the box should be expanded on both axes, or by specifying an expansion distance for each axis. Uses double-precision. Can be used for distance queries, or to add a bounding box filter to a query to take advantage of a spatial index.
     *
     *
     * @see https://postgis.net/docs/ST_Expand.html
     */
    public static function expand($geometry, float|Expression|\Closure|null $unitsToExpand = null, float|Expression|\Closure|null $dx = null, float|Expression|\Closure|null $dy = null, float|Expression|\Closure|null $dz = null, float|Expression|\Closure|null $dm = null): MagellanGeometryOrBboxExpression
    {
        if ($unitsToExpand !== null) {
            return MagellanBaseExpression::geometryOrBox('ST_Expand', [GeoParam::wrap($geometry), $unitsToExpand]);
        }

        if ($dy !== null) {
            $dx = $dx ?? 0;
        }

        if ($dz !== null) {
            $dx = $dx ?? 0;
            $dy = $dy ?? 0;
        }

        if ($dm !== null) {
            $dx = $dx ?? 0;
            $dy = $dy ?? 0;
            $dz = $dz ?? 0;
        }

        $nonNullArguments = collect([$dx, $dy, $dz, $dm])
            ->filter(fn ($x) => $x !== null)
            ->toArray();

        if (count($nonNullArguments) === 1) {
            $nonNullArguments[] = 0;
        }

        return MagellanBaseExpression::geometryOrBox('ST_Expand', [GeoParam::wrap($geometry), ...$nonNullArguments]);
    }

    /**
     * Returns the estimated extent of a spatial table as a box2d. The current schema is used if not specified. The estimated extent is taken from the geometry column's statistics. This is usually much faster than computing the exact extent of the table using ST_Extent or ST_3DExtent.
     *
     *
     * @see https://postgis.net/docs/ST_EstimatedExtent.html
     */
    public static function estimatedExtent(string|Expression|\Closure $tableName, string|Expression|\Closure $geoColumn, string|Expression|\Closure|null $schemaName = null, bool|Expression|\Closure|null $parentOnly = null): MagellanBBoxExpression
    {
        $arguments = [
            $tableName,
            $geoColumn,
        ];

        if ($schemaName !== null) {
            array_unshift($arguments, $schemaName);
        }

        if ($parentOnly !== null) {
            if (count($arguments) !== 3) {
                // TODO: Create Custom Exception
                throw new RuntimeException('Invalid combination of parameters. See documentation for proper use of ST_EstimatedExtent');
            }

            $arguments[] = $parentOnly;
        }

        return MagellanBaseExpression::bbox('ST_EstimatedExtent', $arguments);
    }
}
