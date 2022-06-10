<?php

namespace Clickbar\Postgis\Eloquent;

use Clickbar\Postgis\Exception\PostgisColumnsNotDefinedException;
use Illuminate\Support\Arr;

trait HasPostgisColumns
{
    public function getPostgisTypeAndSrid(string $key)
    {
        $this->assertKeyIsInPostgisColumns($key);

        $default = [
            'geomtype' => config('postgis.eloquent.default_postgis_type'),
            'srid' => config('postgis.eloquent.default_srid'),
        ];

        return Arr::get($this->postgisColumns, $key, $default);
    }

    public function getPostgisColumnNames()
    {
        $this->assertPostgisColumnsNotEmpty();

        return array_map(function ($key) {
            if (is_int($key)) {
                return $this->postgisColumns[$key];
            }

            return $key;
        }, array_keys($this->postgisColumns));
    }

    protected function assertPostgisColumnsNotEmpty()
    {
        if (! property_exists($this, 'postgisColumns')) {
            throw new PostgisColumnsNotDefinedException(__CLASS__ . ' has not defined any postgis columns within the $postgisColumns property.');
        }
    }

    protected function assertKeyIsInPostgisColumns(string $key)
    {
        if (! in_array($key, $this->getPostgisColumnNames())) {
            throw new PostgisColumnsNotDefinedException(__CLASS__ . " has not defined the column '$key' within the \$postgisColumns property.");
        }
    }
}
