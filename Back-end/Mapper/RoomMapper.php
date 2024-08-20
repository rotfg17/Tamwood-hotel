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
            $query = "SELECT room_type 
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
            return [];
        }
    }
    
    

}

?>