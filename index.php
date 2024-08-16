<?php

require_once './Back-end/config/Database.php';
require_once './Back-end/controller/UserController.php';

function route($method, $path) {
    $parsedPath = parse_url($path, PHP_URL_PATH);

    $db = new Database();

    $ROOT_PATH = '/Tamwood-hotel/';

    if ($method === 'GET' && $parsedPath === $ROOT_PATH) {
        echo "index.php";
    } else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/users') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('users'));
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-user') {
        $controller = new UserController($db, $method);
        $request = $controller->processRequest('add-user');
        json_encode($request);
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-user') {
        $controller = new UserController($db, $method);
        $request = $controller->processRequest('update-user');
        json_encode($request);
    } 
    else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(['error' => 'Route not found']);
    }
}

// Real Request
try {
    route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch(Exception $error) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['error' => $error->getMessage()]);
}
?>
