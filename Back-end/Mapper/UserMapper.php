<?php
require_once __DIR__ . '/../model/User.php';

class UserMapper {
    private $conn;
    private $table_name = 'users';

    public function __construct($conn) {
        $this->conn = $conn->getConnection();
    }

    public function verifyUserbyEmail(string $email): bool {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE email=:email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
            
        $count = $stmt->fetchColumn();
        return $count == 0;
    }

    public function createUser(User $user): bool {
        $query = "INSERT INTO " . $this->table_name . " (
                                                            username,
                                                            password_hash,
                                                            email,
                                                            role
                                                        ) 
                                                        VALUES (
                                                            :username, 
                                                            :password_hash,
                                                            :email,
                                                            :role
                                                        )";
        $stmt = $this->conn->prepare($query);
        
        // Asignar los valores a variables antes de pasar por referencia
        $username = $user->getName();
        $passwordHash = $user->getPasswordHash();
        $email = $user->getEmail();
        $role = $user->getRole();
    
        // Vincular los parámetros
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
    
        // Ejecutar la consulta
        return $stmt->execute();
    }
    

    public function getUserByEmail(string $email) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return null;
        }
    }
}
?>