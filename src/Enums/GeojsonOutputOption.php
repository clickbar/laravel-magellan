<?php

namespace Clickbar\Magellan\Enums;

enum GeojsonOutputOption: int
{
    case NoOption = 0;
    case Bbox = 1;
    case ShortCrs = 2;
    case LongCrs = 4;
    case ShortCrsIfNot4326 = 8;
}
