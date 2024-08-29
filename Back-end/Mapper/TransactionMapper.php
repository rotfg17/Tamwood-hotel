<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Transaction.php';

class TransactionMapper{
    private $conn;
    private $table_name = 'wallet_transactions';

    public function __construct($conn=null) {
        $this->conn = $conn->getConnection();
    }
    
    public function getUserTransactions(User $user) {
        try {
            $transactions = [];
            
            $query = "SELECT * FROM " . $this->table_name . " WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $user->getId());
            
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $transactions[] = $row;
            }

            return $transactions;
        } catch (PDOException $e) {
            error_log("Error in getUserTransactions: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function createUserTransaction(Transaction $transaction) {
        try {            
            $query = "INSERT INTO " . $this->table_name . " 
                (user_id, transaction_type, amount, description) 
                values (:user_id, :transaction_type, :amount, :description)";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':user_id', $transaction->getUserId());
            $stmt->bindParam(':transaction_type', $transaction->getTransactionType());
            $stmt->bindParam(':amount', $transaction->getAmount(), PDO::PARAM_INT);
            $stmt->bindParam(':description', $transaction->getDescription());
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in createUserTransaction: " . $e->getMessage());
            return $e->getMessage();
        }
    }

    public function deleteUserTransaction(Transaction $transaction) {
        try {            
            $query = "DELETE FROM " . $this->table_name . " WHERE transaction_id = :transaction_id";

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':transaction_id', $transaction->getTransactionId());
            
            if ($stmt->execute()) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error in deleteUserTransaction: " . $e->getMessage());
            return $e->getMessage();
        }
    }
}

?>