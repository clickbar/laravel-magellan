<?php

namespace Clickbar\Magellan\Eloquent;

use Clickbar\Magellan\Exception\PostgisColumnsNotDefinedException;
use Clickbar\Magellan\Exception\SridMissmatchException;
use Clickbar\Magellan\Geometries\Geometry;
use Clickbar\Magellan\Geometries\GeometryCollection;
use Clickbar\Magellan\IO\Generator\BaseGenerator;
use Clickbar\Magellan\IO\Parser\WKB\WKBParser;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

trait HasPostgisColumns
{
    public function getPostgisTypeAndSrid(string $key)
    {
        $this->assertKeyIsInPostgisColumns($key);

        $default = [
            'type' => Config::get('magellan.eloquent.default_postgis_type'),
            'srid' => Config::get('magellan.eloquent.default_srid'),
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

    private function getGenerator(): BaseGenerator
    {
        $generatorClass = Config::get('magellan.insert_generator');

        return new $generatorClass();
    }

    protected function geomFromText(Geometry $geometry, $srid = 4326)
    {
        $generator = $this->getGenerator();
        $geometrySql = $generator->toPostgisGeometrySql($geometry, Config::get('magellan.schema', 'public'));
        if ($geometry->hasSrid() && $geometry->getSrid() != $srid) {
            if (Config::get('magellan.transform_on_insert', false)) {
                $geometrySql = 'ST_TRANSFORM('.$geometrySql.', '.$srid.')';
            } else {
                throw new SridMissmatchException($srid, $geometry->getSrid());
            }
        }

        return $this->getConnection()->raw($geometrySql);
    }

    protected function geogFromText(Geometry $geometry, $srid = 4326)
    {
        $generator = $this->getGenerator();
        $geometrySql = $generator->toPostgisGeographySql($geometry, Config::get('magellan.schema', 'public'));

        if ($geometry->hasSrid() && $geometry->getSrid() != $srid) {
            if (Config::get('magellan.transform_on_insert', false)) {
                $geometrySql = 'ST_TRANSFORM('.$geometrySql.', '.$srid.')';
            } else {
                throw new SridMissmatchException($srid, $geometry->getSrid());
            }
        }

        return $this->getConnection()->raw($geometrySql);
    }

    public function getGeometryAsInsertable(Geometry $geometry, array $columnConfig)
    {
        return match (strtoupper($columnConfig['type'])) {
            'GEOMETRY' => $this->geomFromText($geometry, $columnConfig['srid']),
            default => $this->geogFromText($geometry, $columnConfig['srid']),
        };
    }

    protected function performInsert(EloquentBuilder $query, array $options = [])
    {
        $geometryCache = [];

        foreach ($this->attributes as $key => $value) {
            if ($value instanceof Geometry) {
                $geometryCache[$key] = $value; //Preserve the geometry objects prior to the insert
                if ($value instanceof GeometryCollection) {
                    // --> Only insertable into geometry column types
                    $this->attributes[$key] = $this->geomFromText($value);
                } else {
                    $this->attributes[$key] = $this->geomFromText($value);
                    $columnConfig = $this->getPostgisTypeAndSrid($key);
                    $this->attributes[$key] = $this->getGeometryAsInsertable($value, $columnConfig);
                }
            }
        }

        $insert = parent::performInsert($query, $options);

        foreach ($geometryCache as $key => $value) {
            $this->attributes[$key] = $value; //Retrieve the geometry objects so they can be used in the model
        }

        return $insert; //Return the result of the parent insert
    }

    public function setRawAttributes(array $attributes, $sync = false)
    {
        $pgfields = $this->getPostgisColumnNames();

        // postgis always returns the geometry as a WKB string, so we need to convert it to a Geometry object
        $parser = App::make(WKBParser::class);

        foreach ($attributes as $key => &$value) {
            if (in_array($key, $pgfields) && is_string($value)) {
                $value = $parser->parse($value);
            }
        }

        return parent::setRawAttributes($attributes, $sync);
    }

    protected function assertPostgisColumnsNotEmpty()
    {
        if (! property_exists($this, 'postgisColumns')) {
            throw new PostgisColumnsNotDefinedException(__CLASS__.' has not defined any postgis columns within the $postgisColumns property.');
        }
    }

    protected function assertKeyIsInPostgisColumns(string $key)
    {
        if (! in_array($key, $this->getPostgisColumnNames())) {
            throw new PostgisColumnsNotDefinedException(__CLASS__." has not defined the column '$key' within the \$postgisColumns property.");
        }
    }
}
