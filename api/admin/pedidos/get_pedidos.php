<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store");
require_once __DIR__ . '/../../../config/db.php';

// Auth estricta (evita redirecciones HTML)
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] ?? '') !== 'Admin') {
  http_response_code(401);
  echo json_encode(['error' => 'Acceso no autorizado. Solo administradores pueden acceder a esta información.']);
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
    ORDER BY p.fecha_pedido DESC
  ";
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Verifica si hay resultados
  if (empty($rows)) {
    echo json_encode(['message' => 'No se encontraron pedidos en la base de datos.']);
  } else {
    echo json_encode($rows); // Devuelve los datos obtenidos
  }
} catch (Throwable $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al obtener los pedidos: ' . $e->getMessage()]);
}
?>
