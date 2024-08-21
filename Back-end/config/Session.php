<?php

class Session {
    public function getSession() {
        session_start();

        $key = isset($_SERVER['HTTP_SESSION_KEY']) ? $_SERVER['HTTP_SESSION_KEY'] : null;
        $sessionStatus = 'active';

        if ($key === 'tamwood-hotel:)') {
            if (isset($_SESSION['timeout'])) {
                if ($_SESSION['timeout'] > time()) {
                    $_SESSION['timeout'] = time() + 1000;
                } else {
                    $sessionStatus = 'expired';
                    session_unset();
                    session_destroy();
                }
            } else {
                $_SESSION['timeout'] = time() + 3600;
            }
        } else {
            throw new Exception("Wrong session keys", 406);
        }

        return $sessionStatus;
    }
}

?>