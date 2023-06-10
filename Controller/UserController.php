<?php
namespace Src\Controller;

use Src\TableGateways\UserGateway;

class UserController extends AbstractController {
    protected function _initGateway($db) {
        $this->_Gateway = new UserGateway($db);
    }

    protected function _validate($input, $requestMethod) {
        return
            (isset($input["name"]) && $input["name"] != "") &&
            (isset($input["password"]) && $input["password"]);
    }
}
