<?php

require_once './Back-end/config/Database.php';
require_once './Back-end/config/Session.php';
require_once './Back-end/controller/UserController.php';
require_once './Back-end/controller/BookingController.php';
require_once './Back-end/controller/CommentController.php';
require_once './Back-end/controller/RoomController.php';
require_once './Back-end/controller/TransactionController.php';
require_once './Back-end/controller/ServiceController.php';

function route($method, $path) {
    $parsedPath = parse_url($path, PHP_URL_PATH);
    $db = new Database();

    $ROOT_PATH = '/Tamwood-hotel/';

    if ($method === 'GET' && $parsedPath === $ROOT_PATH) {
        echo "index.php";
    } 
    //Login & Register 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/register') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('add-user');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/login') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('login');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/init-locked') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('init-locked');
    } 
    //UserController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/user-list') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('user-list');
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/users') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('users');
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-user') {
        $controller = new UserController($db, $method);
        $request = $controller->processRequest('add-user');
        return $request;
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-user') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('update-user');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-user') {
        $controller = new UserController($db, $method);
        return $controller->processRequest('delete-user');
    }
    //BookingController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/booking-list') {
        $controller = new BookingController($db, $method);
        return $controller->processRequest('booking-list');
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/bookings') {
        $controller = new BookingController($db, $method);
        return $controller->processRequest('bookings');
    }
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/booking-info') {
        $controller = new BookingController($db, $method);
        return $controller->processRequest('booking-info');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-booking') {
        $controller = new BookingController($db, $method);
        return $controller->processRequest('create-booking');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-booking-status') {
        $controller = new BookingController($db, $method);
        return $controller->processRequest('update-booking-status');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-booking') {
        $controller = new BookingController($db, $method);
        return $controller->processRequest('delete-booking');
    }
    //Comment
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/comment-list') {
        $controller = new CommentController($db, $method);
        return $controller->processRequest('comment-list');
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/comments') {
        $controller = new CommentController($db, $method);
        return $controller->processRequest('comments');
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-comment') {
        $controller = new CommentController($db, $method);
        return  $controller->processRequest('add-comment');
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-comment') {
        $controller = new CommentController($db, $method);
        return $controller->processRequest('update-comment');
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-comment') {
        $controller = new CommentController($db, $method);
        return $controller->processRequest('delete-comment');
    }
    //RoomController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/room-type') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('room-types');

        return $request;
    }
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/rooms') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('rooms');

        return $request;
    }
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/available-room') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('available-room');

        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-room') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('create-room');

        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-room') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('update-room');

        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-room') {
        $controller = new RoomController($db, $method);
        $request = $controller->processRequest('delete-room');

        return $request;
    }

    // transaction
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/transactions') {
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('transactions');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-transaction') {
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('create-transaction');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-transaction') {
        $controller = new TransactionController($db, $method);
        $request = $controller->processRequest('delete-transaction');
        return $request;
    }

    // service
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/services') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('services');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/create-service') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('create-service');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-service') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('update-service');
        return $request;
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-service') {
        $controller = new ServiceController($db, $method);
        $request = $controller->processRequest('delete-service');
        return $request;
    }

    else {
        header("HTTP/1.1 404 Not Found");
        return ['error' => 'Route not found'];
    }
}

// Real Request
try {
    header("Access-Control-Allow-Origin: *");
    
    $session = new Session();
    $sessionStatus = $session->getSession();

    $response = route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

    // Decode the JSON response
    $data = json_decode($response, true); // true converts the JSON object to associative array

    if (isset($data['result']) && $data['result'] === 'success') {
        $sessionStatus = $session->startSession();
    }

    echo json_encode([
        'sessionStatus' => $sessionStatus,
        'data' => $data, // Now $data is an array and not an object
    ]);
} catch(Exception $error) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['error' => $error->getMessage()]);
}

?>