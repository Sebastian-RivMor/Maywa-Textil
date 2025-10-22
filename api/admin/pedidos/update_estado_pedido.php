<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../../../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)($data['id_pedido'] ?? 0);
$estado = trim($data['estado'] ?? '');

$validos = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];
if ($id <= 0 || !in_array($estado, $validos)) {
  http_response_code(400);
  echo json_encode(['error' => 'Datos inválidos']);
  exit;
}

try {
  // Actualizar estado en tb_pedido
  $stmt = $pdo->prepare("UPDATE tb_pedido SET estado_pedido = ? WHERE id_pedido = ?");
  $stmt->execute([$estado, $id]);

  // Actualizar tb_envio según corresponda
  $estado_envio = match($estado) {
    'pendiente' => 'pendiente',
    'procesando' => 'enviado',
    'enviado', 'entregado' => 'entregado',
    default => 'pendiente'
  };

  $stmt2 = $pdo->prepare("UPDATE tb_envio SET estado_envio = ? WHERE id_pedido = ?");
  $stmt2->execute([$estado_envio, $id]);

  echo json_encode(['success' => true, 'estado_envio' => $estado_envio]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
