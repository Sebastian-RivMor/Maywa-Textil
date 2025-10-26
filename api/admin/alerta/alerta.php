<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo json_encode(['success' => false, 'error' => "[$errno] $errstr en $errfile:$errline"]);
    exit;
});

require_once __DIR__ . '/../../../config/db.php';

$response = ['success' => false];

try {
    if (!isset($pdo)) {
        throw new Exception("Error de conexión con la base de datos.");
    }

    // Consulta SQL para obtener productos con stock bajo
    $sql = "SELECT id_producto, nombre_producto, stock FROM tb_producto WHERE stock <= 2 ORDER BY stock ASC";
    
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($productos) > 0) {
        $response['success'] = true;
        $response['product_name'] = $productos[0]['nombre_producto'];  // El primer producto con bajo stock
        $response['stock'] = $productos[0]['stock'];  // Stock del primer producto
    } else {
        $response['success'] = true;
        $response['message'] = "Todos los productos tienen suficiente stock.";
    }

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
