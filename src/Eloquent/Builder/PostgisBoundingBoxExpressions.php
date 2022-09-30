<?php

namespace Clickbar\Magellan\Eloquent\Builder;

class PostgisBoundingBoxExpressions
{
    public static function getBox2DExpression($builder, string $bindingType, $a, string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'Box2D', $as, $a);
    }

    public static function getBox3DExpression($builder, string $bindingType, $a, string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'Box3D', $as, $a);
    }

    public static function getExtentExpression($builder, string $bindingType, $a, string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'st_extent', $as, $a);
    }

    public static function getMakeBox2DExpression($builder, string $bindingType, $a, $b, string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_MakeBox2D', $as, $a, $b);
    }
}
