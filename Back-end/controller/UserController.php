<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../mapper/UserMapper.php';
require_once __DIR__ . '/../Utils/Paging.php';
require_once __DIR__ . '/../Utils/Util.php';

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
            case 'login':
                $response = $this->login();
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

            $data = ["result"=>$userMapper->getUserList($pageObject, $searchString, $searchType),
                    "pagination" => $pageObject->getPaginationLinks($_SERVER['REDIRECT_URL'])];
            
            return $this->jsonResponse(200, $data);
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
            $util = new Util();
            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            //Setting User Class
            $user = new User();

            $user-> setEmail($input['email']);
            $user -> setPasswordHash($input['password']); // password hash

            //is locked
            $isFailedCount = $userMapper -> getFailedLoginAttempts($user ->getEmail());
            if ($isFailedCount == 2){
                //check time
                if(!$userMapper -> getLockedExpired($user->getEmail())) {
                    $util -> Audit_Gen($_SERVER,true,$user->getEmail()."1th-lock");
                    return $this->jsonResponse(500, ['fail'=> "1th-lock"]);
                }
            }
            else if ($isFailedCount == 4){
                //check time
                if(!$userMapper -> getLockedExpired($user->getEmail())) {
                    $util -> Audit_Gen($_SERVER,true,$user->getEmail()."2th-lock");
                    return $this->jsonResponse(500, ['fail'=> "2th-lock"]);
                }
            }
            else if($isFailedCount == 5){
                $util -> Audit_Gen($_SERVER,true,$user->getEmail()." permanent-lock.");
                return $this->jsonResponse(500, ['fail'=> "permanent-lock"]);
            }

            //password verify
            if(password_verify($user->getPasswordHash(), $userMapper -> getPassword($user))) {
                $newUser = $userMapper -> getUserByEmail($user->getEmail());
                //Set Session
                $email = $user->getEmail();
                $userInfo = $userMapper->getUserByEmail($email);
                $newUser = new User($userInfo['user_id'], $userInfo['username'], $userInfo['password_hash'], $userInfo['email'], $userInfo['role'], $userInfo['wallet_balance']);
                $userMapper -> initLocked($newUser->getId());
                
                $session = new Session();

                $sid = $session->startSession($newUser);

                $util -> Audit_Gen($_SERVER,true,$user->getEmail()." Success Login");
                return $this->jsonResponse(200, ['sid'=> $sid]);
                
            }else {

                //failed_login_attempts++
                $userMapper -> updateFailedLoginAttempts($user -> getEmail()); 

                //update Expire time
                switch ($userMapper -> getFailedLoginAttempts($user -> getEmail())) {
                    case 2:
                        $userMapper -> updateLockedExpire(4,$user->getEmail());
                        break;
                    case 4:
                        $userMapper -> updateLockedExpire(10,$user->getEmail());
                        break;
                    default:
                        break;
                }

                $util -> Audit_Gen($_SERVER,true,$user->getEmail()." User verify fail");
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
            $util = new Util();
            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            //Setting User Class
            $user = new User();

            $user -> setName($input['name']);
            $user -> setPasswordHash(password_hash($input['password'], PASSWORD_BCRYPT)); // password hash
            $user-> setEmail($input['email']);
            $user -> setRole('customer');

            //verify email - duplicate test
            if($userMapper -> verifyUserbyEmail($user -> getEmail())){
                if ($userMapper -> createUser($user)) {
                    return $this->jsonResponse(201, ['message' => 'User Created']);
                } else {
                    throw new Exception("Failed to create user.");
                }
            } else {
                $util -> Audit_Gen($_SERVER,true,$user->getEmail()." User already exist");
                return $this->jsonResponse(500, ['message' => 'User already exist']);
            }

        } catch (Exception $e) {
            error_log("Error creating user: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error creating user: " . $e->getMessage()]);
        }
    }

    public function updateUser() {
        try {
            $role = unserialize($_SESSION['userClass']) -> getRole();
            if($role!='admin') throw new Exception("No permission");

            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            $user = new User();
            $user -> setName($input['name']);
            $user-> setId($input['uid']);

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
            $role = unserialize($_SESSION['userClass']) -> getRole();
            if($role!='admin') throw new Exception("No permission");

            $userMapper = new UserMapper($this->db);
            $user_id = $_POST["uid"];

            if ($userMapper -> deleteUser($user_id)) {
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
            $role = unserialize($_SESSION['userClass']) -> getRole();
            if($role!='admin') throw new Exception("No permission");

            $userMapper = new UserMapper($this->db);
            $input = $_POST;

            $user = new User();
            $user-> setId($input['uid']);
            //get session & check user is admin
            if ($userMapper -> initLocked($user->getId())) {
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
