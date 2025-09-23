<?php
declare(strict_types=1);
namespace App\Auth;

interface UserRepository {
    public function findByEmail(string $email): ?array;
    public function isEmailTaken(string $email): bool;
    public function createUser(int $idPersona, string $correo, string $passwordHash, ?int $idRol = 2, string $estado = 'Activo'): int;
}
