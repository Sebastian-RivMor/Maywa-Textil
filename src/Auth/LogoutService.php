<?php
declare(strict_types=1);
namespace App\Auth;

final class LogoutService {
    public static function logout(): array {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time()-42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        return ["success"=>true,"message"=>"Sesión cerrada"];
    }
}
