<?php
session_start();
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;

if ($id && isset($_SESSION['cart'])) {
  $_SESSION['cart'] = array_values(array_filter($_SESSION['cart'], fn($p) => $p['id'] != $id));
}

echo json_encode(['ok' => true, 'cart' => $_SESSION['cart'] ?? []]);
