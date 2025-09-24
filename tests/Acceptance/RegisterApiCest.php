<?php
declare(strict_types=1);

namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class RegisterApiCest
{
    private array $payload = [
        'nombre'       => 'Juan',
        'apellido'     => 'Pérez',
        'dni'          => '87654321',
        'telefono'     => '999000111',
        'sexo'         => 'M',
        'id_provincia' => 1,
        'correo'       => 'juan.prueba@example.com',
        'contrasena'   => 'Secreta123',
    ];

    public function _before(AcceptanceTester $I): void
    {
        // Sembrar provincia requerida
        $existe = $I->grabNumRecords('tb_provincia', ['id_provincia' => 1]);
        if ($existe === 0) {
            $I->haveInDatabase('tb_provincia', [
                'id_provincia' => 1,
                'nombre'       => 'Lima', // añade columnas NOT NULL si existen
            ]);
        }
    }

    public function fallaSiNoMandaProvincia(AcceptanceTester $I): void
    {
        $bad = $this->payload;
        unset($bad['id_provincia']);

        $I->sendPost('api/auth/register.php', $bad);
        $I->seeResponseCodeIsBetween(200, 302);
        $I->seeResponseContains('Debe seleccionar una provincia');
    }

    public function fallaSiProvinciaNoExiste(AcceptanceTester $I): void
    {
        $bad = $this->payload;
        $bad['id_provincia'] = 9999;

        $I->sendPost('api/auth/register.php', $bad);
        $I->seeResponseCodeIsBetween(200, 302);
        $I->seeResponseContains('La provincia seleccionada no existe');
    }

    public function registroOk_redirigeYcreaSesion(AcceptanceTester $I): void
    {
        $ok = $this->payload;
        $ok['correo'] = 'juan.' . time() . '@example.com';

        $I->sendPost('api/auth/register.php', $ok);

        $I->seeResponseCodeIsBetween(200, 302);
        $I->seeHttpHeader('Location');
        $I->seeHttpHeader('Location', fn($v) => str_contains($v, '/public/index.php?page=home'));
        $I->seeCookie('PHPSESSID');
    }
}
