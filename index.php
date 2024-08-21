<?php

require_once './Back-end/config/Database.php';
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
        return json_encode($controller->processRequest('add-user'));
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/login') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('login'));
    } 
    //UserController
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/user-list') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('user-list'));
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/users') {
        $controller = new UserController($db, $method);
        return json_encode($controller->processRequest('users'));
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-user') {
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
    //Comment
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/comment-list') {
        $controller = new CommentController($db, $method);
        return json_encode($controller->processRequest('comment-list'));
    } 
    else if ($method === 'GET' && $parsedPath === $ROOT_PATH.'api/comments') {
        $controller = new CommentController($db, $method);
        return json_encode($controller->processRequest('comments'));
    } else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/add-comment') {
        $controller = new CommentController($db, $method);
        return  json_encode($controller->processRequest('add-comment'));
    } 
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/update-comment') {
        $controller = new CommentController($db, $method);
        return json_encode($controller->processRequest('update-comment'));
    }
    else if ($method === 'POST' && $parsedPath === $ROOT_PATH.'api/delete-comment') {
        $controller = new CommentController($db, $method);
        return json_encode($controller->processRequest('delete-comment'));
    }
    //RoomController
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
    header("Access-Control-Allow-Origin: *");
    
    // session_start();

    // $value = isset($_SERVER['HTTP_X_SESSION_VALUE']) ? $_SERVER['HTTP_X_SESSION_VALUE'] : null;

    // if ($value === 'tamwood-hotel:)') {
    //     if (isset($_SESSION['timeout'])) {
    //         if ($_SESSION['timeout'] > time()) {
    //             echo 'renew';
    //             $_SESSION['timeout'] = time() + 1000;
    //         } else {
    //             echo 'destroy';
    //             session_unset();
    //             session_destroy();
    //         }
    //     } else {
    //         echo 'add things';
    //         $_SESSION['timeout'] = time() + 3600;
    //     }
    // } else {
    //     throw new Exception("Wrong session keys", 406);
    // }

    route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    
} catch(Exception $error) {
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(['error' => $error->getMessage()]);
}
?>