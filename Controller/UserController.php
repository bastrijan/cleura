<?php
namespace Src\Controller;

use Src\TableGateways\UserGateway;
use Src\Services\DependencyInjectionSingleton;
use Src\Utilities\Helper;

class UserController extends AbstractController {
    protected function _initGateway() {
        $this->_Gateway = DependencyInjectionSingleton::getContainer()->get(UserGateway::class);
    }

    protected function _validate($input, $requestMethod) {
        
        return
            (isset($input["name"]) && $input["name"] != "") &&
            (isset($input["password"]) && $input["password"]);
    }

    protected function _authenticate() {
        $name = $_SERVER['HTTP_NAME'] ?? null;
        $password = $_SERVER['HTTP_PASSWORD'] ?? null;

        $result = (null !== $name ? $this->_Gateway->findByName($name) : false);

        if(!$result) {
            return false;
        }

        if(!Helper::validateHashedPassword($password, $result[0]["password"]))
        {
            return false;
        }

        return (1 === $result[0]["admin"] ? true : false) ;
    }
}
