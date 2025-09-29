<?php /* api/auth/forgot_password.php */
$layout = 'datos.php';

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recuperar contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen min-h-screen bg-gradient-to-br from-purple-600 via-fuchsia-600 to-purple-700 p-4 sm:p-6 flex items-center justify-center px-4 py-8">
  <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
    <!-- Título con subrayado grueso -->
    <h1 class="text-3xl font-bold text-black mb-3">¿Olvidaste tu contraseña?</h1>


    <p class="text-sm text-gray-700 mb-5">Selecciona el método de recuperación.</p>

    <!-- Tabs en pastilla con gradiente -->
    <div class="mb-5">
      <div class="relative bg-purple-200/60 rounded-full p-1 border border-purple-300 overflow-hidden">
        <div id="tabIndicator" class="absolute top-1 left-1 h-[36px] w-[calc(50%-0.25rem)] rounded-full bg-gradient-to-r from-purple-800 to-purple-500 transition-all duration-300"></div>
        <div class="relative grid grid-cols-2 gap-2 text-center text-sm font-semibold">
          <button type="button" id="btnTabCorreo"
                  class="py-2 rounded-full z-10 text-white"
                  onclick="mostrarCorreo()">Por Correo</button>
          <button type="button" id="btnTabTelefono"
                  class="py-2 rounded-full z-10 text-purple-800"
                  onclick="mostrarTelefono()">Por Teléfono</button>
        </div>
      </div>
    </div>

    <!-- Marco del formulario -->
    <div class="rounded-3xl border-2 border-purple-300 p-5">

      <!-- FORMULARIO: CORREO -->
      <form id="formCorreo" method="POST" action="process_forgot.php" class="space-y-4">
        <label class="block text-sm font-medium text-gray-800">Ingrese el correo de su cuenta: <span class="text-red-500">*</span></label>

        <div class="relative">
          <!-- ícono -->
          <span class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full border border-purple-300 flex items-center justify-center text-purple-600">
            <!-- mail icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"/>
            </svg>
          </span>
          <input required type="email" name="correo"
                class="w-full h-12 pl-14 pr-4 rounded-full border border-purple-300 focus:ring-2 focus:ring-purple-400 focus:border-purple-500 outline-none"
                placeholder="tucorreo@dominio.com">
        </div>

        <button class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold shadow hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
          Enviar enlace
        </button>
      </form>

      <!-- FORMULARIO: TELÉFONO -->
      <form id="formTelefono" method="POST" action="../reset_numero/send_otp.php" class="space-y-4 hidden">
        <label class="block text-sm font-medium text-gray-800">Ingrese el teléfono de su cuenta: <span class="text-red-500">*</span></label>

        <div class="relative">
          <!-- ícono -->
          <span class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full border border-purple-300 flex items-center justify-center text-purple-600">
            <!-- phone icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.25 1.04l-1.6 1.6a15.9 15.9 0 006.36 6.36l1.6-1.6a1 1 0 011.04-.25l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.16 21 3 14.84 3 7V6a1 1 0 011-1z"/>
            </svg>
          </span>
          <input required type="tel" name="telefono"
                class="w-full h-12 pl-14 pr-4 rounded-full border border-purple-300 focus:ring-2 focus:ring-purple-400 focus:border-purple-500 outline-none"
                placeholder="+51987654321">
        </div>

        <button class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold shadow hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
          Enviar código
        </button>
      </form>

      <!-- Copy inferior -->
      <p class="mt-5 text-xs text-gray-500">
        Para aumentar las posibilidades de éxito, restablezca su contraseña en un dispositivo que utilice con frecuencia.
      </p>
    </div>
  </div>

  <script>
    function mostrarCorreo() {
      document.getElementById('formCorreo').classList.remove('hidden');
      document.getElementById('formTelefono').classList.add('hidden');
      document.getElementById('btnTabCorreo').classList.add('bg-purple-500', 'text-white');
      document.getElementById('btnTabCorreo').classList.remove('bg-white', 'text-purple-700');
      document.getElementById('btnTabTelefono').classList.remove('bg-purple-500', 'text-white');
      document.getElementById('btnTabTelefono').classList.add('bg-white', 'text-purple-700');
    }

    function mostrarTelefono() {
      document.getElementById('formCorreo').classList.add('hidden');
      document.getElementById('formTelefono').classList.remove('hidden');
      document.getElementById('btnTabTelefono').classList.add('bg-purple-500', 'text-white');
      document.getElementById('btnTabTelefono').classList.remove('bg-white', 'text-purple-700');
      document.getElementById('btnTabCorreo').classList.remove('bg-purple-500', 'text-white');
      document.getElementById('btnTabCorreo').classList.add('bg-white', 'text-purple-700');
    }

    // Mostrar teléfono por defecto si lo deseas
    // mostrarTelefono();
  </script>
</body>
</html>
