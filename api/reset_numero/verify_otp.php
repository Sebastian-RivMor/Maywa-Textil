<?php /* api/auth/verify_otp.php */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Verificar código</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div class="w-full max-w-md bg-white rounded-2xl shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Verificar código</h1>
    <p class="text-sm text-gray-600 mb-6">Ingresa el código que recibiste por SMS para continuar.</p>

    <form method="POST" action="process_otp.php" class="space-y-4">
      <!-- Se mantiene el número en un campo oculto -->
      <input type="hidden" name="telefono" value="<?php echo htmlspecialchars($_GET['telefono'] ?? '') ?>">

      <div>
        <label class="block text-sm font-medium mb-1">Código de verificación</label>
        <input required type="text" name="otp_code" maxlength="6" class="w-full border rounded-xl px-3 py-2" placeholder="123456">
      </div>

      <button class="w-full rounded-xl px-4 py-2 bg-purple-600 text-white font-semibold hover:bg-purple-700">Verificar</button>
    </form>
  </div>
</body>
</html>
