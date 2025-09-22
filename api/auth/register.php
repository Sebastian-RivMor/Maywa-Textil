<?php
session_start();
include '../../config/db.php';

try {
    // Recibir datos del formulario
    $nombre       = $_POST['nombre'] ?? null;
    $apellido     = $_POST['apellido'] ?? null;
    $dni          = $_POST['dni'] ?? null;
    $telefono     = $_POST['telefono'] ?? null;
    $sexo         = $_POST['sexo'] ?? null;
    $id_provincia = $_POST['id_provincia'] ?? null;
    $correo       = $_POST['correo'] ?? null;
    $contrasena   = $_POST['contrasena'] ?? null;

    // Validar provincia
    if (empty($id_provincia)) {
        die("Debe seleccionar una provincia");
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM tb_provincia WHERE id_provincia = ?");
    $stmt->execute([$id_provincia]);
    if ($stmt->fetchColumn() == 0) {
        die("La provincia seleccionada no existe");
    }

    // Insertar persona
    $stmt = $pdo->prepare("INSERT INTO tb_persona (nombre, apellido, dni, telefono, sexo, id_provincia)
                           VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nombre, $apellido, $dni, $telefono, $sexo, $id_provincia]);
    $id_persona = $pdo->lastInsertId();

    // Insertar usuario con rol Cliente (id_rol = 2)
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO tb_usuario (id_persona, correo, contrasena, id_rol) VALUES (?, ?, ?, 2)");
    $stmt->execute([$id_persona, $correo, $hash]);
    $id_usuario = $pdo->lastInsertId();

    // Crear sesión automáticamente
    $_SESSION['usuario'] = [
        'id'       => $id_usuario,
        'nombre'   => $nombre,
        'apellido' => $apellido,
        'correo'   => $correo,
        'rol'      => 'Cliente'
    ];

    // 🔹 Redirigir al home.php
    header("Location: ../../public/index.php?page=home");
    exit;

} catch (Exception $e) {
    die("Error en servidor: " . $e->getMessage());
}
