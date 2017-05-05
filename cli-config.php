<?php

////  You are missing a "cli-config.php" or "config/cli-config.php" file in your
//// project, which is required to get the Doctrine Console working. You can use the
//// following sample as a template:
//
//use Doctrine\ORM\Tools\Console\ConsoleRunner;
//
//define('BASEPATH', APPPATH . '/../system/');
//// replace with file to your own project bootstrap
//require __DIR__ . '/application/libraries/Doctrine.php';
//
//// replace with mechanism to retrieve EntityManager in your app
//$entity = new Doctrine();
//
//$doctrine = new Doctrine;
//$em = $doctrine->em;
//
//$helperSet = new Symfony\Component\Console\Helper\HelperSet(array(
//    'db' => new Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
//    'em' => new Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em)
//));
//
//\Doctrine\ORM\Tools\Console\ConsoleRunner::run($helperSet);

// cli-config.php
require_once __DIR__ . '/application/libraries/Doctrine.php';

$doctrine = new Doctrine();

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($doctrine->em);