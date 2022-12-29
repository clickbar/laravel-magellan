<?php

namespace Clickbar\Magellan\Enums;

enum ValidFlag: int
{
    /**
     * Use usual OGC SFS validity semantics.
     */
    case OCG = 0;

    /**
     * Consider certain kinds of self-touching rings (inverted shells and exverted holes) as valid. This is also known as "the ESRI flag", since this is the validity model used by those tools. Note that this is invalid under the OGC model.
     */
    case ESRI = 1;
}
