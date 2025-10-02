<section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-600 via-fuchsia-600 to-purple-700 p-4">
  <!-- Card con tamaño fijo -->
  <div class="mx-auto max-w-6xl grid md:grid-cols-2 gap-6 bg-white rounded-2xl p-4 sm:p-8 shadow-xl">

    <!-- Columna Izquierda: Imagen + Logo -->
    <div class="relative">
      <img
        src="assets/img/aguja.png"  
        alt="Tela y herramientas de costura"
        class="w-full h-full object-cover"
      />
      <img
        src="assets/img/logo.png"  
        alt="Maywa Textil"
        class="absolute top-6 left-6 h-16 w-16 rounded-full ring-2 ring-white/80 shadow-md"
      />
    </div>

    <!-- Columna Derecha: Form -->
    <div class="p-12 flex flex-col justify-center">
      <h1 class="text-5xl font-semibold text-right">Iniciar Sesión</h1>
      <hr class="mt-2 mb-8 border-black/20" />

      <!-- ✅ Form listo para enviar -->
      <form id="form-login"
            class="space-y-6"
            method="post"
            action="../api/auth/login.php"
            autocomplete="on">
        <!-- Correo -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Correo electrónico:</label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600">
              <!-- icono correo -->
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 8l9 6 9-6M4.5 6h15A1.5 1.5 0 0121 7.5v9A1.5 1.5 0 0119.5 18h-15A1.5 1.5 0 013 16.5v-9A1.5 1.5 0 014.5 6z"/>
              </svg>
            </span>
            <input type="email"
                   name="correo"
                   placeholder="tuemail@com"
                   required
                   class="w-full rounded-full border border-purple-300 bg-white/90 pl-12 pr-4 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500 text-lg" />
          </div>
        </div>

        <!-- Contraseña -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña:</label>
          <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600">
              <!-- icono candado -->
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M16.5 10.5V8a4.5 4.5 0 10-9 0v2.5M6 10.5h12v8.25A1.25 1.25 0 0116.75 20H7.25A1.25 1.25 0 016 18.75V10.5z"/>
              </svg>
            </span>
            <input type="password"
                   name="contrasena"
                   placeholder="Tu contraseña"
                   required
                   class="w-full rounded-full border border-purple-300 bg-white/90 pl-12 pr-12 py-3 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500 text-lg" />
            <!-- icono ojo -->
            <button type="button"
                    id="toggle-pass"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-purple-600
                          transition transform duration-150 ease-out active:scale-95"
                    aria-pressed="false" title="Mostrar contraseña">
              <!-- eye (visible por defecto) -->
              <svg id="icon-eye" class="h-6 w-6 block" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                      d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3" stroke-width="1.7"/>
              </svg>
              <!-- eye-off (oculto al inicio) -->
              <svg id="icon-eye-off" class="h-6 w-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                      d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7a20.59 20.59 0 014.32-4.91M9.9 4.24A10.93 10.93 0 0112 5c7 0 11 7 11 7a20.66 20.66 0 01-3.62 4.35"/>
                <path stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"
                      d="M1 1l22 22"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Recordarme + Olvidaste -->
        <div class="flex items-center justify-between text-sm">
          <label class="inline-flex items-center gap-2 text-gray-700 text-base">
            <input type="checkbox" class="h-5 w-5 rounded border-purple-300 text-purple-600 focus:ring-purple-500">
            Recordarme
          </label>
          <a href="../api/auth/forgot_password.php" class="text-gray-700 hover:text-purple-700 text-base">¿Olvidaste tu contraseña?</a>
          <p class="text-sm mt-2">
        </div>

        <!-- Enlace a registro -->
        <a href="/registro" class="block text-base text-purple-700 hover:underline">
          ¿Aún no tiene una cuenta? Regístrese
        </a>

        <!-- Errores -->
        <p id="login-error" class="hidden text-red-600 text-sm"></p>

        <!-- Botón -->
        <button type="submit"
          class="w-full rounded-full bg-gradient-to-r from-purple-700 to-fuchsia-600 text-white py-4 text-lg font-semibold hover:opacity-90">
          Continúa →
        </button>
      </form>

      <!-- Footer -->
      <div class="mt-12 text-center text-sm text-gray-500 space-y-1">
        <p>© 2025 Maywa Textil. Todos los derechos reservados.</p>
        <p>
          <a href="/soporte" class="hover:text-purple-700">Soporte</a> •
          <a href="/contacto" class="hover:text-purple-700">Contacto</a> •
          <a href="/ayuda" class="hover:text-purple-700">Ayuda</a>
        </p>
      </div>
    </div>
  </div>
</section>

<script>
  // Enviar con fetch y redirigir al home cuando el login sea correcto
  document.getElementById('form-login').addEventListener('submit', async (e) => {
    e.preventDefault();

    const form  = e.currentTarget;
    const error = document.getElementById('login-error');
    error.classList.add('hidden');
    error.textContent = '';

    try {
      const res  = await fetch(form.action, { method: 'POST', body: new FormData(form) });
      const data = await res.json();

      if (data && data.success) {
        window.location.replace(data.redirect); // usa lo que mande el backend
      } else {
        error.textContent = (data && data.error) || 'No se pudo iniciar sesión.';
        error.classList.remove('hidden');
      }
    } catch (err) {
      error.textContent = 'Error de conexión. Inténtalo nuevamente.';
      error.classList.remove('hidden');
    }
  });

  (function () {
    const input  = document.querySelector('input[name="contrasena"]');
    const btn    = document.getElementById('toggle-pass');
    if (!input || !btn) return;

    const eye    = document.getElementById('icon-eye');
    const eyeOff = document.getElementById('icon-eye-off');

    const toggle = () => {
      const showing = input.type === 'text';
      // anima un poco
      btn.classList.add('rotate-6');
      setTimeout(() => btn.classList.remove('rotate-6'), 120);

      if (showing) {
        input.type = 'password';
        btn.setAttribute('aria-pressed', 'false');
        btn.setAttribute('title', 'Mostrar contraseña');
        eye.classList.remove('hidden');   // eye on
        eyeOff.classList.add('hidden');   // eye-off off
      } else {
        input.type = 'text';
        btn.setAttribute('aria-pressed', 'true');
        btn.setAttribute('title', 'Ocultar contraseña');
        eye.classList.add('hidden');      // eye off
        eyeOff.classList.remove('hidden');// eye-off on
      }
    };

    btn.addEventListener('click', toggle);
    // Accesible por teclado cuando el input tiene foco (Alt+*)
    input.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && input.type === 'text') toggle();
    });
  })();
</script>