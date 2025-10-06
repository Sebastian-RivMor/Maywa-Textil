<?php
require_once __DIR__ . '/../../config/db.php';

$token = trim($_POST['token'] ?? '');
$pass1 = $_POST['contrasena'] ?? '';
$pass2 = $_POST['contrasena_confirm'] ?? '';

function fail($msg) {
  http_response_code(400);
  echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"/>'
     . '<script src="https://cdn.tailwindcss.com"></script></head><body class="min-h-screen flex items-center justify-center bg-gray-100">'
     . '<div class="bg-white p-8 rounded-2xl shadow-md text-center max-w-sm">'
     . '<div class="flex justify-center mb-4"><svg class="w-20 h-20 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
     . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></div>'
     . '<h1 class="text-xl font-bold text-gray-800 mb-2">'.htmlspecialchars($msg,ENT_QUOTES,'UTF-8').'</h1>'
     . '<a href="/MAYWATEXTIL/public/index.php?page=sesion" class="mt-6 inline-block px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Ir al login</a>'
     . '</div></body></html>';
  exit;
}

if ($token === '' || $pass1 === '' || $pass2 === '') fail('Datos incompletos.');
if ($pass1 !== $pass2) fail('Las contraseñas no coinciden.');
if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,}$/', $pass1))
  fail('La contraseña no cumple los requisitos.');

$stmt = $pdo->prepare(
  'SELECT pr.id, pr.id_usuario
     FROM password_resets pr
    WHERE pr.token = ?
      AND pr.expires_at > NOW()
      AND pr.used_at IS NULL
      AND pr.invalidated_at IS NULL
    LIMIT 1'
);
$stmt->execute([$token]);
$reset = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$reset) fail('Token inválido o expirado.');

try {
  $pdo->beginTransaction();

  // Actualiza contraseña
  $hash = password_hash($pass1, PASSWORD_BCRYPT);
  $pdo->prepare('UPDATE tb_usuario SET contrasena = ? WHERE id_usuario = ?')
      ->execute([$hash, $reset['id_usuario']]);

  // Marca token como usado
  $pdo->prepare('UPDATE password_resets SET used_at = NOW() WHERE id = ?')
      ->execute([$reset['id']]);

  // Invalida cualquier otro token pendiente del mismo usuario
  $pdo->prepare(
    'UPDATE password_resets
        SET invalidated_at = NOW()
      WHERE id_usuario = ?
        AND id <> ?
        AND used_at IS NULL
        AND invalidated_at IS NULL'
  )->execute([$reset['id_usuario'], $reset['id']]);

  $pdo->commit();
} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();
  fail('No se pudo actualizar tu contraseña.');
}

// Éxito con redirección en 5s
echo '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"/>'
   . '<meta http-equiv="refresh" content="5;url=/MAYWATEXTIL/public/index.php?page=sesion"/>'
   . '<script src="https://cdn.tailwindcss.com"></script></head>'
   . '<body class="min-h-screen flex items-center justify-center bg-gray-100">'
   . '<div class="bg-white p-8 rounded-2xl shadow-md text-center max-w-sm">'
   . '<div class="flex justify-center mb-4"><svg class="w-20 h-20 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">'
   . '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>'
   . '<h1 class="text-xl font-bold text-gray-800 mb-2">Contraseña actualizada correctamente. Ya puedes iniciar sesión.</h1>'
   . '<p class="text-gray-600 text-sm">Te redirigiremos en 5 segundos…</p>'
   . '<a href="/MAYWATEXTIL/public/index.php?page=sesion" class="mt-6 inline-block px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">Ir ahora</a>'
   . '</div></body></html>';