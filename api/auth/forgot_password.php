<?php /* api/auth/forgot_password.php */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold mb-4">¿Olvidaste tu contraseña?</h1>
    <p class="text-sm text-gray-600 mb-6">Ingresa tu correo y, si existe, te enviaremos un enlace.</p>
    <form method="POST" action="process_forgot.php" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Correo</label>
        <input required type="email" name="correo" class="w-full border rounded-xl px-3 py-2" placeholder="tucorreo@dominio.com">
      </div>
      <button class="w-full rounded-xl px-4 py-2 bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Enviar enlace</button>
    </form>
  </div>
</body>
</html>
