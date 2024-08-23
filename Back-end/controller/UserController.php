<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../mapper/UserMapper.php';

class UserController {
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest($param) {
        switch ($param) {
            case 'add-user':
                $response = $this->createUser();
                break;
            case 'login':
                $response = $this->login();
                break;
            case 'init-locked':
                $response = $this->initLocked();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        return $response;
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

            $userMapper = new UserMapper($this->db);

            $user_count = $userMapper->getUserTotalCount();
            $pageObject = new Paging($currPage, $user_count, 20);
            $result = $userMapper->getUserList($pageObject, $searchString, $searchType);
            
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
    public function Login() {
        try {
            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            //Setting User Class
            $user = new User();

            $user->setEmail($input['email']);
            $user->setPasswordHash($input['password']); // password hash

            //is locked
            $isLockedCount = $userMapper -> isLocked($user ->getEmail());
            // 3rd lock permanent
            if($isLockedCount > 2){
                return $this->jsonResponse(500, ['fail'=> "permanent-lock"]);
            }else if ($isLockedCount > 0){
                //check time
                if(!$userMapper -> getLockedExpired($user->getEmail())) 
                return $this->jsonResponse(500, ['fail'=> $isLockedCount."th-lock"]);
            }

            //password verify
            if(password_verify($user->getPasswordHash(), $userMapper -> getPassword($user))) {
                //Set Session
                $email = $user->getEmail();
                $userInfo = $userMapper->getUserByEmail($email);
                $newUser = new User($userInfo['user_id'], $userInfo['username'], $userInfo['password_hash'], $userInfo['email'], $userInfo['role'], $userInfo['wallet_balance']);
                $session = new Session();
                $sid = $session->startSession($newUser);

                return $this->jsonResponse(200, ['sid'=> $sid]);
            }else {
                if($userMapper -> getFailedLoginAttempts($user -> getEmail()) > 4 || $userMapper -> isLocked($user ->getEmail()) > 0){
                    //update locked number
                    $userMapper -> updateIsLocked($user->getEmail());
                    //update Expire time
                    switch ($userMapper -> isLocked($user ->getEmail())) {
                        case 1:
                            $userMapper -> updateLockedExpire(4,$user->getEmail());
                            break;
                        case 2:
                            $userMapper -> updateLockedExpire(10,$user->getEmail());
                            break;
                    }
                    return $this->jsonResponse(500, ['fail'=> "isLocked"]);
                }
                $userMapper -> updateFailedLoginAttempts($user -> getEmail());
                return $this->jsonResponse(500, ["error" => "User verify fail"]);
            }
            
        } catch (PDOException $e) {
            error_log("Error Login: " . $e->getMessage()); // error log
            return $this->jsonResponse(500, ["error" => "Error getting Login: " . $e->getMessage()]);
        }
    }
    public function Logout() {
        $session = new Session();
        $session -> deleteSession();

        header("Location: /Tamwood-hotel/");
        exit();
    }
    public function createUser() {
        try {
            $role = unserialize($_SESSION['userClass']) -> getRole();
            if($role!='admin') throw new Exception("No permission");
            
            $userMapper = new UserMapper($this->db);
            $input = $_POST;
    
            // Depura los datos recibidos
            error_log(print_r($input, true));
    
            if (!isset($input['username']) || !isset($input['password_hash']) || !isset($input['email']) || !isset($input['role'])) {
                return $this->jsonResponse(400, ["error" => "Missing required fields"]);
            }
    
            // Crear usuario con los datos de entrada
            $user = new User();
            $user->setName($input['username']);
            $user->setPasswordHash(password_hash($input['password_hash'], PASSWORD_BCRYPT));
            $user->setEmail($input['email']);
            $user->setRole($input['role']);
    
            // Verificar que el email no esté duplicado
            if ($userMapper->verifyUserbyEmail($user->getEmail())) {
                if ($userMapper->createUser($user)) {
                    return $this->jsonResponse(201, ['message' => 'User Created']);
                } else {
                    throw new Exception("Failed to create user.");
                }
            } else {
                return $this->jsonResponse(400, ["error" => "User already exists"]);
            }
    
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error creating user: " . $e->getMessage()]);
        }
    }
    
    

    public function login() {
        try {
            $role = unserialize($_SESSION['userClass']) -> getRole();
            if($role!='admin') throw new Exception("No permission");

            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            // Verificar que el email y contraseña estén presentes
            if (!isset($input['email']) || !isset($input['password_hash'])) {
                return $this->jsonResponse(400, ["error" => "Email and password are required"]);
            }
        } catch (Exception $e) {
            error_log("Error updating user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error updating user: " . $e->getMessage()]);
        }
    }

    public function deleteUser() {
        try {
            $userMapper = new UserMapper($this->db);
            $user_id = $_POST["uid"];

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
    
    public function initLocked(){
        try {
            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            $user = new User();
            $user-> setId($input['uid']);
            //get session & check user is admin
            if ($userMapper -> initLocked($user->getId())) {
                return $this->jsonResponse(201, ['message' => 'Locked release']);
            } else {
                return $this->jsonResponse(401, ["error" => "Invalid credentials"]);
            }

        } catch (PDOException $e) {
            error_log("Error Login: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error during login: " . $e->getMessage()]);
        }
    }

    private function jsonResponse($statusCode, $data) {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        return json_encode($data);
    }

    private function notFoundResponse() {
        return $this->jsonResponse(404, ['message' => 'Not Found']);
    }
}
?>