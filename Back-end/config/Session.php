<?php
require_once __DIR__ . '/../model/User.php';
class Session {
    public $sessionStatus;
    private $timeout;

    public function __construct($timeout = 1000) {
        $this->timeout = $timeout;
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Inicia la sesión solo si no está activa
        }
    }

    public function setTimeout($timeout) {
        $this->timeout = $timeout;
    }

    public function getTimeout() {
        return $this->timeout;
    }
    
    public function startSession(User $user) {
        $_SESSION['userClass'] = serialize($user); // Almacena el objeto serializado de User en la sesión
        $_SESSION['timeout'] = time() + $this->timeout; // Establece el tiempo de expiración de la sesión

        return session_id(); // Retorna el ID de la sesión actual
    }

    public function deleteSession() {
        session_unset(); // Limpia todas las variables de la sesión
        session_destroy(); // Destruye la sesión

        return 'expired'; // Retorna el estado de la sesión como 'expired'
    }

    public function getSession() {
        $userClass = isset($_SESSION['userClass']) ? unserialize($_SESSION['userClass']) : null;
        $sessionStatus = null;

        if (isset($_SERVER['HTTP_USER_SID']) && session_id() === $_SERVER['HTTP_USER_SID']) {
            if (isset($_SESSION['timeout'])) {
                if ($_SESSION['timeout'] > time()) {
                    $sessionStatus = 'active'; // Marca la sesión como activa
                    $_SESSION['timeout'] = time() + $this->timeout; // Extiende el tiempo de expiración
                } else {
                    $sessionStatus = $this->deleteSession(); // Si la sesión ha expirado, la elimina
                }
            }
        }

        return $sessionStatus; // Retorna el estado de la sesión ('active' o 'expired')
    }
}