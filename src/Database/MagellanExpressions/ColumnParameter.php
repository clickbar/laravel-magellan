<?php

namespace Clickbar\Magellan\Database\MagellanExpressions;

/**
 * @internal
 *
 * Wrapper class for the parameters that represents a database column.
 * We need this wrapper to distinguish when generating the actual SQL of a MagellanBaseExpression.
 */
class ColumnParameter
{
    protected function __construct(
        protected readonly string $column,
    ) {
    }

    /**
     * Only wrap the given value into the ColumnParameter if it is string.
     *
     * @return self|mixed
     */
    public static function wrap(mixed $value): mixed
    {
        if (is_string($value)) {
            return new self($value);
        }

        return $value;
    }

    public function getValue(): string
    {
        return $this->column;
    }
}
