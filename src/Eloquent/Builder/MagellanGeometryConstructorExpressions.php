<?php

namespace Clickbar\Magellan\Eloquent\Builder;

use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanBaseExpression;
use Clickbar\Magellan\Eloquent\Builder\MagellanExpressions\MagellanGeometryExpression;

trait MagellanGeometryConstructorExpressions
{
    /**
     * Collects geometries into a geometry collection. The result is either a Multi* or a GeometryCollection, depending on whether the input geometries have the same or different types (homogeneous or heterogeneous). The input geometries are left unchanged within the collection.
     *
     * @param $geometry1
     * @param $geometry2
     * @return MagellanGeometryExpression
     *
     * @see https://postgis.net/docs/ST_Collect.html
     */
    public static function collectFromGeometries($geometry1, $geometry2): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Collect', [$geometry1, $geometry2]);
    }

    /**
     * Collects geometries into a geometry collection. The result is either a Multi* or a GeometryCollection, depending on whether the input geometries have the same or different types (homogeneous or heterogeneous). The input geometries are left unchanged within the collection.
     *
     * @param $geometryArrayOrSet
     * @return MagellanGeometryExpression
     *
     * @see https://postgis.net/docs/ST_Collect.html
     */
    public static function collect($geometryArrayOrSet): MagellanGeometryExpression
    {
        return MagellanBaseExpression::geometry('ST_Collect', [$geometryArrayOrSet]);
    }
}
