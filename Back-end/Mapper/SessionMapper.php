<?php

class SessionMapper{
    private $conn;
    private $table_name = 'session';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }

    public function updateSessionTime(Session $session) {
        try {
            $query = "UPDATE " . $this->table_name . " SET session_value = :session_time WHERE session_title = 'session-time'";

            $stmt = $this->conn->prepare($query);
            $sessionTime = $session->getTimeout();
            $stmt->bindParam(':session_time', $sessionTime);
            
            if ($stmt->execute()) {
                $session->setTimeout($sessionTime);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in updateService: " . $e->getMessage());
            return $e->getMessage();
        }
    }
}

?>