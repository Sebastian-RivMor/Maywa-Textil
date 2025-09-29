<?php /* api/auth/verify_otp.php */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificar código</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-600 via-purple-500 to-purple-700 px-4 py-10">

  <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
    <!-- Título y subrayado -->
    <h1 class="text-3xl font-bold text-black text-center mb-2">Verificar código</h1>
    <div class="h-1 w-24 bg-black/80 rounded mx-auto mb-6"></div>

    <p class="text-sm text-gray-700 text-center mb-6">Ingresa el código que recibiste por SMS para continuar.</p>

    <form method="POST" action="process_otp.php" class="space-y-6">
      <!-- Campo oculto con teléfono -->
      <input type="hidden" name="telefono" value="<?= htmlspecialchars($_GET['telefono'] ?? '') ?>">

      <div>
        <label class="block text-sm font-semibold text-gray-800 mb-2">Código de verificación:</label>
        <input required type="text" name="otp_code" maxlength="6"
               class="w-full h-12 px-5 rounded-full border border-purple-400 focus:ring-2 focus:ring-purple-400 outline-none"
               placeholder="123456">
      </div>

      <button type="submit"
              class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
        Verificar
      </button>
    </form>
  </div>

</body>
</html>
