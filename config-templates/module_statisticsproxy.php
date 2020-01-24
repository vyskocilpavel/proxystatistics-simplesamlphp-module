<?php
/**
 * This is an example configuration of SimpleSAMLphp Perun interface and additional features.
 * Copy this file to default config directory and edit the properties.
 *
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */

$config = [

    /*
     * Choose one of the following modes: PROXY, IDP, SP
     */
    'mode' => '',

    /*
     * EntityId of IdP
     * REQUIRED FOR IDP MODE
     */
    'idpEntityId' => '',

    /*
     * Name of IdP
     * REQUIRED FOR IDP MODE
     */
    'idpName' => '',

    /*
     * EntityId of SP
     * REQUIRED FOR SP MODE
     */
    'spEntityId' => '',

    /*
     * Name of SP
     * REQUIRED FOR SP MODE
     */
    'spName' => '',

    /*
     * Config for SimpleSAML\Database.
     * If not set, the global config is used.
     * @see SimpleSAML\Database
     */
    'store' => [
        'database.dsn' => 'mysql:host=localhost;port=3306;dbname=STATS;charset=utf8',
        'database.username' => 'stats',
        'database.password' => 'stats',

        /**
         * Configuration for SSL
         * If you want to use SSL, fill these values and uncomment the block of code
         */
        /*
        'database.driver_options' => [
            // Path for the ssl key file
            PDO::MYSQL_ATTR_SSL_KEY => '',
            // Path for the ssl cert file
            PDO::MYSQL_ATTR_SSL_CERT => '',
            // Path for the ssl ca file
            PDO::MYSQL_ATTR_SSL_CA => '',
            // Path for the ssl ca dir
            PDO::MYSQL_ATTR_SSL_CAPATH => '',
        ],
        */
    ],

    /*
     * For how many days should detailed statistics (per user) be kept.
     * @default 0
     */
    'detailedDays' => 0,

    /**
     * Which attribute should be used as user ID.
     * @default uid
     */
    'userIdAttribute' => 'uid',

    /*
     * Table name for statistics
     */
    'statisticsTableName' => 'statisticsTableName',

    /*
     * Table name for detailed statistics
     * @default
     */
    'detailedStatisticsTableName' => 'statistics_detail',

    /*
     * Table name for identityProvidersMap
     */
    'identityProvidersMapTableName' => 'identityProvidersMap',

    /*
     * Table name for serviceProviders
     */
    'serviceProvidersMapTableName' => 'serviceProvidersMap',

    /**
     * Authentication source name if authentication should be required.
     * Defaults to empty string.
     */
    //'requireAuth.source' => 'default-sp',
];
