<?php

use Clickbar\Magellan\Geometries\GeometryFactory;
use Clickbar\Magellan\IO\Parser\WKT\WKTParser;

beforeEach(function () {
    $this->parser = new WKTParser(new GeometryFactory());
});
