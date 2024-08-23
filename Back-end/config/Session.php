<?php

class Session {
    public $sessionStatus;

    public function getSessionStatus() {
        return $this->sessionStatus;
    }

    public function startSession(User $user) {
        // Verificar si una sesión ya está activa antes de iniciar una nueva
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['userClass'] = serialize($user);
        $_SESSION['timeout'] = time() + 3600; // Establecer tiempo de sesión

        return session_id();
    }

    public function deleteSession() {
        session_unset();
        session_destroy();
    }

    public function getSession() {
         session_start();

        $userClass = isset($_SESSION['userClass']) ? unserialize($_SESSION['userClass']) : null;

        if ($userClass !== null) {
            if (isset($_SESSION['timeout'])) {
                if ($_SESSION['timeout'] > time()) {
                    $sessionStatus = session_id();
                    $_SESSION['timeout'] = time() + 1000;
                } else {
                    $this->deleteSession();
                }
            } else {
                $sessionStatus = $this->startSession();
            }
        }

        return $sessionStatus;
    }
}


?>