<?php

// doctrine.php
use Symfony\Component\Console\Application;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Core\Database\DB;

// as before ...
require_once 'vendor/autoload.php';
// replace with file to your own project bootstrap
require_once 'Bootstrap.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManager = DB::Connection();
// replace the ConsoleRunner::run() statement with:
$cli = new Application('Doctrine Command Line Interface', \Doctrine\ORM\Version::VERSION);
$cli->setCatchExceptions(true);
$cli->setHelperSet(ConsoleRunner::createHelperSet($entityManager));

// Register All Doctrine Commands
ConsoleRunner::addCommands($cli);

// Register your own command
$cli->addCommands([new \Core\Command\LoadData()]);

// Runs console application
$cli->run();
