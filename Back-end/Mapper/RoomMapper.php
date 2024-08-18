<?php
require_once __DIR__ . '/../model/Room.php';

class RoomMapper{
    private $conn;
    private $table_name = 'rooms';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    
    public function getRoomTypes() {
        try {//need paging util
            $query = "SELECT DISTINCT room_type 
                        FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
            return $users;
        } catch (PDOException $e) {
            error_log("Error in getRooms: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getRooms(string $status) {
        try {
            $rooms = [];

            $query = "SELECT * FROM " . $this->table_name;

            if ($status !== null) {
                $query .= " WHERE status = :status";
            }

            $query .= " ORDER BY room_number ASC";

            $stmt = $this->conn->prepare($query);

            if ($status !== null) {
                $stmt->bindParam(':status', $status);
            }

            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rooms[] = $row;
            }

            return $rooms;
        } catch (PDOException $e) {
            error_log("Error in getRooms: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function createRoom(Room $room) {
        try {         
            $query = "INSERT INTO " . $this->table_name . " 
                (room_number, room_type, price_per_night, description, image_url, status) 
                value (:room_number, :room_type, :price_per_night, :description, :image_url, :status)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':room_number', $room->getRoomNumber());
            $stmt->bindParam(':room_type', $room->getRoomType());
            $stmt->bindParam(':price_per_night', $room->getPricePerNight());
            $stmt->bindParam(':description', $room->getDescription());
            $stmt->bindParam(':image_url', $room->getImageUrl());
            $stmt->bindParam(':status', $room->getStatus());
            
            if ($stmt->execute()) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error in createRoom: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function updateRoom(Room $room) {
        try {            
            $fieldsToUpdate = [];
            $params = [':room_id' => $room->getRoomId()];            

            if ($room->getRoomNumber() !== null) {
                $fieldsToUpdate[] = "room_number = :room_number";
                $params[':room_number'] = $room->getRoomNumber();
            }

            if ($room->getRoomType() !== null) {
                $fieldsToUpdate[] = "room_type = :room_type";
                $params[':room_type'] = $room->getRoomType();
            }

            if ($room->getPricePerNight() !== null) {
                $fieldsToUpdate[] = "price_per_night = :price_per_night";
                $params[':price_per_night'] = $room->getPricePerNight();
            }

            if ($room->getDescription() !== null) {
                $fieldsToUpdate[] = "description = :description";
                $params[':description'] = $room->getDescription();
            }

            if ($room->getImageUrl() !== null) {
                $fieldsToUpdate[] = "image_url = :image_url";
                $params[':image_url'] = $room->getImageUrl();
            }

            if ($room->getStatus() !== null) {
                $fieldsToUpdate[] = "status = :status";
                $params[':status'] = $room->getStatus();
            }

            $fieldsToUpdate[] = "updated_at = CURRENT_TIMESTAMP";

            $query = "UPDATE " . $this->table_name . " SET " . implode(", ", $fieldsToUpdate) . " WHERE room_id = :room_id";

            $stmt = $this->conn->prepare($query);

            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in updateRoom: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function deleteRoom(Room $room) {
        try {            
            $query = "DELETE FROM " . $this->table_name . " WHERE room_id = :room_id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':room_id', $room->getRoomId());
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in deleteRoom: " . $e->getMessage());
            return $e->getMessage();
        }
    }
}

?>