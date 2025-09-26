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
    <p class="text-sm text-gray-600 mb-6">Selecciona el método de recuperación.</p>

    <!-- FORMULARIO DE RECUPERACIÓN POR CORREO -->
    <form id="formCorreo" method="POST" action="process_forgot.php" class="space-y-4">
      <div>
        <label class="block text-sm font-medium mb-1">Correo</label>
        <input required type="email" name="correo" class="w-full border rounded-xl px-3 py-2" placeholder="tucorreo@dominio.com">
      </div>
      <button class="w-full rounded-xl px-4 py-2 bg-indigo-600 text-white font-semibold hover:bg-indigo-700">Enviar enlace</button>
    </form>

    <!-- FORMULARIO DE RECUPERACIÓN POR TELÉFONO -->
    <form id="formTelefono" method="POST" action="../reset_numero/send_otp.php" class="space-y-4 hidden">
      <div>
        <label class="block text-sm font-medium mb-1">Número de teléfono</label>
        <input required type="tel" name="telefono" class="w-full border rounded-xl px-3 py-2" placeholder="+51987654321">
      </div>
      <button class="w-full rounded-xl px-4 py-2 bg-purple-600 text-white font-semibold hover:bg-purple-700">Enviar código</button>
    </form>

    <!-- BOTONES DE CAMBIO DE MÉTODO -->
    <div class="mt-6 text-center space-y-2">
      <button onclick="mostrarCorreo()" id="btnCorreo" class="text-sm text-indigo-600 hover:underline font-medium hidden">¿Prefieres recuperar por correo?</button>
      <button onclick="mostrarTelefono()" id="btnTelefono" class="text-sm text-purple-600 hover:underline font-medium">¿Prefieres recuperar por teléfono?</button>
    </div>
  </div>

  <script>
    function mostrarTelefono() {
      document.getElementById('formCorreo').classList.add('hidden');
      document.getElementById('formTelefono').classList.remove('hidden');
      document.getElementById('btnTelefono').classList.add('hidden');
      document.getElementById('btnCorreo').classList.remove('hidden');
    }

    function mostrarCorreo() {
      document.getElementById('formTelefono').classList.add('hidden');
      document.getElementById('formCorreo').classList.remove('hidden');
      document.getElementById('btnCorreo').classList.add('hidden');
      document.getElementById('btnTelefono').classList.remove('hidden');
    }
  </script>
</body>
</html>
