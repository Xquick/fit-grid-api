<?php
/*
* @property CI_Controller $CI
*/
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once APPPATH.'/../vendor/autoload.php';

class Doctrine
{
    public $em;

    function __construct()
    {
        $isDevMode = true;

        $config = Setup::createAnnotationMetadataConfiguration(array("application/entity"), $isDevMode);

        $conn = array(
            'driver' => 'pdo_mysql',
            'user' => 'root',
            'password' => '',
            'host' => '127.0.0.1',
            'dbname' => 'fitgrid'
        );
        $this->em = EntityManager::create($conn, $config);
    }
}

