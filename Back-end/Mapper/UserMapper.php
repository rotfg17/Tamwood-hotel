<?php
require_once __DIR__ . '/../model/User.php';
class UserMapper{
    private $conn;
    private $table_name = 'users';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    
    public function getUsers() {
        try {//need paging util
            $query = "SELECT user_id, username 
                        FROM " . $this->table_name. 
                        " ORDER BY user_id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $users = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $row;
            }
            return $users;
        } catch (PDOException $e) {
            error_log("Error in getUsers: " . $e->getMessage());
            return [];
        }
    }
    
    

    public function createUser(User $user) {
        $query = "INSERT INTO " . $this->table_name . "(
                                                            username,
                                                            password_hash,
                                                            email,
                                                            role,
                                                            is_locked,
                                                            failed_login_attempts,
                                                            wallet_balance
                                                        ) 
                                                values (
                                                            :name, 
                                                            'hashed-password',
                                                            :email,
                                                            :role,
                                                            0,
                                                            0,
                                                            0.0
                                                        )";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':name', $user->getName());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':role', $user->getRole());

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function updateUser(User $user){
        $query = "UPDATE " . $this->table_name . " 
                        SET 
                            username = :name, 
                            email = :email
                        WHERE 
                            user_id = :id
                            ";
        echo $query;
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $user->getName());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':id', $user->getId());
        
        if ($stmt->execute()) {
        return true;
        }
        return false;
            }
}

?>