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
    private $proxyTableName;
    private $servicesTableName;

    const CONFIG_FILE_NAME = 'module_statisticsproxy.php';
    const SERVER  = 'serverName';
    const USER = 'userName';
    const PASSWORD = 'password';
    const DATABASE = 'databaseName';
    const PROXY_TABLE_NAME = 'proxyTableName';
    const SERVICES_TABLE_NAME = 'servicesProxyName' ;



    public function __construct ()
    {
        $conf = SimpleSAML_Configuration::getConfig(self::CONFIG_FILE_NAME);
        $this->serverName = $conf->getString(self::SERVER);
        $this->username = $conf->getString(self::USER);
        $this->password = $conf->getString(self::PASSWORD);
        $this->databaseName = $conf->getString(self::DATABASE);
        $this->proxyTableName = $conf->getString(self::PROXY_TABLE_NAME);
        $this->servicesTableName = $conf->getString(self::SERVICES_TABLE_NAME);
    }

    public function getConnection()
    {
        $conn = NULL;
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->databaseName);
        return $conn;
    }

    public function getProxyTableName()
    {
        return $this->proxyTableName;

    }

    public function getServicesTableName()
    {
        return $this->servicesTableName;

    }


}