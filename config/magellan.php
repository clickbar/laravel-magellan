<?php

return [

    /**
     * The database schema that should be used for prefixing / accessing PostGIS functions.
     * E.g. the ST_X() function will be prefixed with the schema name and become public.ST_X().
     * You will usually only need this in custom deployments where you have a custom schema for PostGIS.
     *
     * @see https://www.postgis.net/workshops/postgis-intro/schemas.html
     */
    'schema' => 'public',

    'eloquent' => [
        /**
         * The default PostGIS type information that should be used when interacting with PostGIS columns
         * on Eloquent models. This is used when the type information is not specified in the $postgisColumns
         * array on the model.
         */
        'default_postgis_type' => 'geometry',
        'default_srid' => 4326,

        /**
         * When set to true, the Eloquent model will automatically transform geometries to the database projection
         * using ST_TRANSFORM(), when the SRID of the geometry does not match the SRID of the column as described in the
         * model's $postgisColumns array.
         *
         * NOTE: This only works for Eloquent based insert/update operations, and will not work for custom DB queries.
         * NOTE: This will ignore geography columns, since they do not support transformation to other SRIDs.
         *
         * @see https://postgis.net/docs/ST_Transform.html
         */
        'transform_to_database_projection' => false,
    ],

    /**
     * The generator that should be used when converting a geometry to JSON.
     * This should be a sensitive default for most WGS use-cases, but remember that
     * the GeoJSON standard is only defined for SRID 4326 and will fail otherwise. You
     * may use another generator if you need to support other SRIDs eg. the WKT generator.
     */
    'json_generator' => \Clickbar\Magellan\IO\Generator\Geojson\GeojsonGenerator::class,

    /**
     * The generator that should be used when converting a geometry for the database.
     * You may use this variable to use a custom generator if you need any specialized handling.
     * It is recommended to use the WKT generator as a default for a readable output when debugging and best
     * compatibility.
     *
     * NOTE: GeoJSON cannot be used as a generator for geography columns, since it does not support SRIDs.
     */
    'sql_generator' => \Clickbar\Magellan\IO\Generator\WKT\WKTGenerator::class,

    /**
     * The generator that should be used when converting a geometry to a string.
     * This should be a sensitive default for all use-cases and will be used by the
     * __toString() method on the geometry objects.
     *
     * It may also be implicitly used, when passing Geometry objects to the DB as bindings in queries.
     * NOTE: The GeoJson generator will not work for geography columns, since they do not support SRIDs.
     */
    'string_generator' => \Clickbar\Magellan\IO\Generator\WKT\WKTGenerator::class,

    /**
     * The directories where the models are located that we should consider for the magellan commands.
     * E.g. the update-postgis-columns command uses these locations to find the models of tables which should
     * be updated.
     */
    'model_directories' => [
        'Models',
    ],

    /**
     * The SRIDs, that are used to determine whether a geometry class uses a geodetic projection when trying
     * to access coordinates using the geodetic named lat/lng/alt methods. Using a SRID not present in this list
     * will raise an error stating that the projection is not lng/lat and therefore shouldn't be accessed that way
     */
    'geodetic_srids' => [
        4326,
        4267,
        4269,
    ],

    /**
     * The default SRID that will be set in the Point Geometry Class when using the makeGeodetic factory function.
     */
    'geodetic_default_srid' => 4326,
];
