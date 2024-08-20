<?php
require_once __DIR__ . '/../model/Room.php';
require_once __DIR__ . '/../mapper/RoomMapper.php';

class RoomController {
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest($param) {
        switch ($param) {
            case 'room-types':
                $response = $this->getRoomTypes();
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

    public function getRoomTypes() {
        try {
            $RoomMapper = new RoomMapper($this->db);
            $result = $RoomMapper -> getRoomTypes();
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Rooms: " . $e->getMessage()); // 에러 로그 추가
            return $this->jsonResponse(500, ["error" => "Error getting Rooms: " . $e->getMessage()]);
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
