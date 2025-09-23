<?php
declare(strict_types=1);
namespace App\Auth;

final class AuthService {
    public function __construct(private UserRepository $users, private PersonRepository $persons) {}

    public function authenticate(string $correo, string $contrasena): array {
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            return ["success"=>false,"message"=>"Correo inválido"];
        }
        if ($contrasena === '') {
            return ["success"=>false,"message"=>"Contraseña vacía"];
        }

        $u = $this->users->findByEmail($correo);
        if (!$u) return ["success"=>false,"message"=>"Usuario no encontrado"];
        if (strcasecmp((string)($u['estado_usuario'] ?? ''), 'Activo') !== 0) {
            return ["success"=>false,"message"=>"Cuenta inhabilitada"];
        }
        if (!isset($u['contrasena']) || !password_verify($contrasena, (string)$u['contrasena'])) {
            return ["success"=>false,"message"=>"Contraseña incorrecta"];
        }

        $rol  = (int)($u['id_rol'] ?? 2);
        $tipo = ($rol === 1) ? 'Administrador' : 'Cliente';
        $p = $this->persons->getPerson((int)$u['id_persona']) ?? ['nombre'=>null,'apellido'=>null];

        return [
            "success"=>true,
            "message"=>"Inicio de sesión como {$tipo}",
            "usuario"=>[
                "id"=>(int)$u['id_usuario'],
                "correo"=>(string)$u['correo'],
                "rol"=>$tipo,
                "nombre"=>$p['nombre'],
                "apellido"=>$p['apellido'],
            ],
            "redirect"=> $rol===1 ? "Admin/admin-page.php" : "index.php"
        ];
    }
}
