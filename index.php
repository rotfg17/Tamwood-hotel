<?php

require_once './Back-end/config/Database.php';
require_once './Back-end/controller/UserController.php';
require_once './Back-end/controller/RoomController.php';
require_once './Back-end/controller/TransactionController.php';

function route($method, $path) {
    $parsedPath = parse_url($path, PHP_URL_PATH);

    $db = new Database();

    $ROOT_PATH = '/Tamwood-hotel/';

    if ($method === 'GET' && $parsedPath === $ROOT_PATH) {
        echo "index.php";
    }

    // user
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/users') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('users'));
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-user') {
        $controller = new UserController($db, $method);
        $request = $controller->processRequest('add-user');
        json_encode($request);
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-user') {
        $controller = new UserController($db, $method);
        $request = $controller->processRequest('update-user');
        json_encode($request);
    }
    
    // room
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/room-type') {
        $controller = new RoomController($db, $method);
        return json_encode($controller->processRequest('room-types'));
    }

    // transaction
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/transactions') {
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('transactions');
        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-transaction') {
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('create-transaction');
        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-transaction') {
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('delete-transaction');
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
