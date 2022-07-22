<?php

namespace Clickbar\Magellan\IO\Parser\WKB;

enum WKBGeometryType: int
{
    case point = 1;
    case lineString = 2;
    case polygon = 3;
    case multiPoint = 4;
    case multiLineString = 5;
    case multiPolygon = 6;
    case geometryCollection = 7;
}
