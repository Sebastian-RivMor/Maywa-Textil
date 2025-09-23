<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Auth\RegisterService;
use App\Auth\UserRepository;
use App\Auth\PersonRepository;

final class RegisterServiceTest extends TestCase
{
    public function test_registro_exitoso(): void {
        $users = $this->createMock(UserRepository::class);
        $persons = $this->createMock(PersonRepository::class);

        $users->method('isEmailTaken')->willReturn(false);
        $persons->method('createPerson')->willReturn(15);

        $users->expects($this->once())
            ->method('createUser')
            ->with(
                15,
                'nuevo@example.com',
                $this->callback(fn($h)=>is_string($h)&&password_verify('Secret123',$h)),
                2,
                'Activo'
            )->willReturn(99);

        $svc = new RegisterService($users, $persons);
        $r = $svc->register('Merly','Angulo','nuevo@example.com','Secret123',2);

        $this->assertTrue($r['success']);
        $this->assertSame(99, $r['id_usuario']);
    }

    public function test_correo_invalido(): void {
        $svc = new RegisterService($this->createMock(UserRepository::class), $this->createMock(PersonRepository::class));
        $r = $svc->register('M','A','bad-email','Secret123',2);
        $this->assertFalse($r['success']);
        $this->assertSame('Correo inválido', $r['message']);
    }

    public function test_contrasena_debil(): void {
        $svc = new RegisterService($this->createMock(UserRepository::class), $this->createMock(PersonRepository::class));
        $r = $svc->register('M','A','ok@example.com','abc',2);
        $this->assertFalse($r['success']);
        $this->assertStringContainsString('Contraseña débil', $r['message']);
    }

    public function test_correo_duplicado(): void {
        $users = $this->createMock(UserRepository::class);
        $users->method('isEmailTaken')->willReturn(true);
        $svc = new RegisterService($users, $this->createMock(PersonRepository::class));
        $r = $svc->register('M','A','existe@example.com','Secret123',2);
        $this->assertFalse($r['success']);
        $this->assertSame('Correo ya registrado', $r['message']);
    }
}
