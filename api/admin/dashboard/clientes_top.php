<?php
require_once __DIR__ . '/../../../config/db.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $stmt = $pdo->query("
    SELECT 
      CONCAT(per.nombre, ' ', per.apellido) AS cliente,
      COUNT(p.id_pedido) AS total_pedidos
    FROM tb_pedido p
    INNER JOIN tb_usuario u ON p.id_usuario = u.id_usuario
    INNER JOIN tb_persona per ON u.id_persona = per.id_persona
    GROUP BY cliente
    ORDER BY total_pedidos DESC
    LIMIT 5
  ");
  echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}
