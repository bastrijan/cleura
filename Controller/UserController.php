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
        $username = $_SERVER['HTTP_USERNAME'] ?? null;
        $password = $_SERVER['HTTP_PASSWORD'] ?? null;
        $result = $this->_Gateway->getPassword($username);
        
        if(!$result) {
            return false;
        }

        return Helper::validateHashedPassword($password, $result[0]["password"]);
    }
}
