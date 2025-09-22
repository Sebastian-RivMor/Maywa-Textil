<!-- Fondo -->
<section class="min-h-screen bg-gradient-to-br from-purple-600 via-fuchsia-600 to-purple-700 p-4 sm:p-6">
  <!-- Card -->
  <div class="mx-auto max-w-6xl grid md:grid-cols-2 gap-6 bg-white rounded-2xl p-4 sm:p-8 shadow-xl">

    <!-- Columna izquierda: Form -->
    <form class="space-y-5 m-0 p-0 block" action="../api/auth/register.php" method="POST" id="registerForm">
      <!-- Logo + título -->
      <div class="flex items-center gap-3">
        <img src="assets/img/logo.png" alt="Logo" class="h-24 w-24 rounded-full ring-2 ring-purple-200">
        <h1 class="text-4xl font-semibold">Regístrate</h1>
      </div>
      <hr class="border-black/20 -mt-1" />
      <!-- Mensaje de éxito -->
      <!-- Grid campos -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Nombre -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Nombre:</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M15.75 7.5a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 1115 0"/>
              </svg>
            </span>
            <input name="nombre" type="text" placeholder="Nombre" required
                   class="w-full rounded-full border border-purple-300 bg-white/90 pl-10 pr-4 py-2.5 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          </div>
        </div>

        <!-- Apellido -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Apellido:</label>
          <input name="apellido" type="text" placeholder="Apellido" required
                 class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
        </div>

        <!-- DNI -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">DNI:</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3.75 5.25h16.5m-15 4.5h13.5m-13.5 4.5h10.5M6 19.5h12"/>
              </svg>
            </span>
            <input name="dni" type="text" placeholder="DNI" required
                   class="w-full rounded-full border border-purple-300 bg-white/90 pl-10 pr-4 py-2.5 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          </div>
        </div>

        <!-- Teléfono -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono:</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M2.25 6.75c0 8.01 6.99 15 15 15 .9 0 1.77-.07 2.63-.2a1.5 1.5 0 00.98-2.45l-2.33-2.72a1.5 1.5 0 00-1.62-.44l-2.54.85a12.04 12.04 0 01-5.71-5.71l.85-2.54a1.5 1.5 0 00-.44-1.62L4.9 2.64A1.5 1.5 0 002.45 3.6c-.13.86-.2 1.73-.2 2.63z"/>
              </svg>
            </span>
            <input name="telefono" type="tel" placeholder="Teléfono" 
                   class="w-full rounded-full border border-purple-300 bg-white/90 pl-10 pr-4 py-2.5 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          </div>
        </div>

        <!-- Género -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Género:</label>
          <div class="relative">
            <select name="sexo" required
                    class="w-full appearance-none rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
              <option value="">Seleccione su género</option>
              <option value="2">Femenino</option>
              <option value="1">Masculino</option>
              <option value="3">Otro</option>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
              </svg>
            </span>
          </div>
        </div>

        <!-- Provincia -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Provincia:</label>
          <div class="relative">
            <select for="provincia" name="id_provincia" required
                    class="w-full appearance-none rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
              <option value="">Seleccione su provincia</option>
              <option value="1">Lima</option>
              <option value="2">Cusco</option>
              <option value="3">Puno</option>
              <option value="4">Arequipa</option>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="CurrentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
              </svg>
            </span>
          </div>
        </div>

        <!-- Correo -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Correo:</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 8l9 6 9-6M4.5 6h15a1.5 1.5 0 011.5 1.5v9A1.5 1.5 0 0119.5 18h-15A1.5 1.5 0 013 16.5v-9A1.5 1.5 0 014.5 6z"/>
              </svg>
            </span>
            <input name="correo" type="email" placeholder="Correo" required
                   class="w-full rounded-full border border-purple-300 bg-white/90 pl-10 pr-4 py-2.5 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          </div>
        </div>

        <!-- Contraseña -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña:</label>
          <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875A4.875 4.875 0 007.5 7.5V10.5m-3 0h15v9a1.5 1.5 0 01-1.5 1.5h-12A1.5 1.5 0 014.5 19.5v-9z"/>
              </svg>
            </span>
            <input name="contrasena" type="password" placeholder="Tu contraseña" required minlength="6"
                   class="w-full rounded-full border border-purple-300 bg-white/90 pl-10 pr-11 py-2.5 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          </div>
        </div>
      </div>

      <!-- Checkbox -->
      <label class="flex items-start gap-3 text-sm text-gray-700">
        <input name="tc" type="checkbox" value="true" required
               class="mt-1 h-4 w-4 rounded border-purple-300 text-purple-600 focus:ring-purple-500">
        <span>
          Acepto los <span class="font-semibold">términos y condiciones</span> y la <span class="font-semibold">política de privacidad</span>
        </span>
      </label>

      <a href="/login/" class="block text-sm text-purple-700 hover:underline">
        ¿Ya tiene una cuenta? Inicie sesión
      </a>

      <!-- Botón de envío -->
      <button type="submit"
              class="w-full rounded-full bg-gradient-to-r from-purple-700 to-fuchsia-600 text-white py-3 font-semibold hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed">
        Crear Cuenta →
      </button>
    </form>

    <!-- Columna derecha: Imagen -->
    <div class="rounded-2xl overflow-hidden">
      <img src="assets/img/telas.png" alt="Telas" class="h-full w-full object-cover aspect-[4/3] md:aspect-auto">
    </div>
  </div>
</section>