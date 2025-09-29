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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-600 via-purple-500 to-purple-700 px-4 py-10">
  <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
    <?php if (!$valid): ?>
      <h1 class="text-2xl font-bold text-center mb-4 text-black">Enlace no válido o expirado</h1>
      <p class="text-gray-600 text-center">Vuelve a solicitar el restablecimiento.</p>
    <?php else: ?>
      <h1 class="text-3xl font-bold text-center mb-6 text-black">Crear nueva contraseña</h1>
      <div class="h-1 w-24 bg-black/80 rounded mx-auto mb-6"></div>
      <form id="formReset" method="POST" action="process_reset.php" class="space-y-6">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

        <div>
          <label class="block text-sm font-medium text-gray-800 mb-2">Nueva contraseña:</label>
          <div class="relative">
            <input required type="password" name="contrasena" minlength="8"
                   class="w-full h-12 px-5 rounded-full border border-purple-400 focus:ring-2 focus:ring-purple-400 outline-none"
                   placeholder="••••••••">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-purple-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </span>
          </div>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-800 mb-2">Confirmar contraseña:</label>
          <div class="relative">
            <input required type="password" name="contrasena_confirm" minlength="8"
                   class="w-full h-12 px-5 rounded-full border border-purple-400 focus:ring-2 focus:ring-purple-400 outline-none"
                   placeholder="••••••••">
            <span class="absolute right-4 top-1/2 -translate-y-1/2 cursor-pointer text-purple-600">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </span>
          </div>
        </div>

        <button type="submit" class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
          Guardar
        </button>
      </form>
    <?php endif; ?>
  </div>

  <!-- Éxito (simulado para después del POST, puede ser condicional) -->
  <?php if (isset($_GET['success']) && $_GET['success'] === '1'): ?>
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white p-8 rounded-2xl shadow-md text-center max-w-sm">
        <div class="flex justify-center mb-4">
          <svg class="w-20 h-20 text-green-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h1 class="text-xl font-bold text-gray-800 mb-2">Contraseña actualizada correctamente.</h1>
        <p class="text-gray-600 text-sm">Ya puedes iniciar sesión.</p>
        <a href="/MAYWA-TEXTIL-MASTER/public/index.php?page=sesion"
           class="mt-6 inline-block px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
          Ir al login
        </a>
      </div>
    </div>
  <?php endif; ?>
</body>
</html>