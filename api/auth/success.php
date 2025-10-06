<?php
declare(strict_types=1);

// Mensaje desde querystring (sanitizado)
$raw = $_GET['msg'] ?? 'Si el correo está registrado, te enviaremos un enlace para restablecer tu contraseña.';
$msg = trim(strip_tags($raw));

// Detectar URL del login según la ruta actual (/MAYWATEXTIL/api/auth/success.php)
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host     = $_SERVER['HTTP_HOST'] ?? 'localhost';
$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');   // ej: /MAYWATEXTIL/api/auth
$appBase  = preg_replace('#/api/auth$#', '', $basePath);                         // ej: /MAYWATEXTIL
$loginRel = $appBase ? ($appBase . '/public/index.php?page=sesion') : '/MAYWATEXTIL/public/index.php?page=sesion';
$loginUrl = $scheme . '://' . $host . $loginRel;

// Segundos para redirigir
$seconds = 5;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Éxito</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-600 via-fuchsia-600 to-purple-700 p-4">
  <div class="bg-white p-8 rounded-2xl shadow-xl text-center max-w-sm w-full">
    <!-- Check -->
    <div class="flex justify-center mb-4">
      <svg class="w-20 h-20 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>

    <h1 class="text-xl font-bold text-gray-800 mb-2">Revisa tu correo</h1>
    <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>

    <p class="text-gray-500 text-sm mb-6">
      Te redirigiremos al login en <span id="count"><?= (int)$seconds ?></span>…
    </p>

    <a href="<?= htmlspecialchars($loginUrl, ENT_QUOTES, 'UTF-8') ?>"
       class="inline-block px-4 py-2 rounded-full bg-purple-600 text-white font-semibold hover:bg-purple-700 transition">
      Ir al login ahora
    </a>
  </div>

  <script>
    (function () {
      var s = <?= (int)$seconds ?>;
      var el = document.getElementById('count');
      var url = <?= json_encode($loginUrl, JSON_UNESCAPED_SLASHES) ?>;

      var t = setInterval(function () {
        s--;
        if (el) el.textContent = s;
        if (s <= 0) {
          clearInterval(t);
          window.location.replace(url);
        }
      }, 1000);
    })();
  </script>
</body>
</html>
