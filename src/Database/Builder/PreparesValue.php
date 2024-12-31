<?php

namespace Clickbar\Magellan\Database\Builder;

use Clickbar\Magellan\Database\MagellanExpressions\ColumnParameter;
use Closure;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

trait PreparesValue
{
    public function prepareParam(Grammar $grammar, mixed $param): string
    {

        // 1. Check if param is queryable -> it's a subquery
        if ($this->isQueryable($param)) {
            // --> Create sub and replace with param array
            return $this->createSub($param);
        }

        // 2. Basic Binding Value
        if ($param instanceof ValueParameter) {
            // --> escape and replace
            return $grammar->escape($param->getValue());
        }

        // 3. expression
        if ($param instanceof Expression) {
            return $param->getValue($grammar);
        }

        // 4. Column Parameter
        if ($param instanceof ColumnParameter) {
            return $grammar->wrap($param->getValue());
        }

        // 5. string / default
        return $grammar->wrap($param);
    }

    private function createSub($query): string
    {
        if ($query instanceof Closure) {
            $callback = $query;
            $callback($query = DB::query());
        }

        return "({$query->toRawSql()})";
    }

    private function isQueryable($value): bool
    {
        return $value instanceof Builder ||
            $value instanceof EloquentBuilder ||
            $value instanceof Relation ||
            $value instanceof Closure;
    }
}
