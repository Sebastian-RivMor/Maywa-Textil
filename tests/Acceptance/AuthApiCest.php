<?php
declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class AuthApiCest
{
    private string $correo;
    private string $pass = 'Secreta123';

    public function _before(AcceptanceTester $I): void
    {
        // 1) Asegurar provincia (evita FK)
        $existe = $I->grabNumRecords('tb_provincia', ['id_provincia' => 1]);
        if ($existe === 0) {
            $I->haveInDatabase('tb_provincia', [
                'id_provincia' => 1,
                'nombre'       => 'Lima',   // agrega aquí cualquier otra columna NOT NULL que tenga tu tabla
            ]);
        }

        // 2) Crear persona
        $personaId = $I->haveInDatabase('tb_persona', [
            'nombre'       => 'Juan',
            'apellido'     => 'Pérez',
            'dni'          => '12345678',   // si es UNIQUE, está bien repetir para tests aislados en BD de pruebas
            'telefono'     => '999888777',
            'sexo'         => 'M',
            'id_provincia' => 1,
        ]);

        // 3) Crear usuario logeable
        $this->correo = 'login.user.' . time() . '@example.com';
        $hash = password_hash($this->pass, PASSWORD_DEFAULT);

        $I->haveInDatabase('tb_usuario', [
            'id_persona' => $personaId,
            'correo'     => $this->correo,
            'contrasena' => $hash,
            'id_rol'     => 2, // Cliente
        ]);
    }

    public function metodoNoPermitido_devuelve405(AcceptanceTester $I): void
    {
        $I->sendGet('api/auth/login.php');
        $I->seeResponseCodeIs(405);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Método no permitido']);
    }

    public function faltanCampos_devuelve400(AcceptanceTester $I): void
    {
        $I->sendPost('api/auth/login.php', ['correo' => $this->correo]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Debe ingresar correo y contraseña']);
    }

    public function credencialesInvalidas_devuelve401(AcceptanceTester $I): void
    {
        $I->sendPost('api/auth/login.php', [
            'correo'     => $this->correo,
            'contrasena' => 'mala'
        ]);
        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Correo o contraseña incorrectos']);
    }

    public function loginOk_devuelve200_ySesion(AcceptanceTester $I): void
    {
        $I->sendPost('api/auth/login.php', [
            'correo'     => $this->correo,
            'contrasena' => $this->pass
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['success' => true]);
        $I->seeCookie('PHPSESSID');
    }
}
