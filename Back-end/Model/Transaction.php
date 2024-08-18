<?php
class Transaction {
    private $transaction_id;
    private $user_id;
    private $transaction_type;
    private $amount;
    private $transaction_date;
    private $description;

    // Constructor
    public function __construct($transaction_id = null, $user_id = null, $transaction_type = null, $amount = 0.0, $transaction_date = null, $description = null) {
        $this->transaction_id = $transaction_id;
        $this->user_id = $user_id;
        $this->transaction_type = $transaction_type;
        $this->amount = $amount;
        $this->transaction_date = $transaction_date;
        $this->description = $description;
    }

    // Getters
    public function getTransactionId() {
        return $this->transaction_id;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getTransactionType() {
        return $this->transaction_type;
    }

    public function getAmount() {
        return $this->amount;
    }

    public function getTransactionDate() {
        return $this->transaction_date;
    }

    public function getDescription() {
        return $this->description;
    }

    // Setters
    public function setTransactionId($transaction_id) {
        $this->transaction_id = $transaction_id;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setTransactionType($transaction_type) {
        $this->transaction_type = $transaction_type;
    }

    public function setAmount($amount) {
        $this->amount = $amount;
    }

    public function setTransactionDate($transaction_date) {
        $this->transaction_date = $transaction_date;
    }

    public function setDescription($description) {
        $this->description = $description;
    }
}

?>