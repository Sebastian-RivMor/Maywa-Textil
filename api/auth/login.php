<?php
session_start();

header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../../config/db.php';

try {
    // Solo POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
        exit;
    }

    // Normalizar inputs
    $correo     = trim($_POST['correo'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // Validaciones básicas
    if ($correo === '' || $contrasena === '') {
        http_response_code(400);
        echo json_encode(["error" => "Debe ingresar correo y contraseña"]);
        exit;
    }
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode(["error" => "El correo no es válido"]);
        exit;
    }

    // Buscar usuario por correo
    $stmt = $pdo->prepare("
        SELECT u.id_usuario, u.correo, u.contrasena, u.id_rol, u.estado_usuario,
               p.nombre, p.apellido
        FROM tb_usuario u
        INNER JOIN tb_persona p ON u.id_persona = p.id_persona
        WHERE u.correo = ?
        LIMIT 1
    ");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Validación credenciales y estado
    if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
        http_response_code(401);
        echo json_encode(["error" => "Correo o contraseña incorrectos"]);
        exit;
    }
    if (isset($usuario['estado_usuario']) && $usuario['estado_usuario'] === 'Inactivo') {
        http_response_code(403);
        echo json_encode(["error" => "Tu usuario está inactivo"]);
        exit;
    }

    // Nueva sesión
    session_regenerate_id(true);

    // Guardar datos mínimos en sesión
    $_SESSION['usuario'] = [
        "id"       => (int)$usuario['id_usuario'],
        "nombre"   => $usuario['nombre'],
        "apellido" => $usuario['apellido'],
        "correo"   => $usuario['correo'],
        "rol"      => ((int)$usuario['id_rol'] === 1 ? "Admin" : "Cliente")
    ];

    // Redirección según rol
    $redirect = ($_SESSION['usuario']['rol'] === "Admin")
        ? "/MAYWATEXTIL/admin/dashboard.php"
        : "/MAYWATEXTIL/public/index.php?page=home";

    echo json_encode([
        "success"  => true,
        "message"  => "Inicio de sesión exitoso",
        "usuario"  => $_SESSION['usuario'],
        "redirect" => $redirect
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error en servidor"]);
    // error_log($e->getMessage()); // log real
    exit;
}