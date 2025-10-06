<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Captura de errores y devuelve JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo json_encode(['success' => false, 'error' => "[$errno] $errstr en $errfile:$errline"]);
    exit;
});

require_once __DIR__ . '/../../../config/db.php'; // Ajusta la ruta si tu config está en otro nivel

$response = [];

try {
    if (!isset($pdo)) {
        throw new Exception("Error de conexión con la base de datos (PDO no definido).");
    }

    // Consulta SQL completa con LEFT JOIN
    $sql = "SELECT 
                p.id_producto,
                p.nombre_producto,
                p.descripcion_corta,
                p.descripcion_producto,
                p.precio,
                p.stock,
                p.foto_url,
                p.estado_producto,
                c.nombre_comunidad,
                cat.nombre_categoria,
                m.nombre_material
            FROM tb_producto p
            LEFT JOIN tb_comunidad c ON p.id_comunidad = c.id_comunidad
            LEFT JOIN tb_categoria cat ON p.id_categoria = cat.id_categoria
            LEFT JOIN tb_material m ON p.id_material = m.id_material
            ORDER BY p.id_producto DESC";

    // Ejecutar consulta con PDO
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatear respuesta
    foreach ($productos as $row) {
        $response[] = [
            'id_producto'          => (int)$row['id_producto'],
            'nombre_producto'      => $row['nombre_producto'],
            'descripcion_corta'    => $row['descripcion_corta'],
            'descripcion_producto' => $row['descripcion_producto'],
            'precio'               => (float)$row['precio'],
            'stock'                => (int)$row['stock'],
            'foto_url'             => $row['foto_url'] ?: '',
            'estado_producto'      => $row['estado_producto'],
            'nombre_comunidad'     => $row['nombre_comunidad'] ?? '',
            'nombre_categoria'     => $row['nombre_categoria'] ?? '',
            'nombre_material'      => $row['nombre_material'] ?? ''
        ];
    }

    echo json_encode($response);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Error PDO: ' . $e->getMessage()]);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}
