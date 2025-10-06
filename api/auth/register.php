<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

function respond_json($arr, $code=200) {
  header('Content-Type: application/json; charset=utf-8');
  http_response_code($code);
  echo json_encode($arr);
  exit;
}

try {
  $nombre          = trim($_POST['nombre'] ?? '');
  $apellido        = trim($_POST['apellido'] ?? '');
  $dni             = trim($_POST['dni'] ?? '');
  $telefono        = trim($_POST['telefono'] ?? '');
  $sexo            = $_POST['sexo'] ?? 'Prefiero no decirlo';
  $id_departamento = $_POST['id_departamento'] ?? '';
  $correo          = trim($_POST['correo'] ?? '');
  $contrasena      = $_POST['contrasena'] ?? '';
  $tc              = $_POST['tc'] ?? null;

  $errors = [];

  // Requeridos
  if ($nombre === '')           $errors['nombre'] = 'El nombre es obligatorio.';
  if ($apellido === '')         $errors['apellido'] = 'El apellido es obligatorio.';
  if ($dni === '')              $errors['dni'] = 'El DNI es obligatorio.';
  if ($telefono === '')         $errors['telefono'] = 'El teléfono es obligatorio.';
  if ($correo === '')           $errors['correo'] = 'El correo es obligatorio.';
  if ($contrasena === '')       $errors['contrasena'] = 'La contraseña es obligatoria.';
  if ($id_departamento === '' ) $errors['id_departamento'] = 'Seleccione un departamento.';
  if (!$tc)                     $errors['tc'] = 'Debes aceptar los términos y condiciones.';

  // Formatos
  if ($dni !== '' && !preg_match('/^\d{8}$/', $dni))             $errors['dni'] = 'El DNI debe tener 8 dígitos.';
  if ($telefono !== '' && !preg_match('/^\d{9}$/', $telefono))   $errors['telefono'] = 'El teléfono debe tener 9 dígitos.';
  if ($correo !== '' && !filter_var($correo, FILTER_VALIDATE_EMAIL)) $errors['correo'] = 'Correo no válido.';

  // Sexo ENUM
  $sexos = ['Masculino','Femenino','Prefiero no decirlo'];
  if ($sexo === '' || !in_array($sexo, $sexos, true)) $sexo = 'Prefiero no decirlo';

  // Contraseña fuerte
  if ($contrasena !== '' && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{6,}$/', $contrasena)) {
    $errors['contrasena'] = 'Mínimo 6 e incluye mayúscula, minúscula, número y carácter especial.';
  }

  // Departamento existe
  if ($id_departamento !== '') {
    $stmt = $pdo->prepare("SELECT 1 FROM tb_departamento WHERE id_departamento = ?");
    $stmt->execute([$id_departamento]);
    if (!$stmt->fetchColumn()) $errors['id_departamento'] = 'El departamento seleccionado no existe.';
  }

  // Unicidad previa
  if (!isset($errors['dni'])) {
    $stmt = $pdo->prepare("SELECT 1 FROM tb_persona WHERE dni = ?");
    $stmt->execute([$dni]);
    if ($stmt->fetchColumn()) $errors['dni'] = 'El DNI ya está registrado.';
  }
  if (!isset($errors['telefono'])) {
    $stmt = $pdo->prepare("SELECT 1 FROM tb_persona WHERE telefono = ?");
    $stmt->execute([$telefono]);
    if ($stmt->fetchColumn()) $errors['telefono'] = 'El teléfono ya está registrado.';
  }
  if (!isset($errors['correo'])) {
    $stmt = $pdo->prepare("SELECT 1 FROM tb_usuario WHERE correo = ?");
    $stmt->execute([$correo]);
    if ($stmt->fetchColumn()) $errors['correo'] = 'El correo ya está registrado.';
  }

  if (!empty($errors)) {
    respond_json(['ok'=>false, 'errors'=>$errors], 422);
  }

  // Crear registros
  $pdo->beginTransaction();

  $stmt = $pdo->prepare("INSERT INTO tb_persona (nombre, apellido, dni, telefono, sexo, id_departamento)
                         VALUES (?, ?, ?, ?, ?, ?)");
  $stmt->execute([$nombre, $apellido, $dni, $telefono, $sexo, $id_departamento]);
  $id_persona = $pdo->lastInsertId();

  // Rol Cliente fijo (2)
  $idRolCliente = 2;
  $exists = $pdo->query("SELECT 1 FROM tb_rol WHERE id_rol = 2")->fetchColumn();
  if (!$exists) {
    $pdo->rollBack();
    respond_json(['ok'=>false, 'errors'=>['correo'=>'No se pudo crear la cuenta (rol Cliente no existe).']], 500);
  }

  $hash = password_hash($contrasena, PASSWORD_BCRYPT);
  $stmt = $pdo->prepare("INSERT INTO tb_usuario (id_persona, correo, contrasena, id_rol, estado_usuario)
                         VALUES (?, ?, ?, ?, 'Activo')");
  $stmt->execute([$id_persona, $correo, $hash, $idRolCliente]);
  $id_usuario = $pdo->lastInsertId();

  $pdo->commit();

  // Sesión
  $_SESSION['usuario'] = [
    'id'       => $id_usuario,
    'nombre'   => $nombre,
    'apellido' => $apellido,
    'correo'   => $correo,
    'rol'      => 'Cliente'
  ];

  respond_json(['ok'=>true, 'redirect'=>'/MAYWATEXTIL/public/index.php?page=home']);

} catch (Throwable $e) {
  if ($pdo->inTransaction()) $pdo->rollBack();

  $msg = $e->getMessage();
  $errors = [];
  if (stripos($msg, 'dni') !== false)       $errors['dni'] = 'El DNI ya está registrado.';
  if (stripos($msg, 'telefono') !== false)  $errors['telefono'] = 'El teléfono ya está registrado.';
  if (stripos($msg, 'correo') !== false)    $errors['correo'] = 'El correo ya está registrado.';

  if (!empty($errors)) {
    respond_json(['ok'=>false, 'errors'=>$errors], 409);
  }
  respond_json(['ok'=>false, 'message'=>'Error en servidor. Intenta nuevamente.'], 500);
}
