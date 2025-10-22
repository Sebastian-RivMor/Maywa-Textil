<?php
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $stmt = $pdo->query("
    SELECT 
      d.nombre_departamento AS departamento,
      COUNT(p.id_pedido) AS total_pedidos
    FROM tb_pedido p
    INNER JOIN tb_departamento d ON p.id_departamento_envio = d.id_departamento
    GROUP BY departamento
    ORDER BY total_pedidos DESC
  ");
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
