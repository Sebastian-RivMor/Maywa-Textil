<?php
require_once __DIR__ . '/../../config/db.php';

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$pass1 = $_POST['contrasena'] ?? '';
$pass2 = $_POST['contrasena_confirm'] ?? '';

if ($token === '' || $pass1 === '' || $pass2 === '') { exit('Datos incompletos.'); }
if ($pass1 !== $pass2) { exit('Las contraseñas no coinciden.'); }
if (strlen($pass1) < 8) { exit('La contraseña debe tener al menos 8 caracteres.'); }

// Validar token vigente
$stmt = $pdo->prepare('SELECT id, id_usuario, expires_at FROM password_resets WHERE token = ? LIMIT 1');
$stmt->execute([$token]);
$reset = $stmt->fetch();
if (!$reset || strtotime($reset['expires_at']) <= time()) { exit('Token inválido o expirado.'); }

// Actualizar contraseña de tb_usuario (Bcrypt)
$hash = password_hash($pass1, PASSWORD_BCRYPT);
$pdo->prepare('UPDATE tb_usuario SET contrasena = ? WHERE id_usuario = ?')
    ->execute([$hash, $reset['id_usuario']]);

// Invalidar todos los tokens del usuario
$pdo->prepare('DELETE FROM password_resets WHERE id_usuario = ?')
    ->execute([$reset['id_usuario']]);

echo 'Contraseña actualizada correctamente. Ya puedes iniciar sesión.';
