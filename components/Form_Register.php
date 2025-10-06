<?php
// Cargar conexión y departamentos para el <select>
require_once __DIR__ . '/../config/db.php';

$deps = [];
try {
  $stmt = $pdo->query("SELECT id_departamento, nombre_departamento FROM tb_departamento ORDER BY nombre_departamento");
  $deps = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) { $deps = []; }
?>
<!-- Fondo -->
<section class="min-h-screen bg-gradient-to-br from-purple-600 via-fuchsia-600 to-purple-700 p-4 sm:p-6">
  <div class="mx-auto max-w-6xl grid md:grid-cols-2 gap-6 bg-white rounded-2xl p-4 sm:p-8 shadow-xl md:items-stretch">

    <!-- Formulario -->
    <form class="space-y-5 m-0 p-0 block" action="../api/auth/register.php" method="POST" id="registerForm" novalidate>
      
      <!-- Título centrado -->
      <div class="flex flex-col items-center gap-3 text-center">
        <h1 class="text-4xl font-semibold">Regístrate</h1>
      </div>
      <hr class="border-black/20 -mt-1" />

      <!-- Error global -->
      <div id="e_global" class="hidden rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3"></div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Nombre -->
        <div>
          <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre:</label>
          <input id="nombre" name="nombre" type="text" required
                 class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          <p id="e_nombre" class="hidden text-xs text-red-600 mt-1"></p>
        </div>

        <!-- Apellido -->
        <div>
          <label for="apellido" class="block text-sm font-medium text-gray-700 mb-1">Apellido:</label>
          <input id="apellido" name="apellido" type="text" required
                 class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          <p id="e_apellido" class="hidden text-xs text-red-600 mt-1"></p>
        </div>

        <!-- DNI -->
        <div>
          <label for="dni" class="block text-sm font-medium text-gray-700 mb-1">DNI:</label>
          <input id="dni" name="dni" type="text" required inputmode="numeric" pattern="\d{8}" maxlength="8"
                 title="Debe tener exactamente 8 dígitos"
                 class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          <p id="e_dni" class="hidden text-xs text-red-600 mt-1"></p>
        </div>

        <!-- Teléfono -->
        <div>
          <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono:</label>
          <input id="telefono" name="telefono" type="tel" required inputmode="numeric" pattern="\d{9}" maxlength="9"
                 title="Debe tener exactamente 9 dígitos"
                 class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          <p id="e_telefono" class="hidden text-xs text-red-600 mt-1"></p>
        </div>

        <!-- Género -->
        <div>
          <label for="sexo" class="block text-sm font-medium text-gray-700 mb-1">Género:</label>
          <div class="relative">
            <select id="sexo" name="sexo" required
                    class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500 appearance-none">
              <option value="Femenino">Femenino</option>
              <option value="Masculino">Masculino</option>
              <option value="Prefiero no decirlo" selected>Prefiero no decirlo</option>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
              </svg>
            </span>
          </div>
          <p id="e_sexo" class="hidden text-xs text-red-600 mt-1"></p>
        </div>

        <!-- Departamento (dropdown normal con flecha) -->
        <div>
          <label for="id_departamento" class="block text-sm font-medium text-gray-700 mb-1">Departamento:</label>
          <div class="relative">
            <select id="id_departamento" name="id_departamento" required
                    class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 pr-10 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500 appearance-none">
              <option value="">Seleccione su departamento</option>
              <?php foreach ($deps as $d): ?>
                <option value="<?= htmlspecialchars($d['id_departamento']) ?>">
                  <?= htmlspecialchars($d['nombre_departamento']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2">
              <svg class="h-5 w-5 text-purple-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd"/>
              </svg>
            </span>
          </div>
          <p id="e_id_departamento" class="hidden text-xs text-red-600 mt-1"></p>
        </div>

        <!-- Correo -->
        <div>
          <label for="correo" class="block text-sm font-medium text-gray-700 mb-1">Correo:</label>
          <input id="correo" name="correo" type="email" required
                 class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          <p id="e_correo" class="hidden text-xs text-red-600 mt-1"></p>
        </div>

        <!-- Contraseña -->
        <div>
          <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-1">Contraseña:</label>
          <input id="contrasena" name="contrasena" type="password" required minlength="6"
                 pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,}$"
                 title="Mínimo 6 e incluye mayúscula, minúscula, número y carácter especial"
                 class="w-full rounded-full border border-purple-300 bg-white/90 px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-purple-500/70 focus:border-purple-500">
          <p id="e_contrasena" class="hidden text-xs text-red-600 mt-1"></p>
          <p class="text-xs text-gray-500 mt-1">Debe incluir mayúscula, minúscula, número y carácter especial (mín. 6).</p>
        </div>
      </div>

      <!-- Términos -->
      <label class="flex items-start gap-3 text-sm text-gray-700">
        <input id="tc" name="tc" type="checkbox" value="true" required
               class="mt-1 h-4 w-4 rounded border-purple-300 text-purple-600 focus:ring-purple-500">
        <span>Acepto los <span class="font-semibold">términos y condiciones</span> y la <span class="font-semibold">política de privacidad</span></span>
      </label>
      <p id="e_tc" class="hidden text-xs text-red-600 -mt-3 mb-2"></p>

      <a href="index.php?page=sesion" class="block text-sm text-purple-700 hover:underline">¿Ya tiene una cuenta? Inicie sesión</a>

      <button id="btnSubmit" type="submit"
              class="w-full rounded-full bg-gradient-to-r from-purple-700 to-fuchsia-600 text-white py-3 font-semibold hover:opacity-90 disabled:opacity-50 disabled:cursor-not-allowed">
        Crear Cuenta →
      </button>
    </form>

    <!-- Imagen -->
    <div class="rounded-2xl overflow-hidden h-full">
      <img src="assets/img/telas.png" alt="Telas" class="w-full h-full object-cover">
    </div>
  </div>
</section>

<!-- Modal de éxito -->
<div id="successModal"
     class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/50">
  <div class="bg-white rounded-2xl p-8 text-center shadow-2xl w-[320px]">
    <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-green-100 flex items-center justify-center">
      <svg class="h-9 w-9 text-green-600" viewBox="0 0 24 24" fill="none">
        <path d="M20 7L9 18l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <h3 class="text-lg font-semibold">Cuenta creada</h3>
    <p class="text-gray-600 mt-1">¡Exitosamente!</p>
  </div>
</div>

<!-- JS: envío con fetch, errores por campo y modal de éxito -->
<script>
(function () {
  const form = document.getElementById('registerForm');
  const btn  = document.getElementById('btnSubmit');
  const fields = ['nombre','apellido','dni','telefono','sexo','id_departamento','correo','contrasena','tc'];

  function clearErrors() {
    const eg = document.getElementById('e_global');
    eg.classList.add('hidden'); eg.textContent = '';
    fields.forEach(f => {
      const el  = document.getElementById(f);
      const err = document.getElementById('e_'+f);
      if (err) { err.textContent = ''; err.classList.add('hidden'); }
      if (el)  { el.classList.remove('border-red-400','focus:ring-red-500','focus:border-red-500'); }
    });
  }
  function showFieldError(field, msg) {
    const el  = document.getElementById(field);
    const err = document.getElementById('e_'+field);
    if (err) { err.textContent = msg; err.classList.remove('hidden'); }
    if (el)  { el.classList.add('border-red-400','focus:ring-red-500','focus:border-red-500'); }
  }

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    clearErrors();
    if (!form.reportValidity()) return;

    const data = new FormData(form);
    btn.disabled = true;

    try {
      const resp = await fetch(form.action, { method:'POST', body:data, headers:{'X-Requested-With':'fetch'} });
      const json = await resp.json().catch(() => ({}));

      if (!resp.ok || !json.ok) {
        if (json && json.errors) {
          let first = null;
          for (const [k, v] of Object.entries(json.errors)) {
            showFieldError(k, v);
            if (!first) first = k;
          }
          if (first) document.getElementById(first).scrollIntoView({behavior:'smooth', block:'center'});
        } else {
          const eg = document.getElementById('e_global');
          eg.textContent = (json && json.message) ? json.message : 'Ocurrió un error.';
          eg.classList.remove('hidden');
        }
      } else if (json.ok) {
        const modal = document.getElementById('successModal');
        modal.classList.remove('hidden'); modal.classList.add('flex');
        setTimeout(() => {
          window.location.href = json.redirect || '../../public/index.php?page=home';
        }, 1200);
      }
    } catch (err) {
      const eg = document.getElementById('e_global');
      eg.textContent = 'Error de red o servidor. Intenta nuevamente.';
      eg.classList.remove('hidden');
    } finally {
      btn.disabled = false;
    }
  });
})();
</script>
