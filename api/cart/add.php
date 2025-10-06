<?php
session_start();
header('Content-Type: application/json');

// Leer datos del POST
$id = $_POST['id'] ?? null;
$nombre = $_POST['nombre'] ?? '';
$categoria = $_POST['categoria'] ?? '';
$stock = $_POST['stock'] ?? 0;
$precio = $_POST['precio'] ?? 0;
$foto = $_POST['foto'] ?? '';

if (!$id) {
  echo json_encode(['error' => 'Falta ID']);
  exit;
}

// Inicializar carrito si no existe
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// Verificar si el producto ya existe
$found = false;
foreach ($_SESSION['cart'] as &$item) {
  if ($item['id'] == $id) {
    $item['cantidad']++;
    $found = true;
    break;
  }
}

// Si no existe, agregarlo
if (!$found) {
  $_SESSION['cart'][] = [
    'id' => $id,
    'nombre' => $nombre,
    'categoria' => $categoria,
    'stock' => (int)$stock,
    'precio' => (float)$precio,
    'foto' => $foto,
    'cantidad' => 1
  ];
}

echo json_encode(['ok' => true, 'cart' => $_SESSION['cart']]);
