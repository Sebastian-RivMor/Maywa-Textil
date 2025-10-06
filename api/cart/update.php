<?php
session_start();
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$action = $_POST['action'] ?? ''; // 'add' o 'subtract'

if (!$id || !isset($_SESSION['cart'])) {
  echo json_encode(['error' => 'Datos inválidos']);
  exit;
}

foreach ($_SESSION['cart'] as &$item) {
  if ($item['id'] == $id) {
    if ($action === 'add') $item['cantidad']++;
    elseif ($action === 'subtract' && $item['cantidad'] > 1) $item['cantidad']--;
    break;
  }
}

echo json_encode(['ok' => true, 'cart' => $_SESSION['cart']]);
