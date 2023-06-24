<?php
namespace Src\Controller;

use Src\TableGateways\PostGateway;
use Src\Services\DependencyInjectionSingleton;

class PostController extends AbstractController {
    protected function _initGateway() {
        $this->_Gateway = DependencyInjectionSingleton::getContainer()->get(PostGateway::class);
    }

    protected function _validate($input, $requestMethod) {
        return isset($input["message"]) && $input["message"] != "";
    }

    protected function _authenticate() {
        return true;
    }
}
