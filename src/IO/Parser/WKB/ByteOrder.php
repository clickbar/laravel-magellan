<?php

namespace Clickbar\Magellan\IO\Parser\WKB;

enum ByteOrder: string
{
    case littleEndian = 'littleEndian';

    case bigEndian = 'bigEndian';
}
