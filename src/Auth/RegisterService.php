<?php
declare(strict_types=1);
namespace App\Auth;

final class RegisterService {
    public function __construct(private UserRepository $users, private PersonRepository $persons) {}

    public function register(string $nombre, string $apellido, string $correo, string $contrasena, ?int $idRol = 2): array {
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return ["success"=>false,"message"=>"Correo inválido"];
        }
        if (!$this->isStrongPassword($contrasena)) {
            return ["success"=>false,"message"=>"Contraseña débil (mín. 8, mayúscula, minúscula y dígito)"];
        }
        if ($this->users->isEmailTaken($correo)) {
            return ["success"=>false,"message"=>"Correo ya registrado"];
        }
        $idPersona = $this->persons->createPerson($nombre, $apellido);
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);
        $idUsuario = $this->users->createUser($idPersona, $correo, $hash, $idRol, 'Activo');
        return ["success"=>true,"message"=>"Usuario registrado","id_usuario"=>$idUsuario];
    }

    private function isStrongPassword(string $v): bool {
        if (strlen($v) < 8) return false;
        return (bool)preg_match('/[A-Z]/',$v) && preg_match('/[a-z]/',$v) && preg_match('/\d/',$v);
    }
}
