<?php

namespace Clickbar\Magellan\Data\Geometries;

enum Dimension: string
{
    /*
     * Two-Dimension Geometry, e.g. POINT(x, y).
     */
    case DIMENSION_2D = '2D';
    /*
     * Three-Dimension Geometry, e.g. POINT(x, y, z).
     */
    case DIMENSION_3DZ = '3DZ';
    /*
     * Two-Dimension Measured Geometry, e.g. POINT(x, y, m).
     */
    case DIMENSION_3DM = '3DM';
    /*
     * Three-Dimension Measured Geometry, e.g. POINT(x, y, z, m).
     */
    case DIMENSION_4D = '4D';

    public function hasZDimension(): bool
    {
        return $this === self::DIMENSION_3DZ || $this === self::DIMENSION_4D;
    }

    public function isMeasured(): bool
    {
        return $this === self::DIMENSION_3DM || $this === self::DIMENSION_4D;
    }

    public static function fromCoordinates(float $x, float $y, ?float $z, ?float $m): self
    {
        if ($z === null && $m === null) {
            return Dimension::DIMENSION_2D;
        } elseif ($z !== null && $m === null) {
            return Dimension::DIMENSION_3DZ;
        } elseif ($z === null && $m !== null) {
            return Dimension::DIMENSION_3DM;
        } else {
            return Dimension::DIMENSION_4D;
        }
    }
}
