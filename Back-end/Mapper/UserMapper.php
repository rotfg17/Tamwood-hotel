<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../Utils/Paging.php';

class UserMapper {
    private $conn;
    private $table_name = 'users';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }

    public function getUserTotalCount(): int {
        try {
            $query = "SELECT COUNT(*) as count FROM " . $this->table_name;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("Error in getUserTotalCount: " . $e->getMessage());
            return 0;
        }
    }

    public function getUserList(Paging $paging, string $searchString = "", string $searchType = ""): array {
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

    public function getUsers(): array {
        try {
            $query = "SELECT * FROM " . $this->table_name . " ORDER BY user_id DESC";
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
    
    public function verifyUserbyEmail(string $email): bool {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
            
        return $stmt->fetchColumn() > 0 ? false : true;
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
    
        $username = $user->getName();
        $password_hash = $user->getPasswordHash();
        $email = $user->getEmail();
        $role = $user->getRole();
        
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);
        
        return $stmt->execute();
    }

    public function updateUser(User $user): bool {
        $query = "UPDATE " . $this->table_name . " 
                        SET 
                            username = :name
                        WHERE 
                            user_id = :id";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':name', $user->getName());
        $stmt->bindParam(':id', $user->getId());
        
        return $stmt->execute();
    }

    public function deleteUser(int $user_id): bool {
        $query = "DELETE FROM " . $this->table_name . "
                    WHERE user_id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id", $user_id);

        return $stmt->execute();
    }

    public function getPassword(User $user) {
        $query = "SELECT password_hash FROM " . $this->table_name . " WHERE email = :email";
    
        $stmt = $this->conn->prepare($query);
    
        $email = $user->getEmail();
        $stmt->bindParam(':email', $email);
    
        $stmt->execute();
    
        return $stmt->fetchColumn();
    }
    
    public function updateLockedExpire(int $hour, string $email): bool {
        $query = "UPDATE " . $this->table_name . "
                     SET locked_expire = DATE_ADD(NOW(), INTERVAL :hour HOUR)
                     WHERE email = :email";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":hour", $hour, PDO::PARAM_INT);
        $stmt->bindParam(":email", $email);
    
        $result = $stmt->execute();
    
        if (!$result) {
            error_log("Failed to update locked_expire for email: " . $email);
        } else {
            error_log("Successfully updated locked_expire for email: " . $email);
        }
    
        return $result;
    }
    

    public function updateFailedLoginAttempts(string $email): bool {
        $query = "UPDATE " . $this->table_name . " 
                     SET 
                         failed_login_attempts = failed_login_attempts + 1
                     WHERE 
                         email = :email";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':email', $email);
        $result = $stmt->execute();
    
        if (!$result) {
            error_log("Failed to update failed_login_attempts for email: " . $email);
        } else {
            error_log("Successfully updated failed_login_attempts for email: " . $email);
        }
    
        return $result;
    }

    public function getFailedLoginAttempts(string $email) {
        $query = "SELECT failed_login_attempts FROM " . $this->table_name . " WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->fetchColumn();
    }

    public function lockUserPermanently(string $email): bool {
        $query = "UPDATE " . $this->table_name . " 
                     SET 
                         is_locked = 1
                     WHERE 
                         email = :email";
        $stmt = $this->conn->prepare($query);
     
        $stmt->bindParam(':email', $email);
        return $stmt->execute();
    }

    public function unlockUser(int $user_id): bool {
        $query = "UPDATE " . $this->table_name . " 
                    SET 
                        is_locked = 0, 
                        failed_login_attempts = 0, 
                        locked_expire = NULL
                    WHERE 
                        user_id = :user_id";
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }
    public function isUserLocked(string $email): bool {
        $query = "SELECT is_locked FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $lockedStatus = $stmt->fetchColumn();
        error_log("Account locked status for " . $email . ": " . $lockedStatus);
    
        return $lockedStatus == 1;
    }
    

    public function getLockedExpired(string $email): bool {
        $query = "SELECT locked_expire FROM " . $this->table_name . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        $lockedExpire = $stmt->fetchColumn();
        
        if ($lockedExpire && strtotime($lockedExpire) > time()) {
            error_log("User account is still temporarily locked for email: " . $email);
            return false; // Temporarily locked
        } else {
            return true; // Not locked or lock has expired
        }
    }
    

    public function getUserByEmail(string $email): mixed {
        try {
            $query = "SELECT * 
                      FROM " . $this->table_name . 
                      " WHERE email = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);            
        } catch (PDOException $e) {
            error_log("Error in getUserByEmail: " . $e->getMessage());
            return [];
        }
    }

    public function initLocked(int $user_id): bool {
        $query = "UPDATE ". $this->table_name ."
                    SET failed_login_attempts = 0,
                        locked_expire = NULL,
                        is_locked = 0
                    WHERE user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(":user_id", $user_id);
    
        return $stmt->execute();
    }

    public function updateWalletBalance(Transaction $transaction): bool {
        $query = "UPDATE ". $this->table_name;
        if($transaction->getTransactionType() == "deposit") {
            $query .= " SET wallet_balance = wallet_balance + :price";
        } else if($transaction->getTransactionType() == "payment") {
            $query .= " SET wallet_balance = wallet_balance - :price";
        }
        $query .= " WHERE user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":price", $transaction->getAmount());
        $stmt->bindParam(":user_id", $transaction->getUserId());
    
        return $stmt->execute();
    }


}
?>