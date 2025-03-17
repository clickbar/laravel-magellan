<?php

/**
 * Disabled for now, to mitigate issues with the Laravel IDE Helper Generator.
 * PLEASE keep parameter annotations in here with their fully qualified class names, so that the IDE Helper Generator
 * can pick them up properly.
 * See https://github.com/barryvdh/laravel-ide-helper/pull/953
 *
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */

namespace Clickbar\Magellan\Database\Builder;

use Clickbar\Magellan\Data\Boxes\Box;
use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Database\Expressions\Aliased;
use Clickbar\Magellan\Database\MagellanExpressions\MagellanBaseExpression;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EloquentBuilderMacros
{
    public function withMagellanCasts(): \Closure
    {
        return function (): EloquentBuilder {

            // Loop over all columns and add cast if the column is an aliased magellan expression
            $columns = $this->toBase()->columns ?? [];
            foreach ($columns as $column) {

                /** @var string|null $as */
                $as = null;
                /** @var MagellanBaseExpression|null $magellanExpression */
                $magellanExpression = null;

                if ($column instanceof Aliased && $column->expression instanceof MagellanBaseExpression) {
                    $as = $column->as;
                    $magellanExpression = $column->expression;
                } elseif ($column instanceof MagellanBaseExpression) {
                    $as = strtolower($column->postgisFunction);
                    $magellanExpression = $column;
                }

                if ($magellanExpression?->returnsBbox()) {
                    $this->withCasts([$as => Box::class]);
                }

                if ($magellanExpression?->returnsGeometry()) {
                    $this->withCasts([$as => Geometry::class]);
                }
            }

            /**
             * @var EloquentBuilder $this
             *
             * @phpstan-ignore varTag.nativeType
             */
            return $this;
        };
    }
}
