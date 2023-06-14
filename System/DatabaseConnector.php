<?php
namespace Src\System;

class DatabaseConnector {
  
    private $dbConnection = null;

    public function __construct() {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db   = getenv('DB_DATABASE');
        $user = getenv('DB_USERNAME');
        $pass = getenv('DB_PASSWORD');
        // $dns = "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db";
        $dns = 'mysql:dbname=' . $host . ';unix_socket=/var/run/mysqld/mysqld.sock';

        try {
            $this->dbConnection = new \PDO(
                $dns,
                $user,
                $pass,
                array (
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )
		    );
        } 
        catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function getConnection() {
        return $this->dbConnection;
    }
}
