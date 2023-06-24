<?php
namespace Src\Services;

use Src\Controller\AbstractController;

Class ControllerFactory 
{
    public static function create($apiEndPoint = '', $requestMethod, $identifier, $filter = null): null|AbstractController
    {
        $className = 'Src\Controller\\' . ucfirst(strtolower($apiEndPoint)) . 'Controller'::class;

        if(!class_exists($className)) {
            return null;
        }
        
        return new $className($requestMethod, $identifier, $filter);
    }
}