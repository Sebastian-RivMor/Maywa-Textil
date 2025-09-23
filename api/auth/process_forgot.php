<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/mailer.php';

$correo  = isset($_POST['correo']) ? trim($_POST['correo']) : '';
$generic = 'Si el correo existe, te enviaremos un enlace para restablecer tu contraseña.';

if ($correo === '') { exit($generic); }

// Buscar usuario real
$stmt = $pdo->prepare('SELECT id_usuario, correo FROM tb_usuario WHERE correo = ? LIMIT 1');
$stmt->execute([$correo]);
$u = $stmt->fetch();

if ($u) {
  $token   = bin2hex(random_bytes(32));             // 64 chars
  $expires = date('Y-m-d H:i:s', time() + 3600);    // +1 hora

  // Guardar token
  $pdo->prepare('INSERT INTO password_resets (id_usuario, token, expires_at) VALUES (?, ?, ?)')
      ->execute([$u['id_usuario'], $token, $expires]);

  // URL absoluta (ajústala si es necesario)
  // Si tu ruta local es distinta, ponla fija:
  // $resetUrl = 'http://localhost/MAYWA-TEXTIL-MASTER/api/auth/reset_password.php?token=' . $token;
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host   = $_SERVER['HTTP_HOST'];
  $base   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\'); // /api/auth
  $resetUrl = "{$scheme}://{$host}{$base}/reset_password.php?token={$token}";

  try {
    $mail = build_mailer();
    $mail->addAddress($u['correo']);
    $mail->Subject = 'Restablece tu contraseña - Maywa Textil';
    $mail->isHTML(true);
    $mail->Body = '<p>Hola,</p><p>Usa este enlace (válido por 1 hora):</p>'
      . '<p><a href="' . htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8') . '">Restablecer contraseña</a></p>'
      . '<p>Si no solicitaste esto, ignora el mensaje.</p>';
    $mail->AltBody = "Copia y pega esta URL:\n{$resetUrl}";
    $mail->send();
  } catch (Throwable $e) {
    // opcional: error_log($e->getMessage());
  }
}

echo $generic;
