<?php

namespace Clickbar\Magellan\Schema;

/**
 * @mixin \Illuminate\Database\Schema\Blueprint
 */
class MagellanBlueprint
{
    /*
     * Point
     */

    public function magellanPoint(): \Closure
    {
        return $this->createAddColumnClosure('magellanPoint');
    }

    public function magellanPointZ(): \Closure
    {
        return $this->createAddColumnClosure('magellanPointZ');
    }

    public function magellanPointM(): \Closure
    {
        return $this->createAddColumnClosure('magellanPointM');
    }

    public function magellanPointZM(): \Closure
    {
        return $this->createAddColumnClosure('magellanPointZM');
    }

    /*
     * Line String
     */

    public function magellanLineString(): \Closure
    {
        return $this->createAddColumnClosure('magellanLineString');
    }

    public function magellanLineStringZ(): \Closure
    {
        return $this->createAddColumnClosure('magellanLineStringZ');
    }

    public function magellanLineStringM(): \Closure
    {
        return $this->createAddColumnClosure('magellanLineStringM');
    }

    public function magellanLineStringZM(): \Closure
    {
        return $this->createAddColumnClosure('magellanLineStringZM');
    }

    /*
     * Polygon
     */

    public function magellanPolygon(): \Closure
    {
        return $this->createAddColumnClosure('magellanPolygon');
    }

    public function magellanPolygonZ(): \Closure
    {
        return $this->createAddColumnClosure('magellanPolygonZ');
    }

    public function magellanPolygonM(): \Closure
    {
        return $this->createAddColumnClosure('magellanPolygonM');
    }

    public function magellanPolygonZM(): \Closure
    {
        return $this->createAddColumnClosure('magellanPolygonZM');
    }

    /*
     * Multipoint
     */

    public function magellanMultiPoint(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPoint');
    }

    public function magellanMultiPointZ(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPointZ');
    }

    public function magellanMultiPointM(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPointM');
    }

    public function magellanMultiPointZM(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPointZM');
    }

    /*
     * Multi Line String
     */

    public function magellanMultiLineString(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiLineString');
    }

    public function magellanMultiLineStringZ(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiLineStringZ');
    }

    public function magellanMultiLineStringM(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiLineStringM');
    }

    public function magellanMultiLineStringZM(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiLineStringZM');
    }

    /*
     * Multi Polygon
     */

    public function magellanMultiPolygon(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPolygon');
    }

    public function magellanMultiPolygonZ(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPolygonZ');
    }

    public function magellanMultiPolygonM(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPolygonM');
    }

    public function magellanMultiPolygonZM(): \Closure
    {
        return $this->createAddColumnClosure('magellanMultiPolygonZM');
    }

    /*
     * Geometry Collection
     */

    public function magellanGeometryCollection(): \Closure
    {
        return $this->createAddColumnGeometryClosure('magellanGeometryCollection');
    }

    public function magellanGeometryCollectionZ(): \Closure
    {
        return $this->createAddColumnGeometryClosure('magellanGeometryCollectionZ');
    }

    public function magellanGeometryCollectionM(): \Closure
    {
        return $this->createAddColumnGeometryClosure('magellanGeometryCollectionM');
    }

    public function magellanGeometryCollectionZM(): \Closure
    {
        return $this->createAddColumnGeometryClosure('magellanGeometryCollectionZM');
    }

    public function magellanBox2D(): \Closure
    {
        return function ($column) {
            return $this->addColumn('magellanBox2D', $column);
        };
    }

    public function magellanBox3D(): \Closure
    {
        return function ($column) {
            return $this->addColumn('magellanBox3D', $column);
        };
    }

    /*
     * Base Types
     */

    public function magellanGeography(): \Closure
    {
        return function ($column, $srid = 4326) {
            $postgisType = 'GEOGRAPHY';

            return $this->addColumn('magellanGeography', $column, compact('postgisType', 'srid'));
        };
    }

    public function magellanGeometry(): \Closure
    {
        return function ($column, $srid = 4326) {
            $postgisType = 'GEOMETRY';

            return $this->addColumn('magellanGeometry', $column, compact('postgisType', 'srid'));
        };
    }

    /*
     * Helper
     */

    private function createAddColumnClosure(string $type): \Closure
    {
        return function ($column, $srid = 4326, string $postgisType = 'GEOMETRY') use ($type) {
            return $this->addColumn($type, $column, compact('postgisType', 'srid'));
        };
    }

    private function createAddColumnGeometryClosure(string $type): \Closure
    {
        return function ($column, $srid = 4326) use ($type) {
            $postgisType = 'GEOMETRY';

            return $this->addColumn($type, $column, compact('postgisType', 'srid'));
        };
    }
}
