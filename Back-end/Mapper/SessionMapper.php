<?php

class SessionMapper{
    private $conn;
    private $table_name = 'session';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }

    public function getSessionTime() {
        try {
            
            $query = "SELECT session_value FROM " . $this->table_name ." WHERE session_title = 'session-time'";

            $stmt = $this->conn->prepare($query);

            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['session_value'];
        } catch (PDOException $e) {
            error_log("Error in getSession: " . $e->getMessage());
            return $e->getMessage();
        }
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