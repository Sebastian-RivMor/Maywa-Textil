<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/db.php';

$response = ['success' => false];

try {
  if (!isset($pdo)) throw new Exception("No se pudo conectar a la base de datos.");
  $id = $_POST['id_producto'] ?? null;
  if (!$id) throw new Exception("ID no válido.");

  $stmt = $pdo->prepare("DELETE FROM tb_producto WHERE id_producto = :id");
  $stmt->execute([':id' => $id]);

  $response['success'] = true;
} catch (Exception $e) {
  $response['error'] = $e->getMessage();
}

echo json_encode($response);
