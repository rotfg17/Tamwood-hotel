<?php
class Database {
    private $host = "localhost";
    private $db_name = "hotel_reservation";
    private $username = "root";
    private $password = "mysql";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        
        } catch (PDOException $exception) {
            throw new Exception ("Connection error: " . $exception->getMessage(), 500);
        }

        return $this->conn;
    }
}

?>