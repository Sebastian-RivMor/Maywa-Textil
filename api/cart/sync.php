<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['cart']) || !is_array($data['cart'])) {
  echo json_encode(['status' => 'error', 'message' => 'Datos inválidos']);
  exit;
}

// Guardar carrito completo
$_SESSION['carrito'] = $data['cart'];

// Calcular total
$total = 0;
foreach ($_SESSION['carrito'] as $item) {
  $total += floatval($item['precio']) * intval($item['cantidad'] ?? 1);
}
$_SESSION['total_carrito'] = $total;

echo json_encode([
  'status' => 'ok',
  'total' => number_format($total, 2)
]);
