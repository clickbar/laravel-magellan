<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use RuntimeException;

trait MagellanBoundingBoxExpressions
{
    /**
     * Returns a box2d representing the 2D extent of the geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/Box2D.html
     */
    public static function box2D($geometry): MagellanExpression
    {
        return MagellanExpression::bbox('Box2D', [$geometry]);
    }

    /**
     * Returns a box3d representing the 3D extent of the geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/Box3D.html
     */
    public static function box3D($geometry): MagellanExpression
    {
        return MagellanExpression::bbox('Box3D', [$geometry]);
    }

    /**
     * An aggregate function that returns a box2d bounding box that bounds a set of geometries.
     * The bounding box coordinates are in the spatial reference system of the input geometries.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_Extent.html
     */
    public static function extent($geometry): MagellanExpression
    {
        return MagellanExpression::bbox('ST_Extent', [$geometry]);
    }

    /**
     * An aggregate function that returns a box3d (includes Z ordinate) bounding box that bounds a set of geometries.
     * The bounding box coordinates are in the spatial reference system of the input geometries.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_3DExtent.html
     */
    public static function extent3D($geometry): MagellanExpression
    {
        return MagellanExpression::bbox('ST_3DExtent', [$geometry]);
    }

    /**
     * Creates a box2d defined by two Point geometries. This is useful for doing range queries.
     *
     * @param $pointLowLeft
     * @param $pointUpRight
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_MakeBox2D.html
     */
    public static function makeBox2D($pointLowLeft, $pointUpRight): MagellanExpression
    {
        return MagellanExpression::bbox('ST_MakeBox2D', [$pointLowLeft, $pointUpRight]);
    }

    /**
     * Creates a box3d defined by two 3D Point geometries.
     *
     * @param $pointLowLeft
     * @param $pointUpRight
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_3DMakeBox.html
     */
    public static function makeBox3D($pointLowLeft, $pointUpRight): MagellanExpression
    {
        return MagellanExpression::bbox('ST_3DMakeBox', [$pointLowLeft, $pointUpRight]);
    }

    /**
     * Returns the X maxima of a 2D or 3D bounding box or a geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_XMax.html
     */
    public static function xMax($geometry): MagellanExpression
    {
        return MagellanExpression::numeric('ST_XMax', [$geometry]);
    }

    /**
     * Returns the X minima of a 2D or 3D bounding box or a geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_XMin.html
     */
    public static function xMin($geometry): MagellanExpression
    {
        return MagellanExpression::numeric('ST_XMin', [$geometry]);
    }

    /**
     * Returns the Y maxima of a 2D or 3D bounding box or a geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_YMax.html
     */
    public static function yMax($geometry): MagellanExpression
    {
        return MagellanExpression::numeric('ST_YMax', [$geometry]);
    }

    /**
     * Returns the Y minima of a 2D or 3D bounding box or a geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_YMin.html
     */
    public static function yMin($geometry): MagellanExpression
    {
        return MagellanExpression::numeric('ST_YMin', [$geometry]);
    }

    /**
     * Returns the Z maxima of a 2D or 3D bounding box or a geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_ZMax.html
     */
    public static function zMax($geometry): MagellanExpression
    {
        return MagellanExpression::numeric('ST_ZMax', [$geometry]);
    }

    /**
     * Returns the Z minima of a 2D or 3D bounding box or a geometry.
     *
     * @param $geometry
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_ZMin.html
     */
    public static function zMin($geometry): MagellanExpression
    {
        return MagellanExpression::numeric('ST_ZMin', [$geometry]);
    }

    /**
     * Returns a bounding box expanded from the bounding box of the input, either by specifying a single distance with which the box should be expanded on both axes, or by specifying an expansion distance for each axis. Uses double-precision. Can be used for distance queries, or to add a bounding box filter to a query to take advantage of a spatial index.
     *
     * @param $geometry
     * @param  float|null  $unitsToExpand
     * @param  float|null  $dx
     * @param  float|null  $dy
     * @param  float|null  $dz
     * @param  float|null  $dm
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_Expand.html
     */
    public static function expand($geometry, ?float $unitsToExpand = null, ?float $dx = null, ?float $dy = null, ?float $dz = null, ?float $dm = null): MagellanExpression
    {
        if ($unitsToExpand !== null) {
            return MagellanExpression::geometryOrBox('ST_Expand', [$geometry], [$unitsToExpand]);
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

        return MagellanExpression::numeric('ST_Expand', [$geometry], $nonNullArguments);
    }

    /**
     * Returns the estimated extent of a spatial table as a box2d. The current schema is used if not specified. The estimated extent is taken from the geometry column's statistics. This is usually much faster than computing the exact extent of the table using ST_Extent or ST_3DExtent.
     *
     * @param  string  $tableName
     * @param  string  $geoColumn
     * @param  string|null  $schemaName
     * @param  bool|null  $parentOnly
     * @return MagellanExpression
     *
     * @see https://postgis.net/docs/ST_EstimatedExtent.html
     */
    public static function estimatedExtent(string $tableName, string $geoColumn, ?string $schemaName = null, ?bool $parentOnly = null): MagellanExpression
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

        return MagellanExpression::bbox('ST_EstimatedExtent', [], $arguments);
    }
}
