<?php
require_once __DIR__ . '/../../config/db.php';
$token = trim($_GET['token'] ?? '');

$stmt = $pdo->prepare(
  'SELECT pr.id, pr.id_usuario, pr.expires_at, u.correo
     FROM password_resets pr
     JOIN tb_usuario u ON u.id_usuario = pr.id_usuario
    WHERE pr.token = ?
      AND pr.expires_at > NOW()
      AND pr.used_at IS NULL
      AND pr.invalidated_at IS NULL
    LIMIT 1'
);
$stmt->execute([$token]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);
$valid = (bool)$reset;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Restablecer contraseña</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-r from-purple-600 via-purple-500 to-purple-700 px-4 py-10">
  <div class="w-full max-w-md bg-white rounded-3xl shadow-xl p-8">
    <?php if (!$valid): ?>
      <h1 class="text-2xl font-bold text-center mb-4 text-black">Enlace no válido o expirado</h1>
      <p class="text-gray-600 text-center">Vuelve a solicitar el restablecimiento.</p>
    <?php else: ?>
      <h1 class="text-3xl font-bold text-center mb-6 text-black">Crear nueva contraseña</h1>
      <div class="h-1 w-24 bg-black/80 rounded mx-auto mb-6"></div>

      <form id="formReset" method="POST" action="process_reset.php" class="space-y-6" novalidate>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

        <!-- Nueva contraseña -->
        <div>
          <label class="block text-sm font-medium text-gray-800 mb-2" for="pass1">Nueva contraseña:</label>
          <div class="relative">
            <input id="pass1" required type="password" name="contrasena"
                   pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,}$"
                   title="Mínimo 6 e incluye mayúscula, minúscula, número y carácter especial"
                   class="w-full h-12 pl-5 pr-12 rounded-full border border-purple-400 focus:ring-2 focus:ring-purple-400 outline-none placeholder-gray-400"
                   placeholder="••••••••">
            <!-- Ojo 1 -->
            <button type="button" id="toggle1"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-purple-600"
                    aria-pressed="false" title="Mostrar contraseña">
              <svg id="eye1" class="w-5 h-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3" stroke-width="1.8"/>
              </svg>
              <svg id="eyeOff1" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7a20.59 20.59 0 014.32-4.91M9.9 4.24A10.93 10.93 0 0112 5c7 0 11 7 11 7a20.66 20.66 0 01-3.62 4.35"/>
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M1 1l22 22"/>
              </svg>
            </button>
          </div>
          <p id="e_strength" class="hidden text-xs text-red-600 mt-2"></p>
          <p class="text-xs text-gray-500 mt-1">Debe incluir mayúscula, minúscula, número y carácter especial (mín. 6).</p>
        </div>

        <!-- Confirmación -->
        <div>
          <label class="block text-sm font-medium text-gray-800 mb-2" for="pass2">Confirmar contraseña:</label>
          <div class="relative">
            <input id="pass2" required type="password" name="contrasena_confirm"
                   class="w-full h-12 pl-5 pr-12 rounded-full border border-purple-400 focus:ring-2 focus:ring-purple-400 outline-none placeholder-gray-400"
                   placeholder="••••••••">
            <!-- Ojo 2 -->
            <button type="button" id="toggle2"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-purple-600"
                    aria-pressed="false" title="Mostrar contraseña">
              <svg id="eye2" class="w-5 h-5 block" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/>
                <circle cx="12" cy="12" r="3" stroke-width="1.8"/>
              </svg>
              <svg id="eyeOff2" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M17.94 17.94A10.94 10.94 0 0112 19c-7 0-11-7-11-7a20.59 20.59 0 014.32-4.91M9.9 4.24A10.93 10.93 0 0112 5c7 0 11 7 11 7a20.66 20.66 0 01-3.62 4.35"/>
                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M1 1l22 22"/>
              </svg>
            </button>
          </div>
          <p id="e_match" class="hidden text-xs text-red-600 mt-2"></p>
        </div>

        <button type="submit" id="btnSave"
                class="w-full h-12 rounded-full bg-gradient-to-r from-purple-800 via-purple-600 to-purple-400 text-white font-semibold hover:from-purple-900 hover:via-purple-700 hover:to-purple-500 transition">
          Guardar
        </button>
      </form>
    <?php endif; ?>
  </div>

  <script>
    // Ojos (versión corregida)
    function setupToggle(btnId, inputId, eyeId, eyeOffId) {
      const btn    = document.getElementById(btnId);
      const input  = document.getElementById(inputId);
      const eye    = document.getElementById(eyeId);     // ojo abierto
      const eyeOff = document.getElementById(eyeOffId);  // ojo tachado
      if (!btn || !input || !eye || !eyeOff) return;

      btn.addEventListener('click', () => {
        const willReveal = (input.type === 'password'); // vamos a mostrar
        input.type = willReveal ? 'text' : 'password';

        if (willReveal) {
          // mostrar => ojo tachado visible, ojo abierto oculto
          eye.classList.add('hidden');
          eyeOff.classList.remove('hidden');
          btn.setAttribute('aria-pressed', 'true');
          btn.setAttribute('title', 'Ocultar contraseña');
        } else {
          // ocultar => ojo abierto visible, ojo tachado oculto
          eye.classList.remove('hidden');
          eyeOff.classList.add('hidden');
          btn.setAttribute('aria-pressed', 'false');
          btn.setAttribute('title', 'Mostrar contraseña');
        }
      });
    }
    setupToggle('toggle1','pass1','eye1','eyeOff1');
    setupToggle('toggle2','pass2','eye2','eyeOff2');

    // Validación simple en vivo
    const form   = document.getElementById('formReset');
    const pass1  = document.getElementById('pass1');
    const pass2  = document.getElementById('pass2');
    const eStr   = document.getElementById('e_strength');
    const eMatch = document.getElementById('e_match');
    const regex  = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,}$/;

    function validateStrength() {
      if (!pass1.value) { eStr.classList.add('hidden'); return true; }
      if (!regex.test(pass1.value)) {
        eStr.textContent = 'La contraseña no cumple los requisitos.';
        eStr.classList.remove('hidden');
        return false;
      }
      eStr.classList.add('hidden'); return true;
    }
    function validateMatch() {
      if (!pass2.value) { eMatch.classList.add('hidden'); return true; }
      if (pass1.value !== pass2.value) {
        eMatch.textContent = 'Las contraseñas no coinciden.';
        eMatch.classList.remove('hidden');
        return false;
      }
      eMatch.classList.add('hidden'); return true;
    }
    pass1.addEventListener('input', () => { validateStrength(); validateMatch(); });
    pass2.addEventListener('input', validateMatch);
    form.addEventListener('submit', (e) => {
      const ok1 = validateStrength();
      const ok2 = validateMatch();
      if (!ok1 || !ok2) { e.preventDefault(); (!ok1 ? pass1 : pass2).focus(); }
    });
  </script>
</body>
</html>