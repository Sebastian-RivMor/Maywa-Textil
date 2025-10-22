<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store");
require_once __DIR__ . '/../../../config/db.php';

// Auth estricta (evita redirecciones HTML)
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] ?? '') !== 'Admin') {
  http_response_code(401);
  echo json_encode(['error' => 'No autorizado']);
  exit;
}

try {
  $sql = "
    SELECT 
        p.id_pedido,
        p.fecha_pedido,
        p.estado_pedido,
        per.nombre AS cliente,
        per.apellido,
        p.total
    FROM tb_pedido p
    INNER JOIN tb_usuario u ON p.id_usuario = u.id_usuario
    INNER JOIN tb_persona per ON u.id_persona = per.id_persona
    ORDER BY p.fecha_pedido DESC;
  ";
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo json_encode($rows); // o echo json_encode(['data'=>$rows]);
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al obtener pedidos']);
}
