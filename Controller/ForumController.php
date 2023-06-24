<?php
namespace Src\Controller;

use Src\TableGateways\ForumGateway;
use Src\Services\DependencyInjectionSingleton;

class ForumController extends AbstractController {
    protected function _initGateway() {
        $this->_Gateway = DependencyInjectionSingleton::getContainer()->get(ForumGateway::class);
    }

    protected function _validate($input, $requestMethod) {
        return isset($input["name"]) && $input["name"] != "";
    }

    protected function _authenticate() {
        return true;
    }
}
