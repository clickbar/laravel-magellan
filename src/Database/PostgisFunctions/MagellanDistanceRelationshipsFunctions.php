<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBooleanExpression;
use Clickbar\Magellan\Enums\GeometryType;
use Illuminate\Database\Query\Expression;

trait MagellanDistanceRelationshipsFunctions
{
    /**
     * Returns true if the 3D distance between two geometry values is no larger than distance distance_of_srid. The distance is specified in units defined by the spatial reference system of the geometries. For this function to make sense the source geometries must be in the same coordinate system (have the same SRID).
     *
     * @see https://postgis.net/docs/ST_3DDWithin.html
     */
    public static function dWithin3D($geometryA, $geometryB, float|Expression|\Closure $distanceOfSrid): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_3DDWithin', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $distanceOfSrid]);
    }

    /**
     * Returns true if the 3D geometries are fully within the specified distance of one another. The distance is specified in units defined by the spatial reference system of the geometries. For this function to make sense, the source geometries must both be of the same coordinate projection, having the same SRID.
     *
     * @see https://postgis.net/docs/ST_3DDFullyWithin.html
     */
    public static function dFullyWithin3D($geometryA, $geometryB, float|Expression|\Closure $distance): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_3DDFullyWithin', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $distance]);
    }

    /**
     * Returns true if the geometries are entirely within the specified distance of one another. The distance is specified in units defined by the spatial reference system of the geometries. For this function to make sense, the source geometries must both be of the same coordinate projection, having the same SRID.
     *
     * @see https://postgis.net/docs/ST_DFullyWithin.html
     */
    public static function dFullyWithin($geometryA, $geometryB, float|Expression|\Closure $distance): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_DFullyWithin', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $distance]);
    }

    /**
     * Returns true if the geometries are within a given distance
     *
     * The distance is specified in units defined by the spatial reference system of the geometries. For this function to make sense, the source geometries must be in the same coordinate system (have the same SRID).
     *
     * @see https://postgis.net/docs/ST_DWithin.html
     */
    public static function dWithinGeometry($geometryA, $geometryB, float|Expression|\Closure $distanceOfSrid): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_DWithin', [GeoParam::wrap($geometryA), GeoParam::wrap($geometryB), $distanceOfSrid]);
    }

    /**
     * Returns true if the geometries are within a given distance
     *
     * Units are in meters and distance measurement defaults to use_spheroid=true. For faster evaluation use use_spheroid=false to measure on the sphere.
     *
     * @see https://postgis.net/docs/ST_DWithin.html
     */
    public static function dWithinGeography($geographyA, $geographyB, float|Expression|\Closure $distanceMeters, bool|Expression|\Closure|null $useSpheroid = null): MagellanBooleanExpression
    {
        $useSpheroid = $useSpheroid ?? true;

        return MagellanBaseExpression::boolean('ST_DWithin', [GeoParam::wrap($geographyA), GeoParam::wrap($geographyB), $distanceMeters, $useSpheroid], GeometryType::Geography);
    }

    /**
     * Returns true if the geometry is a point and is inside the circle with center center_x,center_y and radius radius.
     *
     * @note Does not use spatial indexes. Use ST_DWithin instead.
     *
     * @see https://postgis.net/docs/ST_PointInsideCircle.html
     */
    public static function pointInsideCircle($point, float|Expression|\Closure $centerX, float|Expression|\Closure $centerY, float|Expression|\Closure $radius): MagellanBooleanExpression
    {
        return MagellanBaseExpression::boolean('ST_PointInsideCircle', [GeoParam::wrap($point), $centerX, $centerY, $radius]);
    }
}
