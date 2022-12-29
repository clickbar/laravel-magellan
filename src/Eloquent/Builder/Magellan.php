<?php

namespace Clickbar\Magellan\Eloquent\Builder;

class Magellan
{
    use MagellanGeometryAccessorExpressions;
    use MagellanBoundingBoxExpressions;
    use MagellanGeometryProcessingExpressions;
    use MagellanMeasurementExpressions;
    use MagellanOverlayExpressions;
    use MagellanTopologicalRelationshipExpressions;
}
