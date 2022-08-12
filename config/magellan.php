<?php

// config for clickbar/laravel-magellan
return [

    'schema' => 'public',

    'eloquent' => [
        'default_postgis_type' => 'geography',
        'default_srid' => 4326,
    ],

    'json_generator' => \Clickbar\Magellan\IO\Generator\Geojson\GeojsonGenerator::class,

    'insert_generator' => \Clickbar\Magellan\IO\Generator\WKB\WKBGenerator::class,

    'string_generator' => \Clickbar\Magellan\IO\Generator\Geojson\GeojsonGenerator::class,

    'transform_on_insert' => false,

];
