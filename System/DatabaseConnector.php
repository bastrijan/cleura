<?php
namespace Src\System;

use \PDO;
use Src\Interfaces\DatabaseInterface;

class DatabaseConnector implements DatabaseInterface {

    private $dns;
    private $user;
    private $pass;
  
    public function __construct() {
        
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT');
        $db   = getenv('DB_DATABASE');
        
        $this->user = getenv('DB_USERNAME');
        $this->pass = getenv('DB_PASSWORD');
        $this->dns = "mysql:host=$host;port=$port;charset=utf8mb4;dbname=$db";
        // $dns = 'mysql:dbname=' . $host . ';unix_socket=/var/run/mysqld/mysqld.sock';
    }

    public function connect()
    {
        try {
            return  new PDO(
                $this->dns,
                $this->user,
                $this->pass,
                array (
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
                )
		    );
        } 
        catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }
}
