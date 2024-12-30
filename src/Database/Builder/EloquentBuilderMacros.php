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

use Clickbar\Magellan\Cast\BBoxCast;
use Clickbar\Magellan\Cast\GeometryCast;
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
        /** @var EloquentBuilder $this */

        return function (): EloquentBuilder {

            // Loop over all columns and add cast if the column is an aliased magellan expression
            $columns = $this->toBase()->columns ?? [];
            foreach ($columns as $column) {

                /** @var string|null $alias */
                $alias = null;
                /** @var MagellanBaseExpression|null $magellanExpression */
                $magellanExpression = null;

                if ($column instanceof Aliased && $column->expression instanceof MagellanBaseExpression) {
                    $alias = $column->alias;
                    $magellanExpression = $column->expression;
                } elseif ($column instanceof MagellanBaseExpression) {
                    $alias = strtolower($column->postgisFunction);
                    $magellanExpression = $column;
                }

                if ($magellanExpression?->returnsBbox()) {
                    $this->withCasts([$alias => BBoxCast::class]);
                }

                if ($magellanExpression?->returnsGeometry()) {
                    $this->withCasts([$alias => GeometryCast::class]);
                }
            }

            return $this;
        };
    }
}
