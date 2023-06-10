<?php
namespace Src\Controller;

use Src\TableGateways\ForumGateway;

class ForumController extends AbstractController {
    protected function _initGateway($db) {
        $this->_Gateway = new ForumGateway($db);
    }

    protected function _validate($input, $requestMethod) {
        return isset($input["name"]) && $input["name"] != "";
    }
}
