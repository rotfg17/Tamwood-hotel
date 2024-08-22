<?php
require_once __DIR__ . '/../model/User.php';

class Session {
    public function startSession() {
        session_start();

        $_SESSION['timeout'] = time() + 3600;
        return $_SESSION['sid'];
    }

    public function deleteSession() {
        session_unset();
        session_destroy();

        return 'expired';
    }

    public function getSession() {
        session_start();

        $user_email = isset($_SESSION['user_email']) ? unserialize($_SESSION['user_email']) : null;
        $sessionStatus = null;

        if ($user_email !== null) {
            if (isset($_SESSION['timeout'])) {
                if ($_SESSION['timeout'] > time()) {
                    $sessionStatus = $_SESSION['sid'];
                    $_SESSION['timeout'] = time() + 1000;
                } else {
                    $sessionStatus = $this->deleteSession();
                }
            } else {
                $sessionStatus = $this->startSession();
            }
        }

        return $sessionStatus;
    }
}

?>