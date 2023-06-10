<?php
namespace Src\Controller;

use Src\TableGateways\PostGateway;

class PostController extends AbstractController {
    protected function _initGateway($db) {
        $this->_Gateway = new PostGateway($db);
    }

    protected function _validate($input, $requestMethod) {
        if ($requestMethod == "PUT") {
            return isset($input["message"]) && $input["message"] != "";
        }
        return false;
    }
}
