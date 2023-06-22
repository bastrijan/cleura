<?php
namespace Src\Services;

use \PDO;
use Src\Services\DependencyInjectionSingleton;
use Src\Interfaces\DatabaseInterface;

class DbSingleton {
    private static $connection = null;

    public static function getConnection() {

        if (null === static::$connection) {
            static::$connection = DependencyInjectionSingleton::getContainer()->get(DatabaseInterface::class)->connect();
        }

        return static::$connection;
    }
}