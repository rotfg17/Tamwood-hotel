<?php

require_once './Back-end/config/Database.php';
require_once './Back-end/controller/UserController.php';
require_once './Back-end/controller/RoomController.php';
require_once './Back-end/controller/TransactionController.php';
require_once './Back-end/controller/ServiceController.php';

function route($method, $path) {
    $parsedPath = parse_url($path, PHP_URL_PATH);

    $db = new Database();

    $ROOT_PATH = '/Tamwood-hotel/';

    if ($method === 'GET' && $parsedPath === $ROOT_PATH) {
        echo "index.php";
    } else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/users') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('users'));
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-user') {
        $controller = new UserController($db, $method);
        $request = $controller->processRequest('add-user');
        json_encode($request);
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-user') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('update-user'));
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-user') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('delete-user'));
    }
    
    // room
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/room-type') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('room-types');
        json_encode($request);
    }
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/rooms') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('rooms');

        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-room') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('create-room');

        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-room') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('update-room');

        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-room') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('delete-room');

        json_encode($request);
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

    // service
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/services') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('services');
        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-service') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('create-service');
        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-service') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('update-service');
        json_encode($request);
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-service') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('delete-service');
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
