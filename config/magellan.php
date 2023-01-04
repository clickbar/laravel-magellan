<?php

// config for clickbar/laravel-magellan
return [

    'schema' => 'public',

    'eloquent' => [
        'default_postgis_type' => 'geography',
        'default_srid' => 4326,
    ],

    'json_generator' => \Clickbar\Magellan\IO\Generator\Geojson\GeojsonGenerator::class,

    'sql_generator' => \Clickbar\Magellan\IO\Generator\WKT\WKTGenerator::class,

    'string_generator' => \Clickbar\Magellan\IO\Generator\Geojson\GeojsonGenerator::class,

    'transform_on_insert' => false,

    /**
     * The directories where the models are located that we should consider for the magellan commands.
     * E.g. the update-postgis-columns command uses these locations to find the models of tables
     * that should be updated.
     */
    'model_directories' => [
        'Models',
    ],
];
