<?php

require_once './Back-end/config/Database.php';
require_once './Back-end/controller/UserController.php';
require_once './Back-end/controller/BookingController.php';
require_once './Back-end/controller/RoomController.php';

function route($method, $path) {
    $parsedPath = parse_url($path, PHP_URL_PATH);
    $db = new Database();

    $ROOT_PATH = '/Tamwood-hotel/';

    if ($method === 'GET' && $parsedPath === $ROOT_PATH) {
        echo "index.php";
    } 
    //UserController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/user-list') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('user-list'));
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/users') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('users'));
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-user') {
        $controller = new UserController($db, $method);
        return  json_encode($controller->processRequest('add-user'));
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-user') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('update-user'));
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-user') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('delete-user'));
    }
    //BookingController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/booking-list') {
        $controller = new BookingController($db, $method);
        return json_encode($controller->processRequest('booking-list'));
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/bookings') {
        $controller = new BookingController($db, $method);
        return json_encode($controller->processRequest('bookings'));
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-booking-status') {
        $controller = new BookingController($db, $method);
        return json_encode($controller->processRequest('update-booking-status'));
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-booking') {
        $controller = new BookingController($db, $method);
        return json_encode($controller->processRequest('delete-booking'));
    }
    //RoomController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/room-type') {
        $controller = new RoomController($db, $method);
        return json_encode($controller->processRequest('room-types'));
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
