<?php

declare(strict_types=1);

require "../bootstrap.php";
use Src\Services\ControllerFactory;
use Src\Interfaces\HttpResponseConstantsInterface;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if (!$uri[1]) {
    header(HttpResponseConstantsInterface::HTTP_404);
    exit();
}

$identifier = $uri[2] ?? null;
$filter = $uri[3] ?? null;

$requestMethod = $_SERVER["REQUEST_METHOD"];

try {
    $Controller = ControllerFactory::create($uri[1], $requestMethod, $identifier, $filter); 

    if(null === $Controller) {
        header(HttpResponseConstantsInterface::HTTP_404);
    } 

    $Controller->processRequest();
}
catch (Exception $e) {
    $code = 500;
    $message = "Internal server error";
    header(HttpResponseConstantsInterface::HTTP_500);  
    echo json_encode(array("error" => array("code" => $code, "message" => $message)));
    exit();
}
