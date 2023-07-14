<?php

namespace Clickbar\Magellan\Cast;

use Clickbar\Magellan\Data\Geometries\Geometry;
use Clickbar\Magellan\IO\Generator\WKB\WKBGenerator;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Facades\App;

class GeometryCast implements CastsAttributes
{
    protected WKBParser $wkbParser;

    protected WKBGenerator $sqlGenerator;

    public function __construct()
    {
        $this->wkbParser = App::make(WKBParser::class);
        $this->sqlGenerator = App::make(WKBGenerator::class);
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
     */
    public function set($model, string $key, mixed $value, array $attributes)
    {
        if ($value instanceof Geometry) {
            return $this->sqlGenerator->generate($value);
        }

        return $value;
    }
}
