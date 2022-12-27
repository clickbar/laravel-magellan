<?php

namespace Clickbar\Magellan\Enums;

enum DelaunayTrianglesOutput: int
{
    case CollectionOfPolyongs = 0;
    case MultilineString = 1;
    case Tin = 2;
}
