<?php
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

// Obtener el ID del usuario desde la sesión
$userId = $_SESSION['user_id']; // Deberías tener el ID del usuario en la sesión

// Consulta para obtener los pedidos y sus detalles
$query = "
    SELECT 
        p.id_pedido, p.total, p.estado_pedido, p.fecha_pedido, e.estado_envio, 
        dp.cantidad, dp.precio_unitario, pr.nombre_producto, pr.foto_url
    FROM tb_pedido p
    JOIN tb_detalle_pedido dp ON p.id_pedido = dp.id_pedido
    JOIN tb_producto pr ON dp.id_producto = pr.id_producto
    LEFT JOIN tb_envio e ON p.id_pedido = e.id_pedido
    WHERE p.id_usuario = :user_id
    ORDER BY p.fecha_pedido DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $userId]);
$pedidos = $stmt->fetchAll();

// Si se encuentran pedidos, retornarlos en formato JSON
if (count($pedidos) > 0) {
    echo json_encode(['status' => 'success', 'data' => $pedidos]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'No se encontraron pedidos.']);
}
