<?php

namespace Clickbar\Magellan\Enums;

enum MakeValidMethod: string
{
    /**
     * "linework" is the original algorithm, and builds valid geometries by first extracting all lines, noding that linework together, then building a value output from the linework.
     */
    case Linework = 'linework';

    /**
     * "structure" is an algorithm that distinguishes between interior and exterior rings, building new geometry by unioning exterior rings, and then differencing all interior rings.
     */
    case Structure = 'structure';
}
