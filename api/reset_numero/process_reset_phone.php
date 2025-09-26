<?php
require_once __DIR__ . '/../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_POST['id_usuario'] ?? null;
    $nueva_contrasena = trim($_POST['nueva_contrasena']);

    if ($id_usuario && $nueva_contrasena) {
        $hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

        // Actualiza contraseña
        $stmt = $pdo->prepare("UPDATE tb_usuario SET contrasena = ? WHERE id_usuario = ?");
        $stmt->execute([$hash, $id_usuario]);

        // Limpia códigos usados
        $pdo->prepare("DELETE FROM otp_resets WHERE id_usuario = ?")->execute([$id_usuario]);

        echo "✅ Contraseña actualizada con éxito. <a href='login.php' class='text-blue-600 underline'>Iniciar sesión</a>";
    } else {
        echo "❌ Datos incompletos.";
    }
} else {
    echo "Acceso no permitido.";
}