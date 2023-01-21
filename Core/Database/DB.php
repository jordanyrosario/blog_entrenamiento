<?php

namespace Core\Database;

 use Doctrine\DBAL\DriverManager;
 use Doctrine\ORM\ORMSetup;
 use Doctrine\ORM\Tools\Setup;
 use Doctrine\ORM\EntityManager;

 class DB
 {
     private static $host;
     private static $username;
     private static $password;
     private static $database;
     private static $port;
     private static $isDevMode = true;
     private static $proxyDir;
     private static $cache;
     private static $useSimpleAnnotationReader = false;

     public function __construct()
     {
     }

    public static function Connection()
    {
        self::$host = $_ENV['DB_HOST'];
        self::$username = $_ENV['DB_USER'];
        self::$password = $_ENV['DB_PASSWORD'];
        self::$database = $_ENV['DB_NAME'];
        self::$port = $_ENV['DB_PORT'] ? $_ENV['DB_PORT'] : 3306;
        $entityDIr = dirname(__DIR__, 2).'/App/Entities';
      
        // var_dump($_ENV);
        $dbParams = [
            'driver' => 'pdo_mysql',
            'user' => self::$username,
            'password' => self::$password,
            'dbname' => self::$database,
            'host' => self::$host,
            'port' => self::$port,
        ];

            $paths = [ $entityDIr ];
            $isDevMode = false;

            

            $config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode);
            $connection = DriverManager::getConnection($dbParams, $config);
            try{

                $entityManager = new EntityManager($connection, $config);
                return $entityManager;
            } catch (\PDOException $e) {
                echo 'Unbale to connect to the database. ERROR:'.$e->getMessage();
            }
     }
 }
