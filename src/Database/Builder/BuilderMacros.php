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

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

/**
 * @mixin \Illuminate\Database\Query\Builder
 */
class BuilderMacros
{
    public function toGeojsonFeatureCollection(): Closure
    {
        return function (): string {
            // Create a fresh query, on the same connection, grammar and processor as the original query
            // @phpstan-ignore-next-line
            $freshQuery = ($this instanceof EloquentBuilder) ? $this->toBase()->newQuery() : $this->newQuery();

            return $freshQuery
                ->selectRaw("json_build_object('type', 'FeatureCollection', 'features', COALESCE(json_agg(ST_AsGeoJSON(f.*)::json), ('[]')::json)) AS geojson")
                ->fromSub($this, 'f')
                ->first()
                ->geojson;
        };
    }
}
