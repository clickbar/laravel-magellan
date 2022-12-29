<?php

namespace Clickbar\Magellan\Enums;

enum ExpressionReturnType: string
{
    case Geometry = 'geometry';
    case Numeric = 'numeric';
    case Boolean = 'boolean';
    case String = 'string';
    case BBox = 'bbox';
    case GeometryOrBBox = 'geometryOrBbox';
    case Set = 'set';
}
