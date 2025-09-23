<?php
require_once __DIR__ . '/../../config/db.php';
$token = isset($_GET['token']) ? trim($_GET['token']) : '';

$stmt = $pdo->prepare('SELECT pr.id, pr.id_usuario, pr.expires_at, u.correo
                       FROM password_resets pr
                       JOIN tb_usuario u ON u.id_usuario = pr.id_usuario
                       WHERE pr.token = ? LIMIT 1');
$stmt->execute([$token]);
$reset = $stmt->fetch();

$valid = $reset && (strtotime($reset['expires_at']) > time());
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow p-6">
    <?php if (!$valid): ?>
      <h1 class="text-2xl font-bold mb-4">Enlace no válido o expirado</h1>
      <p class="text-gray-600">Vuelve a solicitar el restablecimiento.</p>
    <?php else: ?>
      <h1 class="text-2xl font-bold mb-4">Crear nueva contraseña</h1>
      <form method="POST" action="process_reset.php" class="space-y-4">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
        <div>
          <label class="block text-sm font-medium mb-1">Nueva contraseña</label>
          <input required type="password" name="contrasena" minlength="8" class="w-full border rounded-xl px-3 py-2">
        </div>
        <div>
          <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>
          <input required type="password" name="contrasena_confirm" minlength="8" class="w-full border rounded-xl px-3 py-2">
        </div>
        <button class="w-full rounded-xl px-4 py-2 bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Guardar</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
