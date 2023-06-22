<?php
namespace Src\Services;

use DI\Container;
use Src\Interfaces\DatabaseInterface;
use Src\System\DatabaseConnector;

class DependencyInjectionSingleton {
    private static $container = null;

    public static function getContainer(): Container {

        if (null === static::$container) {
            static::$container = new Container([
                DatabaseInterface::class => ( fn() => new DatabaseConnector() ),
            ]);
        }

        return static::$container;
    }
}