<?php
require_once __DIR__ . '/../model/User.php';
require_once __DIR__ . '/../model/Transaction.php';
require_once __DIR__ . '/../mapper/TransactionMapper.php';

class TransactionController {
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest($param) {
        switch ($param) {
            case 'transactions':
                $response = $this->getUserTransactions();
                break;
            case 'create-transaction':
                $response = $this->createUserTransaction();
                break;
            case 'delete-transaction':
                $response = $this->deleteUserTransaction();
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        print_r($response);
        // header($response['status_code_header']);
        // if ($response['body']) {
        //     echo $response['body'];
        // }
    }

    public function getUserTransactions() {
        try {
            $transactionMapper = new TransactionMapper($this->db);
            $input = $_POST;
            
            $user = new User();
            $user->setId($input['user_id']);

            $result = $transactionMapper->getUserTransactions($user);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Rooms: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Rooms: " . $e->getMessage()]);
        }
    }

    public function createUserTransaction() {
        try {
            $transactionMapper = new TransactionMapper($this->db);
            $input = $_POST;
            
            $transaction = new Transaction();
            $transaction->setUserId($input['user_id']);
            $transaction->setTransactionType($input['transaction_type']);
            $transaction->setAmount($input['amount']);
            $transaction->setDescription($input['description']);

            $result = $transactionMapper->createUserTransaction($transaction);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Rooms: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Rooms: " . $e->getMessage()]);
        }
    }

    public function deleteUserTransaction() {
        try {
            $transactionMapper = new TransactionMapper($this->db);
            $input = $_POST;
            
            $transaction = new Transaction();
            $transaction->setTransactionId($input['transaction_id']);

            $result = $transactionMapper->deleteUserTransaction($transaction);

            return $this->jsonResponse(200, $result);
        } catch (PDOException $e) {
            error_log("Error getting Rooms: " . $e->getMessage());
            return $this->jsonResponse(500, ["error" => "Error getting Rooms: " . $e->getMessage()]);
        }
    }
    
    private function jsonResponse($statusCode, $data) {
        header("Content-Type: application/json");
        http_response_code($statusCode);
        return json_encode($data);
    }
    
    private function notFoundResponse() {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode(['message' => 'Not Found']);
        return $response;
    }
}

?>
