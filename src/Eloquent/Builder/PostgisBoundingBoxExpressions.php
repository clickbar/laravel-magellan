<?php

namespace Clickbar\Magellan\Eloquent\Builder;

class PostgisBoundingBoxExpressions
{
    public static function getBox2DExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'Box2D', $as, $a);
    }

    public static function getBox3DExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'Box3D', $as, $a);
    }

    public static function getExtentExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_Extent', $as, $a);
    }

    public static function get3DExtentExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DExtent', $as, $a);
    }

    public static function getMakeBox2DExpression($builder, string $bindingType, $a, $b, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_MakeBox2D', $as, $a, $b);
    }

    public static function getMakeBox3DExpression($builder, string $bindingType, $a, $b, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_3DMakeBox', $as, $a, $b);
    }

    public static function getXMaxExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_XMax', $as, $a);
    }

    public static function getXMinExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_XMin', $as, $a);
    }

    public static function getYMaxExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_YMax', $as, $a);
    }

    public static function getYMinExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_YMin', $as, $a);
    }

    public static function getZMaxExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_ZMax', $as, $a);
    }

    public static function getZMinExpression($builder, string $bindingType, $a, ?string $as)
    {
        return $builder->buildPostgisFunction($bindingType, 'geometry', 'ST_ZMin', $as, $a);
    }

    public static function getExpandExpression($builder, string $bindingType, $a, ?string $as, ?float $unitsToExpand = null, ?float $dx = null, ?float $dy = null, ?float $dz = null, ?float $dm = null)
    {
        if ($unitsToExpand != null) {
            return $builder->buildPostgisFunction($bindingType, null, 'ST_Expand', $as, $a, new BindingExpression($unitsToExpand));
        }

        if ($dy != null) {
            $dx = $dx ?? 0;
        }

        if ($dz != null) {
            $dx = $dx ?? 0;
            $dy = $dy ?? 0;
        }

        if ($dm != null) {
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

        return $builder->buildPostgisFunction($bindingType, null, 'ST_Expand', $as, ...[$a, ...$nonNullArguments]);
    }

    public static function getEstimatedExtentExpression($builder, string $bindingType, ?string $as, string $tableName, string $geoColumn, ?string $schemaName = null, ?bool $parentOnly = null)
    {
        $arguments = [
            new BindingExpression($tableName),
            new BindingExpression($geoColumn),
        ];

        if ($schemaName != null) {
            array_unshift($arguments, new BindingExpression($schemaName));
        }

        if ($parentOnly != null) {
            if (count($arguments) !== 3) {
                // TODO: Create Custom Exception
                throw new \RuntimeException('Invalid combination of parameters. See documentation for proper use of ST_EstimatedExtent');
            }

            $arguments[] = new BindingExpression($parentOnly);
        }

        return $builder->buildPostgisFunction($bindingType, null, 'ST_EstimatedExtent', $as, ...$arguments);
    }
}
