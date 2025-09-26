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
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Restablecer contraseña</h1>
    <form method="POST" action="process_reset_phone.php" class="space-y-4">
      <input type="hidden" name="id_usuario" value="<?php echo htmlspecialchars($id_usuario); ?>">
      <div>
        <label class="block text-sm font-medium mb-1">Nueva contraseña</label>
        <input required type="password" name="nueva_contrasena" class="w-full border rounded-xl px-3 py-2">
      </div>
      <button class="w-full rounded-xl px-4 py-2 bg-green-600 text-white font-semibold hover:bg-green-700">Cambiar contraseña</button>
    </form>
  </div>
</body>
</html>
