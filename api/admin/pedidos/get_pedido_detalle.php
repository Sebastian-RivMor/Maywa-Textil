<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // Evitar cacheo

require_once __DIR__ . '/../../../config/db.php';

// Validación del ID
$id = (int)($_GET['id_pedido'] ?? 0);
if ($id <= 0) {
  http_response_code(400);
  echo json_encode(['error' => 'ID de pedido inválido']);
  exit;
}

try {
  // Obtener el pedido y detalles principales
  $stmt = $pdo->prepare("
    SELECT 
      p.id_pedido,
      p.fecha_pedido,
      p.estado_pedido,
      p.total,
      p.direccion_entrega,
      d.nombre_departamento AS departamento_envio,
      e.estado_envio,
      e.fecha_envio,
      per.nombre AS cliente,
      per.apellido
    FROM tb_pedido p
    INNER JOIN tb_usuario u ON p.id_usuario = u.id_usuario
    INNER JOIN tb_persona per ON u.id_persona = per.id_persona
    INNER JOIN tb_departamento d ON p.id_departamento_envio = d.id_departamento
    LEFT JOIN tb_envio e ON e.id_pedido = p.id_pedido
    WHERE p.id_pedido = ?
  ");
  $stmt->execute([$id]);
  $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

  // Verificar si el pedido fue encontrado
  if (!$pedido) {
    http_response_code(404);
    echo json_encode(['error' => 'Pedido no encontrado.']);
    exit;
  }

  // Detalles de productos del pedido
  $stmt2 = $pdo->prepare("
    SELECT 
      pr.nombre_producto,
      dp.cantidad,
      dp.precio_unitario
    FROM tb_detalle_pedido dp
    INNER JOIN tb_producto pr ON dp.id_producto = pr.id_producto
    WHERE dp.id_pedido = ?
  ");
  $stmt2->execute([$id]);
  $productos = $stmt2->fetchAll(PDO::FETCH_ASSOC);

  // Si no se encuentran productos, se agrega un mensaje indicando esto
  if (empty($productos)) {
    $pedido['productos'] = [];
    $pedido['mensaje_productos'] = 'No se encontraron productos para este pedido.';
  } else {
    $pedido['productos'] = $productos;
  }

  // Respuesta con los detalles completos del pedido
  echo json_encode($pedido);

} catch (Throwable $e) {
  // Manejo de excepciones, asegurándose de que el error sea claro
  http_response_code(500);
  echo json_encode(['error' => 'Error interno del servidor: ' . $e->getMessage()]);
}
?>
