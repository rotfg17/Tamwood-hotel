<?php
require_once __DIR__ . '/../model/Room.php';

class RoomMapper {
    private $conn;
    private $table_name = 'rooms';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    
    public function getRoomTypes() {
        try {
            $query = "SELECT DISTINCT room_number, room_type, price_per_night, 
            description, image_url, status FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $roomTypes = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $roomTypes[] = $row;
            }
            return $roomTypes;
        } catch (PDOException $e) {
            error_log("Error in getRoomTypes: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getRooms(mixed $status) {
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
    public function getAvailableRooms($selectedCheckInDate, $selectedCheckOutDate) {
        try{
            $query = "SELECT * 
                    FROM rooms 
                    WHERE room_id NOT IN (
                        SELECT room_id
                        FROM bookings 
                        WHERE UNIX_TIMESTAMP(check_in_date) < UNIX_TIMESTAMP(:selectedCheckOutDate) 
                        AND UNIX_TIMESTAMP(check_out_date) > UNIX_TIMESTAMP(:selectedCheckInDate)
                    ) 
                    AND status = 'available'";
                    
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':selectedCheckInDate', $selectedCheckInDate );
            $stmt->bindParam(':selectedCheckOutDate', $selectedCheckOutDate );

            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $rooms[] = $row;
            }
            return $rooms;
        } catch (PDOException $e) {
            error_log("Error in getAvailableRooms(): " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function createRoom(Room $room):int | bool {
        try {         
            $query = "INSERT INTO " . $this->table_name . " 
                (room_number, room_type, price_per_night, description, image_url, status) 
                VALUES (:room_number, :room_type, :price_per_night, :description, :image_url, :status)";

            $stmt = $this->conn->prepare($query);

            // Asigna el valor a una variable antes de pasarlo a bindParam
            $roomNumber = $room->getRoomNumber();
            $roomType = $room->getRoomType();
            $pricePerNight = $room->getPricePerNight();
            $description = $room->getDescription();
            $imageUrl = $room->getImageUrl();
            $status = $room->getStatus();

            $stmt->bindParam(':room_number', $roomNumber);
            $stmt->bindParam(':room_type', $roomType);
            $stmt->bindParam(':price_per_night', $pricePerNight);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image_url', $imageUrl);
            $stmt->bindParam(':status', $status);
            
            if ($stmt->execute()) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Error in createRoom: " . $e->getMessage());
        }
    }

    public function updateRoom(Room $room) {
        try {            
            $fieldsToUpdate = [];
            $params = [':room_id' => $room->getRoomId()];            

            $roomNumber = $room->getRoomNumber();
            if ($roomNumber !== null) {
                $fieldsToUpdate[] = "room_number = :room_number";
                $params[':room_number'] = $roomNumber;
            }

            $roomType = $room->getRoomType();
            if ($roomType !== null) {
                $fieldsToUpdate[] = "room_type = :room_type";
                $params[':room_type'] = $roomType;
            }

            $pricePerNight = $room->getPricePerNight();
            if ($pricePerNight !== null) {
                $fieldsToUpdate[] = "price_per_night = :price_per_night";
                $params[':price_per_night'] = $pricePerNight;
            }

            $description = $room->getDescription();
            if ($description !== null) {
                $fieldsToUpdate[] = "description = :description";
                $params[':description'] = $description;
            }

            $imageUrl = $room->getImageUrl();
            if ($imageUrl !== null) {
                $fieldsToUpdate[] = "image_url = :image_url";
                $params[':image_url'] = $imageUrl;
            }

            $status = $room->getStatus();
            if ($status !== null) {
                $fieldsToUpdate[] = "status = :status";
                $params[':status'] = $status;
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

            $roomId = $room->getRoomId();
            $stmt->bindParam(':room_id', $roomId);
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in deleteRoom: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function getRoomPrice(int $roomId, int $interval) {
        try {
            $query = "SELECT price_per_night * :interval FROM " . $this->table_name . "
                    WHERE room_id =:room_id";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(":interval", $interval);
            $stmt->bindParam(":room_id", $roomId);

            $stmt->execute();
    
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in getRoomTypes: " . $e->getMessage());
            return $e->getMessage();
        }
    }
    
}
?>
