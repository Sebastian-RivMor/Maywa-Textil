<?php
// api/auth/process_forgot.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../config/mailer.php';

$generic = 'Si el correo existe, te enviaremos un enlace para restablecer tu contraseña.';

try {
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: success.php?msg=' . urlencode($generic));
    exit;
  }

  $correo = trim($_POST['correo'] ?? '');
  if ($correo === '') {
    header('Location: success.php?msg=' . urlencode($generic));
    exit;
  }

  // Buscar usuario por correo
  $stmt = $pdo->prepare('SELECT id_usuario, correo FROM tb_usuario WHERE correo = ? LIMIT 1');
  $stmt->execute([$correo]);
  $u = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($u) {
    // Invalida tokens previos no usados
    $pdo->prepare(
      'UPDATE password_resets
          SET invalidated_at = NOW()
        WHERE id_usuario = ?
          AND used_at IS NULL
          AND invalidated_at IS NULL'
    )->execute([$u['id_usuario']]);

    // Crea token (10 minutos)
    $token   = bin2hex(random_bytes(32));              // 64 chars
    $expires = date('Y-m-d H:i:s', time() + 10 * 60);  // +10 min

    // Guarda token
    $pdo->prepare(
      'INSERT INTO password_resets (id_usuario, token, expires_at)
       VALUES (?, ?, ?)'
    )->execute([$u['id_usuario'], $token, $expires]);

    // URL al reset (ajusta el prefijo si tu carpeta es distinta)
    $scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host     = $_SERVER['HTTP_HOST'];
    $resetUrl = "{$scheme}://{$host}/MAYWATEXTIL/api/auth/reset_password.php?token={$token}";

    // Enviar correo (bonito, con logo embebido y botón)
    try {
      $mail = build_mailer();
      $mail->addAddress($u['correo']);
      $mail->Subject = 'Restablece tu contraseña - Maywa Textil';

      // Logo embebido (ajusta la ruta si tu logo está en otro lugar)
      $logoPath = realpath(__DIR__ . '/../../public/assets/img/logo.png');
      $logoTag  = '';
      if ($logoPath && file_exists($logoPath)) {
        // cid:maywa_logo para usar en el HTML
        $mail->addEmbeddedImage($logoPath, 'maywa_logo', 'logo.png');
        $logoTag = '<img src="cid:maywa_logo" alt="Maywa Textil" width="64" height="64"
                    style="display:block;border-radius:9999px;border:1px solid #eee;">';
      }

      $safeUrl = htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8');
      $year    = date('Y');

      $mail->isHTML(true);
      $mail->Body = <<<HTML
      <!doctype html>
      <html lang="es">
        <head>
          <meta charset="utf-8">
          <meta name="x-apple-disable-message-reformatting">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <title>Restablecer contraseña</title>
        </head>
        <body style="margin:0;padding:0;background:#f4f5f7;">
          <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background:#f4f5f7;">
            <tr>
              <td align="center" style="padding:32px 12px;">
                <table role="presentation" cellpadding="0" cellspacing="0" width="600" style="max-width:600px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e9e9ee;">
                  <tr>
                    <td align="center" style="padding:24px;background:#5227A0;">
                      <div style="display:inline-block;">{$logoTag}</div>
                      <div style="height:8px;"></div>
                      <h1 style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:22px;line-height:28px;color:#ffffff;">
                        Maywa Textil
                      </h1>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:28px 24px 8px 24px;">
                      <h2 style="margin:0 0 12px 0;font-family:Arial,Helvetica,sans-serif;font-size:20px;line-height:26px;color:#111827;">
                        Restablece tu contraseña
                      </h2>
                      <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:22px;color:#4b5563;">
                        Hola,</p>
                      <p style="margin:12px 0 0 0;font-family:Arial,Helvetica,sans-serif;font-size:15px;line-height:22px;color:#4b5563;">
                        Para crear una nueva contraseña, haz clic en el botón de abajo. 
                        Este enlace es válido por <strong>10 minutos</strong>.
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <td align="center" style="padding:24px;">
                      <!-- Botón -->
                      <a href="{$safeUrl}" 
                        style="display:inline-block;background:#6D28D9;color:#ffffff;text-decoration:none;
                                font-family:Arial,Helvetica,sans-serif;font-weight:bold;font-size:15px;
                                padding:12px 22px;border-radius:9999px;">
                        Restablecer contraseña
                      </a>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:0 24px 16px 24px;">
                      <p style="margin:0 0 10px 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:20px;color:#6b7280;">
                        Si el botón no funciona, copia y pega este enlace en tu navegador:
                      </p>
                      <p style="word-break:break-all;margin:0 0 16px 0;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:20px;color:#6b7280;">
                        <a href="{$safeUrl}" style="color:#6D28D9;text-decoration:underline;">{$safeUrl}</a>
                      </p>
                      <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;line-height:20px;color:#6b7280;">
                        Si no solicitaste este cambio, puedes ignorar este mensaje.
                      </p>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:18px 24px;background:#fafafa;border-top:1px solid #f0f0f5;">
                      <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:12px;line-height:18px;color:#9ca3af;text-align:center;">
                        © {$year} Maywa Textil. Todos los derechos reservados.
                      </p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
      </html>
      HTML;

      // Texto plano de respaldo
      $mail->AltBody = "Hola,\n\n"
                    . "Usa este enlace para restablecer tu contraseña (válido por 10 minutos):\n"
                    . "{$resetUrl}\n\n"
                    . "Si no solicitaste esto, ignora este mensaje.\n";

      $mail->send();
    } catch (Throwable $e) {
      // error_log($e->getMessage());
      // Respuesta neutra de todas maneras
    }

  }

  header('Location: success.php?msg=' . urlencode($generic));
  exit;

} catch (Throwable $e) {
  // error_log($e->getMessage());
  header('Location: success.php?msg=' . urlencode($generic));
  exit;
}