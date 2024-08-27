<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../mapper/UserMapper.php';
require_once __DIR__ . '/../Utils/Paging.php';
require_once __DIR__ . '/../Utils/Util.php';

class UserController {
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
            case 'login':
                $response = $this->Login();
                break;
            case 'logout':
                $this->Logout();
                break;
            case 'init-locked':
                $response = $this->initLocked();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        return $response;
    }

    public function getUserList() {
        try {
            $currPage = isset($_GET['currentPage']) ? $_GET['currentPage'] : 1;
            $searchString = isset($_GET['searchString']) ? $_GET['searchString'] : ""; 
            $searchType = isset($_GET['searchType']) ? $_GET['searchType'] : "";

            $userMapper = new UserMapper($this->db);

            $user_count = $userMapper->getUserTotalCount();
            $pageObject = new Paging($currPage, $user_count, 20);

            $data = [
                "result" => $userMapper->getUserList($pageObject, $searchString, $searchType),
                "pagination" => $pageObject->getPaginationLinks($_SERVER['REDIRECT_URL'])
            ];
            
            return $this->jsonResponse(200, $data);
        } catch (PDOException $e) {
            error_log("Error getting users: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting users: " . $e->getMessage()]);
        }
    }

    public function getUsers() {
        try {
            $userMapper = new UserMapper($this->db);
            $result = $userMapper->getUsers();
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting users: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting users: " . $e->getMessage()]);
        }
    }

    public function Login() {
        try {
            $util = new Util();
            $userMapper = new UserMapper($this->db);
            $input = $_POST;
    
            // Setting User Class
            $user = new User();
            $user->setEmail($input['email']);
            $user->setPasswordHash($input['password']); // password hash
    
            // Check if user is permanently locked
            if ($userMapper->isUserLocked($user->getEmail())) {
                return $this->jsonResponse(403, ["error" => "User account is permanently locked, contact admin"]);
            }
    
            // Check if the user is temporarily locked
            if (!$userMapper->getLockedExpired($user->getEmail())) {
                // If locked, return lock message
                return $this->jsonResponse(403, ["error" => "User account is temporarily locked, please try again later"]);
            }
    
            // Password verification
            if (password_verify($user->getPasswordHash(), $userMapper->getPassword($user))) {
                $userInfo = $userMapper->getUserByEmail($user->getEmail());
                $newUser = new User($userInfo['user_id'], $userInfo['username'], $userInfo['password_hash'], $userInfo['email'], $userInfo['role'], $userInfo['wallet_balance']);
                $session = new Session();
    
                $sid = $session->startSession($newUser);
    
                // Reset failed login attempts after successful login
                $userMapper->initLocked($userInfo['user_id']);
    
                return $this->jsonResponse(200, ['sid' => $sid]);
    
            } else {
                // Handle failed login attempts and lock logic here
                return $this->handleFailedLogin($user, $userMapper, $util);
            }
    
        } catch (PDOException $e) {
            return $this->jsonResponse(500, ["error" => "Error Login: " . $e->getMessage()]);
        }
    }
    
    private function handleFailedLogin($user, $userMapper, $util) {
        // Get the current number of failed login attempts
        $isFailedCount = $userMapper->getFailedLoginAttempts($user->getEmail());
    
        // Increment the failed login attempts
        $userMapper->updateFailedLoginAttempts($user->getEmail());
    
        if ($isFailedCount >= 4) { // On the 5th failed attempt, lock the account permanently
            // Lock the user permanently
            $userMapper->lockUserPermanently($user->getEmail());
            return $this->jsonResponse(403, ['error' => "User account is permanently locked, contact admin"]);
        } elseif ($isFailedCount >= 2) { // On the 3rd to 4th attempt, lock the account temporarily
            $lockDuration = 4; // Lock for 4 hours after 2 failed attempts
            $userMapper->updateLockedExpire($lockDuration, $user->getEmail());
            return $this->jsonResponse(403, ['error' => "User account is temporarily locked, please try again later"]);
        } else {
            return $this->jsonResponse(401, ["error" => "User verify fail"]);
        }
    }
    
    
    
    

    public function Logout() {
        $session = new Session();
        $session->deleteSession();

        header("Location: /Tamwood-hotel/");
        exit();
    }

    public function createUser() {
        try {
            $util = new Util();
            $userMapper = new UserMapper($this->db);
            $input = $_POST;
    
            // Setting User Class
            $user = new User();
            $user->setName($input['name']);
            $user->setPasswordHash(password_hash($input['password'], PASSWORD_BCRYPT));
            $user->setEmail($input['email']);
            $user->setRole('c');
    
            // Verify email - duplicate test
            if($userMapper->verifyUserbyEmail($user->getEmail())) {
                if ($userMapper->createUser($user)) {
                   $util->Audit_Gen($_SERVER, true, $user->getEmail()." User created");
    
                   // Aquí deberías generar o recuperar el SID
                   // Supongamos que tienes un método para generarlo o recuperarlo:
                   $sid = $this->generateSessionID($user);
    
                   return $this->jsonResponse(201, ['success' => 'User created successfully.', 'sid' => $sid]);
                } else {
                    throw new Exception("Failed to create user.");
                }
            } else {
                $util->Audit_Gen($_SERVER, true, $user->getEmail()." User already exists");
                return $this->jsonResponse(409, ['error' => 'User already exists']);
            }
    
        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error creating user: " . $e->getMessage()]);
        }
    }
    
    private function generateSessionID($user) {
        // Aquí podrías implementar la lógica para generar o recuperar el SID
        // Ejemplo simple:
        return session_id(); // o algún otro mecanismo para generar el SID
    }
    
    

    public function updateUser() {
        try {
            $role = unserialize($_SESSION['userClass'])->getRole();
            if($role != 'admin') throw new Exception("No permission");

            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            $user = new User();
            $user->setName($input['name']);
            $user->setId($input['uid']);

            if ($userMapper->updateUser($user)) {
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
            $role = unserialize($_SESSION['userClass'])->getRole();
            if($role != 'admin') throw new Exception("No permission");

            $userMapper = new UserMapper($this->db);
            $user_id = $_POST["uid"];

            if ($userMapper->deleteUser($user_id)) {
                return $this->jsonResponse(201, ['message' => 'User Deleted']);
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
            $role = unserialize($_SESSION['userClass'])->getRole();
            if($role != 'admin') throw new Exception("No permission");

            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            $user = new User();
            $user->setId($input['uid']);

            if ($userMapper->initLocked($user->getId())) {
                return $this->jsonResponse(201, ['message' => 'Locked release']);
            } else {
                throw new Exception("Failed to update user.");
            }
        } catch (Exception $e) {
            error_log("Error init Lock: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error init Lock: " . $e->getMessage()]);
        }
    }

    private function jsonResponse($statusCode, $data) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode(['sessionStatus' => null, 'data' => $data]);
        exit;
    }
    
    private function notFoundResponse() {
        return $this->jsonResponse(404, ['message' => 'Not Found']);
    }
}
?>