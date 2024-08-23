<?php
require_once __DIR__ . '/../mapper/SessionMapper.php';

class SessionController {
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest($param) {
        switch ($param) {
            case 'update-session-time':
                $response = $this->updateSessionTime();
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

    public function updateSessionTime() {
        try {
            $sessionMapper = new SessionMapper($this->db);
            $input = $_POST;

            $session = new Session();
            $session->setTimeout($input['time']);
            $result = $sessionMapper->updateSessionTime($session);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Services: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Services: " . $e->getMessage()]);
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