<?php
require_once __DIR__ . '/../model/User.php';

class Session {
    public $sessionStatus;
    private $timeout;

    // Builder where the session begins and a predetermined waiting time is established
    public function __construct($timeout = 1000) {
        $this->timeout = $timeout;
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Starts the session only if it is not already active
        }
    }
    

    // Setter to update the waiting time
    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    // Getter to get the waiting time
    public function getTimeout() {
        return $this->timeout;
    }
    
    // Start a new session for a specific user
    public function startSession(User $user) {
        $_SESSION['userClass'] = serialize($user); //Stores the User Serialized object in the session
        $_SESSION['timeout'] = time() + 3600; // Set the Expiration time of the session

        return session_id(); // The ID of the current session returns
    }

    //Eliminate the current session
    public function deleteSession() {
        session_unset(); // Clean all session variables
        session_destroy(); // Destroy the session

        return 'expired'; // The state of the session returns as 'expired'
    }

    // Verify the state of the current session
    public function getSession() {
        $userClass = isset($_SESSION['userClass']) ? unserialize($_SESSION['userClass']) : null;
        $sessionStatus = null;

        //Verify that the customer session ID coincides with the current session ID on the server
        if (isset($_SERVER['HTTP_USER_SID']) && session_id() === $_SERVER['HTTP_USER_SID']) {
            //Verify if the session has not expired
            if (isset($_SESSION['timeout'])) {
                if ($_SESSION['timeout'] > time()) {
                    $sessionStatus = 'active'; // Mark the session as active
                    $_SESSION['timeout'] = time() + $this->timeout; // Extend the expiration time
                } else {
                    $sessionStatus = $this->deleteSession(); // If the session has expired, it is eliminated
                }
            }
        }

        return $sessionStatus; // Retorna el estado de la sesión ('active' o 'expired')
    }
}