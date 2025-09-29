<?php
require_once __DIR__ . '/../../config/db.php';

$id_usuario = $_GET['id_usuario'] ?? null;

if (!$id_usuario) {
    echo "Acceso no autorizado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restablecer contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-r from-purple-600 via-purple-500 to-purple-700 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
    <h1 class="text-3xl font-extrabold text-center text-black mb-4">Restablecer contraseña</h1>

    <form method="POST" action="process_reset_phone.php" class="space-y-6">
      <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($id_usuario); ?>">

      <div>
        <label class="block text-sm font-semibold text-gray-800 mb-2">Nueva contraseña:</label>
        <div class="relative">
          <input required type="password" name="nueva_contrasena"
                 class="w-full h-12 px-5 rounded-full border border-purple-400 focus:ring-2 focus:ring-purple-400 outline-none"
                 placeholder="••••••••">
          <span class="absolute right-4 top-1/2 -translate-y-1/2 text-purple-500 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </span>
        </div>
      </div>

      <button type="submit"
              class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
        Cambiar contraseña
      </button>
    </form>
  </div>
</body>
</html>
