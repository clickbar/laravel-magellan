<?php

namespace Clickbar\Magellan\Database\PostgisFunctions;

use Clickbar\Magellan\Database\MagellanExpressions\GeoParam;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanGeometryExpression;

trait MagellanGeometryConstructorFunctions
{
    /**
     * Collects geometries into a geometry collection. The result is either a Multi* or a GeometryCollection, depending on whether the input geometries have the same or different types (homogeneous or heterogeneous). The input geometries are left unchanged within the collection.
     *
     *
     * @see https://postgis.net/docs/ST_Collect.html
     */
    public static function collectFromGeometries($geometry1, $geometry2): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Collect', [GeoParam::wrap($geometry1),  GeoParam::wrap($geometry2)]);
    }

    /**
     * Collects geometries into a geometry collection. The result is either a Multi* or a GeometryCollection, depending on whether the input geometries have the same or different types (homogeneous or heterogeneous). The input geometries are left unchanged within the collection.
     *
     *
     * @see https://postgis.net/docs/ST_Collect.html
     */
    public static function collect($geometryArrayOrSet): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Collect', [GeoParam::wrap($geometryArrayOrSet)]);
    }
}
