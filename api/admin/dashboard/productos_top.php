<?php
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $stmt = $pdo->query("
    SELECT 
      pr.nombre_producto,
      SUM(dp.cantidad) AS total_vendidos,
      ROUND(SUM(dp.cantidad) * 100 / (SELECT SUM(cantidad) FROM tb_detalle_pedido), 2) AS porcentaje
    FROM tb_detalle_pedido dp
    INNER JOIN tb_producto pr ON dp.id_producto = pr.id_producto
    GROUP BY pr.id_producto
    ORDER BY total_vendidos DESC
    LIMIT 5
  ");
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
