<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../mapper/UserMapper.php';
require_once __DIR__ . '/../config/Session.php'; // Asegúrate de incluir la clase de sesión si no estaba incluida

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
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        return $response;
    }

    // Método para crear un nuevo usuario
    public function createUser() {
        try {
            $userMapper = new UserMapper($this->db);
            $input = json_decode(file_get_contents('php://input'), true); // Leer la entrada JSON
    
            if (!$input || !isset($input['name'], $input['password_hash'], $input['email'], $input['role'])) {
                return $this->jsonResponse(400, ["error" => "All fields are required"]);
            }
    
            // Crear usuario con los datos de entrada
            $user = new User();
            $user->setName($input['name']);
            $user->setPasswordHash(password_hash($input['password_hash'], PASSWORD_BCRYPT)); // Hash de contraseña
            $user->setEmail($input['email']);
            $user->setRole($input['role']);
    
            // Verificar que el email no esté duplicado
            if ($userMapper->verifyUserbyEmail($user->getEmail())) {
                if ($userMapper->createUser($user)) {
                    $session = new Session();
                    $sid = $session->startSession($user);
                    return $this->jsonResponse(201, ['sid' => $sid]);
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
    

    // Método para manejar el inicio de sesión
    public function login() {
        try {
            $userMapper = new UserMapper($this->db);
            $input = json_decode(file_get_contents('php://input'), true); // Leer la entrada JSON
    
            // Verificar que el email y la contraseña estén presentes
            if (!isset($input['email']) || !isset($input['password_hash'])) {
                return $this->jsonResponse(400, ["error" => "Email and password are required"]);
            }
    
            // Obtener información del usuario por email
            $userInfo = $userMapper->getUserByEmail($input['email']);
            if (!$userInfo) {
                return $this->jsonResponse(401, ["error" => "User not found"]);
            }
    
            // Verificar la contraseña
            if (password_verify($input['password_hash'], $userInfo['password_hash'])) {
                // Contraseña correcta, iniciar sesión
                $user = new User($userInfo['user_id'], $userInfo['username'], $userInfo['password_hash'], $userInfo['email'], $userInfo['role']);
                $session = new Session();
                $sid = $session->startSession($user);
    
                return $this->jsonResponse(200, ['sid' => $sid]);
            } else {
                return $this->jsonResponse(401, ["error" => "Invalid credentials"]);
            }
    
        } catch (PDOException $e) {
            error_log("Error Login: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error during login: " . $e->getMessage()]);
        }
    }
    
    

    // Método para retornar una respuesta JSON
    private function jsonResponse($statusCode, $data) {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        return json_encode($data);
    }

    // Método para manejar respuestas no encontradas
    private function notFoundResponse() {
        return $this->jsonResponse(404, ['message' => 'Not Found']);
    }
}
?>