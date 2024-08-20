<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../mapper/UserMapper.php';
require_once __DIR__ . '/../Utils/Paging.php';

class UserController{
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest($param) {
        switch ($param) {
            case 'user-list':
                $response = $this->getUserList();
                break;
            case 'users':
                $response = $this->getUsers();
                break;
            case 'add-user':
                $response = $this->createUser();
                break;
            case 'update-user':
                $response = $this->updateUser();
                break;
            case 'delete-user':
                $response = $this->deleteUser();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        print_r($response);
        // header($response['status_code_header']);
        // if ($response['body']) {
        //     echo $response['body'];
        // }
    }

    public function getUserList() {
        try {
            //$current_page, $searchString
            $currPage = isset($_GET['currentPage'])? $_GET['currentPage'] : 1;
            $searchString = isset($_GET['searchString'])? $_GET['searchString'] : ""; 
            $searchType = isset($_GET['searchType'])? $_GET['searchType'] : "";
            echo isset($input);
            $userMapper = new UserMapper($this->db);

            $user_count = $userMapper->getUserTotalCount();
            $pageObject = new Paging($currPage, $user_count, 20);
            $result = $userMapper->getUserList($pageObject, $currPage, $searchString, $searchType,  20);
            
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting users: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting users: " . $e->getMessage()]);
        }
    }
    public function getUsers() {
        try {
            $userMapper = new UserMapper($this->db);
            $result = $userMapper->getUsers();
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting users: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting users: " . $e->getMessage()]);
        }
    }
    
    public function createUser() {
        try {
            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            $user = new User();
            $user -> setName($input['name']);
            $user-> setEmail($input['email']);
            $user -> setRole($input['role']);

            if ($userMapper -> createUser($user)) {
                return $this->jsonResponse(201, ['message' => 'User Created']);
            } else {
                throw new Exception("Failed to create user.");
            }
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error creating user: " . $e->getMessage()]);
        }
    }

    public function updateUser() {
        try {
            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            $user = new User();
            $user -> setName($input['name']);
            $user-> setEmail($input['email']);

            if ($userMapper -> updateUser($user)) {
                return $this->jsonResponse(201, ['message' => 'User Updated']);
            } else {
                throw new Exception("Failed to update user.");
            }
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error updating user: " . $e->getMessage()]);
        }
    }

    public function deleteUser() {
        try {
            $userMapper = new UserMapper($this->db);
            $user_id = $_POST["user_id"];

            if ($userMapper -> deleteUser($user_id)) {
                return $this->jsonResponse(201, ['message' => 'User Updated']);
            } else {
                throw new Exception("Failed to delete user.");
            }
        } catch (Exception $e) {
            error_log("Error deleting user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error deleting user: " . $e->getMessage()]);
        }
    }
    
    private function jsonResponse($statusCode, $data) {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        return json_encode($data);
    }
    
    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(['message' => 'Not Found']);
        return $response;
    }
}

?>
