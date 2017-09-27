# proxystatistics-simplesamlphp-module
Module for simpleSAMLphp which shows Proxy IdP/SP statistics

Guide for use module statistic
1. Install MySQL Database and create database for statistics and user. 
2. For this database run script to create tables. Script is available in config-templates/tables.sql
3. Copy config-templates/module_statisticsproxy.php to your folder vith config and filled it.
4. Add following to authproc in file saml20-idp-hosted.php:

      XX => array(
                                'class' => 'proxystatistics:statistics',
                                'config' => array (),
                        ),
  where XX is number(for example 50; Must not be used for other modules)
