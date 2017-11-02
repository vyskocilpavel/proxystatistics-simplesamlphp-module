<?php
/**
 * @author Pavel Vyskočil <vyskocilpavel@muni.cz>
 */

class databaseConnector
{
    private $serverName;
    private $username;
    private $password;
    private $databaseName;
    private $identityProvidersTableName;
    private $serviceProvidersTableName;

    const CONFIG_FILE_NAME = 'module_statisticsproxy.php';
    const SERVER  = 'serverName';
    const USER = 'userName';
    const PASSWORD = 'password';
    const DATABASE = 'databaseName';
    const IDP_TABLE_NAME = 'identityProvidersTableName';
    const SP_TABLE_NAME = 'serviceProvidersTableName' ;



    public function __construct ()
    {
        $conf = SimpleSAML_Configuration::getConfig(self::CONFIG_FILE_NAME);
        $this->serverName = $conf->getString(self::SERVER);
        $this->username = $conf->getString(self::USER);
        $this->password = $conf->getString(self::PASSWORD);
        $this->databaseName = $conf->getString(self::DATABASE);
        $this->identityProvidersTableName = $conf->getString(self::IDP_TABLE_NAME);
        $this->serviceProvidersTableName = $conf->getString(self::SP_TABLE_NAME);
    }

    public function getConnection()
    {
        $conn = NULL;
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->databaseName);
        return $conn;
    }

    public function getIdentityProvidersTableName()
    {
        return $this->identityProvidersTableName;

    }

    public function getServiceProvidersTableName()
    {
        return $this->serviceProvidersTableName;

    }


}