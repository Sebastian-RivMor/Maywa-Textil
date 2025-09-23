<?php
declare(strict_types=1);
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use App\Auth\AuthService;
use App\Auth\UserRepository;
use App\Auth\PersonRepository;

final class AuthServiceTest extends TestCase
{
    public function test_login_exitoso_admin(): void {
        $users = $this->createMock(UserRepository::class);
        $persons = $this->createMock(PersonRepository::class);

        $users->method('findByEmail')->willReturn([
            'id_usuario'=>10,'id_persona'=>15,'id_rol'=>1,
            'correo'=>'merly@gmail.com',
            'contrasena'=>password_hash('Secret123', PASSWORD_DEFAULT),
            'estado_usuario'=>'Activo'
        ]);
        $persons->method('getPerson')->willReturn(['nombre'=>'Merly','apellido'=>'Angulo']);

        $svc = new AuthService($users, $persons);
        $r = $svc->authenticate('merly@gmail.com','Secret123');

        $this->assertTrue($r['success']);
        $this->assertSame('Administrador', $r['usuario']['rol']);
        $this->assertSame('Admin/admin-page.php', $r['redirect']);
        $this->assertSame('Merly', $r['usuario']['nombre']);
    }

    public function test_correo_invalido(): void {
        $svc = new AuthService($this->createMock(UserRepository::class), $this->createMock(PersonRepository::class));
        $r = $svc->authenticate('correo-malo','x');
        $this->assertFalse($r['success']);
        $this->assertSame('Correo inválido', $r['message']);
    }

    public function test_contrasena_vacia(): void {
        $svc = new AuthService($this->createMock(UserRepository::class), $this->createMock(PersonRepository::class));
        $r = $svc->authenticate('user@example.com','');
        $this->assertFalse($r['success']);
        $this->assertSame('Contraseña vacía', $r['message']);
    }

    public function test_usuario_no_encontrado(): void {
        $users = $this->createMock(UserRepository::class);
        $users->method('findByEmail')->willReturn(null);
        $svc = new AuthService($users, $this->createMock(PersonRepository::class));
        $r = $svc->authenticate('no@existe.com','Secret123');
        $this->assertFalse($r['success']);
        $this->assertSame('Usuario no encontrado', $r['message']);
    }

    public function test_cuenta_inhabilitada(): void {
        $users = $this->createMock(UserRepository::class);
        $users->method('findByEmail')->willReturn([
            'id_usuario'=>1,'id_persona'=>2,'id_rol'=>2,'correo'=>'u@e.com',
            'contrasena'=>password_hash('Secret123', PASSWORD_DEFAULT),
            'estado_usuario'=>'Inactivo'
        ]);
        $svc = new AuthService($users, $this->createMock(PersonRepository::class));
        $r = $svc->authenticate('u@e.com','Secret123');
        $this->assertFalse($r['success']);
        $this->assertSame('Cuenta inhabilitada', $r['message']);
    }

    public function test_contrasena_incorrecta(): void {
        $users = $this->createMock(UserRepository::class);
        $users->method('findByEmail')->willReturn([
            'id_usuario'=>1,'id_persona'=>2,'id_rol'=>2,'correo'=>'u@e.com',
            'contrasena'=>password_hash('Correcta123', PASSWORD_DEFAULT),
            'estado_usuario'=>'Activo'
        ]);
        $svc = new AuthService($users, $this->createMock(PersonRepository::class));
        $r = $svc->authenticate('u@e.com','Wrong!');
        $this->assertFalse($r['success']);
        $this->assertSame('Contraseña incorrecta', $r['message']);
    }
}
