<?php
declare(strict_types=1);
namespace App\Auth;

interface PersonRepository {
    public function createPerson(string $nombre, string $apellido): int;
    public function getPerson(int $idPersona): ?array;
}
