<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Auth\LogoutService;

final class LogoutServiceTest extends TestCase
{
    public function test_logout_cierra_sesion(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) session_start();
        $_SESSION['usuario'] = ['id'=>1];

        $r = LogoutService::logout();

        $this->assertTrue($r['success']);
        $this->assertSame('Sesión cerrada', $r['message']);
        $this->assertSame(PHP_SESSION_NONE, session_status());
    }
}
