<?php

namespace Clickbar\Magellan\Enums;

enum GeojsonOutputOption: int
{
    case NoOption = 0;
    case ShortCrs = 2;
    case LongCrs = 3;
    case ShortCrsIfNot4326 = 8;
}
