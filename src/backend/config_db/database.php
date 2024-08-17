<?php

class Database{
    private $hostname="localhost";
    private $database="hotel_reservation";
    private $username="root";
    private $password="1234";
    private $charset = "utf8";
    


function conexion()
{
    try {
        $db = "mysql:host=" . $this->hostname . "; dbname=" . $this->database . "; charset=" . $this->charset;

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false
        ];

        $pdo = new PDO($db, $this->username, $this->password, $options);

        return $pdo;
    }catch (PDOException $error){
        echo "Conexion failed: " . $error->getMessage();
        exit;
    }

    }

}