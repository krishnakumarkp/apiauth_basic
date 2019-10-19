<?php
require "../bootstrap.php";
use Src\Controller\PersonController;
use Src\TableGateways\UserGateway;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// all of our endpoints start with /person
// everything else results in a 404 Not Found
if ($uri[2] !== 'person') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// the user id is, of course, optional and must be a number:
$userId = null;
if (isset($uri[3])) {
    $userId = (int) $uri[3];
}

// authenticate the request 
if (!isset($_SERVER['PHP_AUTH_USER']) and !$_SERVER['PHP_AUTH_USER']) {
    header('WWW-Authenticate: Basic realm="LOGIN REQUIRED"');
    header('HTTP/1.0 401 Unauthorized');
    $status = array('error' => 1, 'message' => 'Access denied 401!');
    echo json_encode($status);
    exit;
}
if (!authenticate($dbConnection, $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
    header('WWW-Authenticate: Basic realm="WRONG PASSWORD"');
    header('HTTP/1.0 401 Unauthorized');
    $status = array('error' => 2, 'message' => 'Wrong username or password!');
    echo json_encode($status);
    exit;
}

$requestMethod = $_SERVER["REQUEST_METHOD"];
// pass the request method and user ID to the PersonController and process the HTTP request:
if ($uri[2] == 'person' ) {
    $controller = new PersonController($dbConnection, $requestMethod, $userId);
}

$controller->processRequest();

function authenticate($dbConnection, $username, $password) {
    try {

       $userGateway = new UserGateway($dbConnection);

       $userDetails =  $userGateway->findUser($username);
        
        if(!password_verify($password, $userDetails[0]['password'])) {
            throw new \Exception('No user found');
        }
        return true;
    } catch (\Exception $e) {
        return false;
    }
}