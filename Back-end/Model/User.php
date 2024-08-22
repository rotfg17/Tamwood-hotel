<?php
class User {
    private $id;
    protected $name;
    protected $password_hash;
    protected $email;
    protected $role;
    protected $is_locked;
    protected $failed_login_attempt;
    protected $wallet_balance;

    // Constructor
    public function __construct($id = null, $name = null, $password_hash = null, $email = null, $role = null, $is_locked = false, $failed_login_attempt = 0, $wallet_balance = 0.0) {
        $this->id = $id;
        $this->name = $name;
        $this->password_hash = $password_hash;
        $this->email = $email;
        $this->role = $role;
        $this->is_locked = $is_locked;
        $this->failed_login_attempt = $failed_login_attempt;
        $this->wallet_balance = $wallet_balance;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getPasswordHash() {
        return $this->password_hash;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getIsLocked() {
        return $this->is_locked;
    }

    public function getFailedLoginAttempt() {
        return $this->failed_login_attempt;
    }

    public function getWalletBalance() {
        return $this->wallet_balance;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setPasswordHash($password_hash) {
        $this->password_hash = $password_hash;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setIsLocked($is_locked) {
        $this->is_locked = $is_locked;
    }

    public function setFailedLoginAttempt($failed_login_attempt) {
        $this->failed_login_attempt = $failed_login_attempt;
    }

    public function setWalletBalance($wallet_balance) {
        $this->wallet_balance = $wallet_balance;
    }
}
class Customer extends User {
    public function __construct($id = null, $name = null, $password_hash = null, $email = null, $role = null, $is_locked = false, $failed_login_attempt = 0, $wallet_balance = 0.0) {
        parent::__construct($id,$name,$password_hash,$email,"c", $phone);
    }
}
class Staff extends User {
    public function __construct($id = null, $name = null, $password_hash = null, $email = null, $role = null, $is_locked = false, $failed_login_attempt = 0, $wallet_balance = 0.0) {
        parent::__construct($id,$name,$password_hash,$email,"s", $phone);
    }
}
class Admin extends User {
    public function __construct($id = null, $name = null, $password_hash = null, $email = null, $role = null, $is_locked = false, $failed_login_attempt = 0, $wallet_balance = 0.0) {
        parent::__construct($id,$name,$password_hash,$email,"a", $phone);
    }
}
?>
