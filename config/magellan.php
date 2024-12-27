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

    /**
     * The generator that should be used when converting a geometry to JSON.
     * This should be a sensitive default for most WGS use-cases, but remember that
     * the GeoJSON standard is only defined for SRID 4326 and will fail otherwise. You
     * may use another generator if you need to support other SRIDs e.g. the WKT generator.
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
