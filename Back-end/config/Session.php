<?php

class Session {
    private $sessionStatus;

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

        $this->sessionStatus = session_id();
        return $this->sessionStatus;
    }

    public function deleteSession() {
        session_unset();
        session_destroy();
        $this->sessionStatus = null;
    }

    public function getSession() {
        // Verificar si una sesión ya está activa antes de iniciar una nueva
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userClass = isset($_SESSION['userClass']) ? unserialize($_SESSION['userClass']) : null;

        if ($userClass !== null) {
            if (isset($_SESSION['timeout'])) {
                if ($_SESSION['timeout'] > time()) {
                    $this->sessionStatus = session_id();
                    $_SESSION['timeout'] = time() + 1000; // Extender tiempo de sesión
                } else {
                    $this->deleteSession();
                }
            } else {
                $this->sessionStatus = $this->startSession($userClass);
            }
        } else {
            $this->sessionStatus = null; // Asegurarse de que sessionStatus sea null si no hay sesión activa
        }

        return $this->sessionStatus;
    }
}