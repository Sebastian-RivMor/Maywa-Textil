<?php
$msg = isset($_GET['msg']) ? $_GET['msg'] : 'Operación completada.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Éxito</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="bg-white p-8 rounded-2xl shadow-md text-center max-w-sm">
    <!-- animación check -->
    <div class="flex justify-center mb-4">
      <svg class="w-20 h-20 text-green-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>
    <h1 class="text-xl font-bold text-gray-800 mb-2">Si el correo existe, te enviaremos un enlace para restablecer tu contraseña.</h1>
    <p class="text-gray-600 text-sm"><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></p>
    <a href="/MAYWATEXTIL/public/index.php?page=sesion"
       class="mt-6 inline-block px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
      Volver al login
    </a>
  </div>
</body>
</html>
