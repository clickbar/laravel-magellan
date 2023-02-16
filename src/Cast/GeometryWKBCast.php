<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\Data\Geometries\GeometryFactory;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class GeometryWKBCast implements CastsAttributes
{
    protected WKBParser $wkbParser;

    public function __construct()
    {
        $factory = new GeometryFactory();
        $this->wkbParser = new WKBParser($factory);
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Clickbar\Magellan\Data\Geometries\Geometry|null
     */
    public function get($model, string $key, mixed $value, array $attributes)
    {
        // The geometry might be already casted on the lowest level if the alias hits an postgis field name
        if ($value instanceof Geometry) {
            return $value;
        }

        return isset($value) ? $this->wkbParser->parse($value) : null;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        return $value;
    }
}
