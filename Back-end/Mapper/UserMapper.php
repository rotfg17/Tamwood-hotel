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
            
            $count = $stmt->fetchColumn();
            return $count;
        } catch (PDOException $e) {
            error_log("Error in getUsers: " . $e->getMessage());
            return 0;
        }
    }
    public function getUserList(Paging $paging, string $searchString="", string $searchType =""):array {
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

    public function getUsers():array {
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
    

    public function verifyUserbyEmail(string $email):bool {
        $query = "SELECT count(*) as count FROM ".$this->table_name. " WHERE email=:email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
            
        $count = $stmt->fetchColumn();
        if($count > 0) return false;
        else return true;

    }
    public function createUser(User $user):bool {
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
    
        $stmt->bindParam(':username', $user->getName());
        $stmt->bindParam(':password_hash', $user->getPasswordHash());
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
                            username = :name
                        WHERE 
                            user_id = :id
                            ";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $user->getName());
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

        if ($stmt->execute()) {
            return true;
            }
            return false;
    }

    public function getPassword(User $user) {
        $query = "SELECT password_hash FROM ".$this -> table_name ." WHERE email = :email";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $user->getEmail());
        $stmt->execute();
        
        $pass=$stmt->fetchColumn();

        return $pass;
    }
    public function updateLockedExpire(int $hour, string $email) {
        $query = "UPDATE ". $this->table_name ."
                    SET locked_expire = DATE_ADD(NOW(), INTERVAL :hour HOUR)
                    WHERE email = :email";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":hour", $hour, PDO::PARAM_INT);
        $stmt->bindParam(":email", $email);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateFailedLoginAttempts(string $email) {
        $query = "UPDATE " . $this->table_name . " 
                    SET 
                        failed_login_attempts = failed_login_attempts + 1
                    WHERE 
                        email =:email
                        ";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $email);
        
        if ($stmt->execute()) {
        return true;
        }
        return false;

    }

    public function getFailedLoginAttempts(string $email) {
        $query = "SELECT failed_login_attempts FROM ".$this -> table_name ." WHERE email = :email";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $fail_num = $stmt->fetchColumn();

        return $fail_num;
    }

    public function getLockedExpired($email) {
        $query = "SELECT count(*) FROM ".$this->table_name." WHERE locked_expire < NOW() AND email = :email";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $result = $stmt->fetchColumn();
        if($result > 0) {
            return true;
        } else {
            return false;
        }
    }

    //
    public function initLocked(int $user_id) {
        $query = "UPDATE ". $this->table_name ."
                    SET failed_login_attempts = 0,
                        locked_expire = null
                    WHERE user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":user_id", $user_id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateWalletBalance( Transaction $transaction) {
        $query = "UPDATE ". $this->table_name ;
        if($transaction->getTransactionType() == "deposit"){
            $query .= " SET wallet_balance = wallet_balance + :price";
        }else if($transaction->getTransactionType() == "payment"){
            $query .= " SET wallet_balance = wallet_balance - :price";
        }
        $query .= " WHERE user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":price", $transaction->getAmount());
        $stmt->bindParam(":user_id", $transaction->getUserId());
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    public function getUserByEmail(string $email):mixed {
        try {
            $query = "SELECT * 
            FROM " . $this->table_name. 
            " WHERE email =:email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);            

            return $user;
        } catch (PDOException $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return [];
        }
    }
}

?>