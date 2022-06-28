<?php

// config for Clickbar/Postgis
use Clickbar\Postgis\IO\Generator\Geojson\GeojsonGenerator;

return [

    'eloquent' => [
        'default_postgis_type' => 'geography',
        'default_srid' => 4326,
    ],

    'json_generator' => GeojsonGenerator::class,

];
