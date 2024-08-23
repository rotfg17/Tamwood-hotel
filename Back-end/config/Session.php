<?php
require_once __DIR__ . '/../model/User.php';

class Session {
    public $sessionStatus;
    private $timeout;

    public function __construct($timeout = 1000) {
        $this->timeout = $timeout;
        session_start();
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function getTimeout() {
        return $this->timeout;
    }
    
    public function startSession(User $user) {

        $_SESSION['userClass'] = serialize($user);
        $_SESSION['timeout'] = time() + 3600;

        return session_id();
    }

    public function deleteSession() {
        session_unset();
        session_destroy();

        return 'expired';
    }

    public function getSession() {
        $userClass = isset($_SESSION['userClass']) ? unserialize($_SESSION['userClass']) : null;
        $sessionStatus = null;

        if (session_id() === $_SERVER['HTTP_USER_SID']) {
            if (isset($_SESSION['timeout'])) {
                if ($_SESSION['timeout'] > time()) {
                    $sessionStatus = 'active';
                    $_SESSION['timeout'] = time() + $this->timeout;
                } else {
                    $sessionStatus = $this->deleteSession();
                }
            }
        }

        return $sessionStatus;
    }
}

?>