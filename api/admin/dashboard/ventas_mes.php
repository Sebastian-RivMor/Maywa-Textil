<?php
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $stmt = $pdo->query("
    SELECT 
      DATE_FORMAT(fecha_pedido, '%Y-%m') AS mes,
      SUM(total) AS total_ventas,
      COUNT(*) AS total_pedidos
    FROM tb_pedido
    WHERE estado_pedido != 'cancelado'  -- Excluir pedidos cancelados
    GROUP BY mes
    ORDER BY mes ASC
  ");
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
