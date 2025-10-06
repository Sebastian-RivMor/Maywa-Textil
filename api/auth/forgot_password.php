<?php /* api/auth/forgot_password.php */ ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Recuperar contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-purple-600 via-fuchsia-600 to-purple-700 p-4 sm:p-6 flex items-center justify-center">
  <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
    <!-- Título -->
    <h1 class="text-3xl font-bold text-black mb-3 text-center">¿Olvidaste tu contraseña?</h1>
    <p class="text-sm text-gray-700 mb-5 text-center">Selecciona el método de recuperación.</p>

    <!-- Tabs -->
    <div class="mb-5">
      <div id="tabsWrap" class="relative bg-purple-200/60 rounded-full p-1 border border-purple-300 overflow-hidden">
        <!-- Indicador -->
        <div id="tabIndicator"
             class="absolute top-1 h-[36px] rounded-full bg-gradient-to-r from-purple-800 to-purple-500 transition-all duration-300"
             style="left: 0.25rem; width: calc(50% - 0.5rem);"></div>

        <div class="relative grid grid-cols-2 gap-2 text-center text-sm font-semibold">
          <button type="button" id="btnTabCorreo"
                  class="py-2 rounded-full z-10 text-white"
                  aria-selected="true"
                  onclick="mostrarCorreo()">Por Correo</button>

          <button type="button" id="btnTabTelefono"
                  class="py-2 rounded-full z-10 text-purple-800"
                  aria-selected="false"
                  onclick="mostrarTelefono()">Por Teléfono</button>
        </div>
      </div>
    </div>

    <!-- Marco -->
    <div class="rounded-3xl border-2 border-purple-300 p-5">
      <!-- FORM: CORREO -->
      <form id="formCorreo" method="POST" action="process_forgot.php" class="space-y-4">
        <label class="block text-sm font-medium text-gray-800">
          Ingrese el correo de su cuenta: <span class="text-red-500">*</span>
        </label>

        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full border border-purple-300 flex items-center justify-center text-purple-600">
            <!-- icon mail -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V8a2 2 0 00-2-2H3a2 2 0 00-2 2v6a2 2 0 002 2z"/>
            </svg>
          </span>
          <input required type="email" name="correo"
                 class="w-full h-12 pl-14 pr-4 rounded-full border border-purple-300 focus:ring-2 focus:ring-purple-400 focus:border-purple-500 outline-none"
                 placeholder="tucorreo@dominio.com" />
        </div>

        <button class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold shadow hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
          Enviar enlace
        </button>
      </form>

      <!-- FORM: TELÉFONO -->
      <form id="formTelefono" method="POST" action="../reset_numero/send_otp.php" class="space-y-4 hidden">
        <label class="block text-sm font-medium text-gray-800">
          Ingrese el teléfono de su cuenta: <span class="text-red-500">*</span>
        </label>

        <div class="relative">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full border border-purple-300 flex items-center justify-center text-purple-600">
            <!-- icon phone -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h2.28a1 1 0 01.95.68l1.1 3.3a1 1 0 01-.25 1.04l-1.6 1.6a15.9 15.9 0 006.36 6.36l1.6-1.6a1 1 0 011.04-.25l3.3 1.1a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.16 21 3 14.84 3 7V6a1 1 0 011-1z"/>
            </svg>
          </span>
          <input required type="tel" name="telefono"
                 class="w-full h-12 pl-14 pr-4 rounded-full border border-purple-300 focus:ring-2 focus:ring-purple-400 focus:border-purple-500 outline-none"
                 placeholder="+51987654321" />
        </div>

        <button class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold shadow hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
          Enviar código
        </button>
      </form>

      <p class="mt-5 text-xs text-gray-500">
        Para aumentar las posibilidades de éxito, restablece tu contraseña en un dispositivo que utilices con frecuencia.
      </p>
    </div>
  </div>

  <script>
    const indicator = document.getElementById('tabIndicator');
    const btnCorreo = document.getElementById('btnTabCorreo');
    const btnTel    = document.getElementById('btnTabTelefono');
    const formCorreo = document.getElementById('formCorreo');
    const formTel    = document.getElementById('formTelefono');

    function activarTab(isCorreo) {
      // Mueve indicador: izquierda (correo) o mitad (teléfono)
      if (isCorreo) {
        indicator.style.left = '0.25rem';                   // 4px
        indicator.style.width = 'calc(50% - 0.5rem)';       // mitad - 8px
        btnCorreo.classList.add('text-white');
        btnCorreo.classList.remove('text-purple-800');
        btnTel.classList.remove('text-white');
        btnTel.classList.add('text-purple-800');
        btnCorreo.setAttribute('aria-selected','true');
        btnTel.setAttribute('aria-selected','false');

        formCorreo.classList.remove('hidden');
        formTel.classList.add('hidden');
      } else {
        indicator.style.left  = 'calc(50% + 0.25rem)';      // mitad + 4px
        indicator.style.width = 'calc(50% - 0.5rem)';
        btnTel.classList.add('text-white');
        btnTel.classList.remove('text-purple-800');
        btnCorreo.classList.remove('text-white');
        btnCorreo.classList.add('text-purple-800');
        btnCorreo.setAttribute('aria-selected','false');
        btnTel.setAttribute('aria-selected','true');

        formCorreo.classList.add('hidden');
        formTel.classList.remove('hidden');
      }
    }

    function mostrarCorreo(){ activarTab(true); }
    function mostrarTelefono(){ activarTab(false); }

    // Por defecto: correo
    activarTab(true);

    // Pequeño UX: deshabilitar botón al enviar (solo correo)
    document.getElementById('formCorreo').addEventListener('submit', function(e){
      const btn = this.querySelector('button[type="submit"]');
      if (btn) { btn.disabled = true; setTimeout(() => btn.disabled = false, 4000); }
      // Deja que el navegador siga la redirección de process_forgot.php → success.php
    });
  </script>
</body>
</html>