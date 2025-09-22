<?php
session_start();
header("Content-Type: application/json");
require_once '../../config/db.php';

try {
    // Solo aceptar POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        exit;
    }

    $correo     = $_POST['correo']     ?? null;
    $contrasena = $_POST['contrasena'] ?? null;

    if (empty($correo) || empty($contrasena)) {
        http_response_code(400);
        echo json_encode(["error" => "Debe ingresar correo y contraseña"]);
        exit;
    }

    // Buscar usuario por correo
    $stmt = $pdo->prepare("
        SELECT u.id_usuario, u.correo, u.contrasena, u.id_rol,
               p.nombre, p.apellido
        FROM tb_usuario u
        INNER JOIN tb_persona p ON u.id_persona = p.id_persona
        WHERE u.correo = ?
        LIMIT 1
    ");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validación credenciales
    if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
        http_response_code(401);
        echo json_encode(["error" => "Correo o contraseña incorrectos"]);
        exit;
    }

    // Asegurar nueva id de sesión al iniciar sesión
    session_regenerate_id(true);

    // Guardar datos mínimos en sesión
    $_SESSION['usuario'] = [
        "id"       => (int)$usuario['id_usuario'],
        "nombre"   => $usuario['nombre'],
        "apellido" => $usuario['apellido'],
        "correo"   => $usuario['correo'],
        "rol"      => ((int)$usuario['id_rol'] === 1 ? "Admin" : "Cliente")
    ];

    echo json_encode([
        "success"  => true,
        "message"  => "Inicio de sesión exitoso",
        "usuario"  => $_SESSION['usuario'],
        // Puedes aprovechar y mandar la URL de redirección sugerida:
        "redirect" => "/MAYWATEXTIL/index.php?page=home"
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en servidor"]);
    // log real del error en servidor, no al cliente:
    // error_log($e->getMessage());
    exit;
}
