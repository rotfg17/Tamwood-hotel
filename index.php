<?php

require_once './Back-end/config/Database.php';
require_once './Back-end/config/Session.php';
require_once './Back-end/controller/UserController.php';
require_once './Back-end/controller/BookingController.php';
require_once './Back-end/controller/CommentController.php';
require_once './Back-end/controller/RoomController.php';
require_once './Back-end/controller/TransactionController.php';
require_once './Back-end/controller/ServiceController.php';
require_once './Back-end/controller/SessionController.php';

// Handle CORS
handleCors();

// Real Request
try {
    $session = new Session();
    $sessionStatus = $session->getSession();
    
    // Llamada a la función route
    $response = route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

    $data = json_decode($response, true);

    if (isset($data['sid']) && $data['sid'] !== null) {
        $sessionStatus = 'active';
    }

    // Envía la respuesta al cliente
    echo json_encode([
        'sessionStatus' => $sessionStatus !== null ? $sessionStatus : null,
        'data' => $data,
    ]);
} catch (Exception $error) {
    // Manejo de errores
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['error' => $error->getMessage()]);
}


function handleCors() {
    // Allow all origins for simplicity, adjust for production environments
    header("Access-Control-Allow-Origin: *");
    // Specify the allowed methods
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    // Specify the allowed headers
    header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
    // Handle preflight requests
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header("HTTP/1.1 200 OK");
        exit();
    }
}

function route($method, $path) {
    $parsedPath = parse_url($path, PHP_URL_PATH);
    $db = new Database();

    $ROOT_PATH = '/Tamwood-hotel/';

    if ($method === 'GET' && $parsedPath === $ROOT_PATH) {
        header("Location: login.php");
        exit;
    } 
    // Login & Register 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/register') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('add-user');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/login') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('login');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/logout') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('logout');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/init-locked') { //Use it to unlock
        $controller = new UserController($db, $method);
        return $controller->processRequest('init-locked');
    } 
    //UserController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/user-list') { //Admin's user list & paging
        $controller = new UserController($db, $method);
        return $controller->processRequest('user-list');
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/users') {  //Get all users
        $controller = new UserController($db, $method);
        return $controller->processRequest('users');
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-user') { //Admin's user addition function
        $controller = new UserController($db, $method);
        if($_SESSION['userClass']){
            $role = unserialize($_SESSION['userClass']) -> getRole();
            if($role!='admin') throw new Exception("No permission");
        }
        $request = $controller->processRequest('add-user');
        return $request;
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-user') { //Admin's user modify function
        $controller = new UserController($db, $method);
        return $controller->processRequest('update-user');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-user') { ////Admin's user delete function
        $controller = new UserController($db, $method);
        return $controller->processRequest('delete-user');
    }
    // BookingController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/booking-list') { //User/Admin booking list & paging
        $controller = new BookingController($db, $method);
        return $controller->processRequest('booking-list');
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/bookings') { ///Get all bookings
        $controller = new BookingController($db, $method);
        return $controller->processRequest('bookings');
    }
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/booking-info') { //booking list -> booking info(detail page)
        $controller = new BookingController($db, $method);
        return $controller->processRequest('booking-info');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-booking') { //When a user makes a reservation
        $controller = new BookingController($db, $method);
        return $controller->processRequest('create-booking');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-booking-status') { //approved | pending | cancelled
        $controller = new BookingController($db, $method);
        return $controller->processRequest('update-booking-status');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-booking') { //delete booking
        $controller = new BookingController($db, $method);
        return $controller->processRequest('delete-booking');
    }
    // Comment
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/comment-list') { // comment list
        $controller = new CommentController($db, $method);
        return $controller->processRequest('comment-list');
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/comments') {  // Get all Comments
        $controller = new CommentController($db, $method);
        return $controller->processRequest('comments');
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-comment') { //write comment
        $controller = new CommentController($db, $method);
        return  $controller->processRequest('add-comment');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-comment') {//modify comment
        $controller = new CommentController($db, $method);
        return $controller->processRequest('update-comment');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-comment') { // delete comment
        $controller = new CommentController($db, $method);
        return $controller->processRequest('delete-comment');
    }
    // RoomController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/room-type') { //get room-types
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('room-types');

        return $request;
    }
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/rooms') { //get all rooms
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('rooms');

        return $request;
    }
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/available-room') { //Get available rooms according to period
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('available-room');

        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-room') { // Admin's func. create room
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('create-room');

        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-room') { // Admin's func. update room
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('update-room');

        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-room') { // Admin's func. delete room
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('delete-room');

        return $request;
    }

    // transaction
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/transactions') { // get transactions by user
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('transactions');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-transaction') { // add transactions && filling the wallet
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('create-transaction');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-transaction') { //Admin func. delete transaction
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('delete-transaction');
        return $request;
    }

    // service
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/services') { //get All services
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('services');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-service') {// admin. add service
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('create-service');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-service') {//admin. update service
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('update-service');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-service') {//admin. delete service
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('delete-service');
        return $request;
    }

    // session
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-session-time') {
        $controller = new SessionController($db, $method);
        $request = $controller->processRequest('update-session-time');
        return $request;
    }

    else {
        header("HTTP/1.1 404 Not Found");
        return ['error' => 'Route not found'];
    }
}

?>