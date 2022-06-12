<?php

namespace Clickbar\Postgis\IO\Parser\WKB;

enum ByteOrder: string
{
    case littleEndian = 'littleEndian';

    case bigEndian = 'bigEndian';
}
