<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../Utils/Paging.php';
class UserMapper{
    private $conn;
    private $table_name = 'users';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    public function getUserTotalCount():int {
        try {//need paging util
            $query = "SELECT COUNT(*) as count
                        FROM " . $this->table_name ."";
        // biding parameter
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
    
            $count = $stmt -> fetch(PDO::FETCH_ASSOC);
            return $count["count"];
        } catch (PDOException $e) {
            error_log("Error in getUsers: " . $e->getMessage());
            return 0;
        }
    }
    public function getUserList(Paging $paging, string $searchString="", string $searchType ="") {
        // Page per row
        $records_per_page = $paging -> getItemsPerPage();
        // cal OFFSET 
        $offset = $paging -> getOffset();

        try {//need paging util
            $query = "SELECT * FROM " . $this->table_name;
        if ($searchType=="username")
            $query .= " WHERE username LIKE '%".$searchString."%'";
        else if ($searchType=="email")
            $query .= " WHERE email LIKE '%".$searchString."%'";
        else if ($searchType=="role")
            $query .= " WHERE role LIKE '%".$searchString."%'";
            $query .= " ORDER BY user_id DESC 
                        LIMIT :limit OFFSET :offset";
        
        // biding <paramete></paramete>r
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $records_per_page, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
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

    public function getUsers() {
        try {//need paging util
            $query = "SELECT * 
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
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $user->getName());
        $stmt->bindParam(':email', $user->getEmail());
        $stmt->bindParam(':id', $user->getId());
        
        if ($stmt->execute()) {
        return true;
        }
        return false;
    }

    public function deleteUser(int $user_id) {
        $query = "DELETE FROM " . $this -> table_name . "
                    WHERE user_id = :id
                ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id",$user_id);

        $stmt->execute();
        if ($stmt->execute()) {
            return true;
            }
            return false;
    }

    //회원가입
    //insert
}

?>