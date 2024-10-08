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
            case 'rooms':
                $response = $this->getRooms();
                break;
            case 'available-room':
                $response = $this->getAvailableRoom();
                break;
            case 'create-room':
                $response = $this->createRoom();
                break;
            case 'update-room':
                $response = $this->updateRoom();
                break;
            case 'delete-room':
                $response = $this->deleteRoom();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        
        return $response;
    }

    public function getRoomTypes() {
        try {
            $roomMapper = new RoomMapper($this->db);
            $result = $roomMapper->getRoomTypes();
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Room Types: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Room Types: " . $e->getMessage()]);
        }
    }

    public function getRooms() {
        try {
            $roomMapper = new RoomMapper($this->db);
            $status = isset($_GET['status']) ? $_GET['status'] : null;
            $result = $roomMapper->getRooms($status);
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Rooms: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Rooms: " . $e->getMessage()]);
        }
    }

    public function getAvailableRoom() {
        try {
            $roomMapper = new RoomMapper($this->db);
            $checkInDate = isset($_GET['checkInDate']) ? $_GET['checkInDate'] : null;
            $checkOutDate = isset($_GET['checkOutDate']) ? $_GET['checkOutDate'] : null;
            $result = $roomMapper->getAvailableRooms($checkInDate, $checkOutDate);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Available Rooms: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Available Rooms: " . $e->getMessage()]);
        }
    }

    public function createRoom() {
        try {
            $roomMapper = new RoomMapper($this->db);
            $input = $_POST;

            $room = new Room();
            $room->setRoomNumber($input['room_number']);
            $room->setRoomType($input['room_type']);
            $room->setPricePerNight($input['price_per_night']);
            $room->setDescription($input['description']);
            $room->setStatus($input['status']);
            $room->setImageUrl($room->uploadFile($_FILES['image']));
            
            $result = $roomMapper->createRoom($room);
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error creating Room: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error creating Room: " . $e->getMessage()]);
        }
    }

    public function updateRoom() {
        try {
            $roomMapper = new RoomMapper($this->db);
            $input = $_POST;

            if (!isset($input['room_id']) || !isset($input['room_number']) || !isset($input['room_type']) || !isset($input['price_per_night']) || !isset($input['description']) || !isset($input['status'])) {
                return $this->jsonResponse(400, ["error" => "Faltan datos requeridos para actualizar la habitación"]);
            }

            $room = new Room();
            $room->setRoomId($input['room_id']);
            $room->setRoomNumber($input['room_number']);
            $room->setRoomType($input['room_type']);
            $room->setPricePerNight($input['price_per_night']);
            $room->setDescription($input['description']);
            $room->setStatus($input['status']);
            if (isset($_FILES['image'])) {
                $room->setImageUrl($room->uploadFile($_FILES['image']));
            }

            $result = $roomMapper->updateRoom($room);
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error updating Room: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error updating Room: " . $e->getMessage()]);
        }
    }

    public function deleteRoom() {
        try {
            $roomMapper = new RoomMapper($this->db);
            $input = $_POST;

            if (!isset($input['room_id'])) {
                return $this->jsonResponse(400, ["error" => "Room ID is required for deletion"]);
            }

            $room = new Room();
            $room->setRoomId($input['room_id']);

            $result = $roomMapper->deleteRoom($room);
            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error deleting Room: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error deleting Room: " . $e->getMessage()]);
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