<?php
require "../bootstrap.php";
use Src\Controller\ForumController;
use Src\Controller\PostController;
use Src\Controller\UserController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

if (!$uri[1]) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$identifier = isset($uri[2]) ? $uri[2] : null;

$requestMethod = $_SERVER["REQUEST_METHOD"];

try {
    $Controller = null;  
    switch ($uri[1]) {
    case "forum":
        $Controller = new ForumController($dbConnection, $requestMethod, $identifier);
        break;
    case "post":
        $Controller = new PostController($dbConnection, $requestMethod, $identifier);
        break;
    case "user":
        $Controller = new UserController($dbConnection, $requestMethod, $identifier);
        break;    
    default:
        header("HTTP/1.1 404 Not Found");
        exit();
        break;
    }
 
    // pass the request method and variables to the Controller and process the HTTP request:
    $Controller->processRequest();
}
catch (Exception $e) {
    $code = 500;
    $message = "Internal server error";
    header("HTTP/1.1 500 Server Error");  
    echo json_encode(array("error" => array("code" => $code, "message" => $message)));
    exit();
}
