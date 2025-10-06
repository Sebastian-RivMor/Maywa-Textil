<?php
header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Captura de errores como JSON
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    echo json_encode(['success' => false, 'error' => "[$errno] $errstr en $errfile:$errline"]);
    exit;
});

require_once __DIR__ . "/../../../config/db.php"; // ajusta la ruta si tu config está en otra carpeta

$response = ['success' => false];

try {
    // Validación de conexión PDO
    if (!isset($pdo)) {
        throw new Exception("Error de conexión a la base de datos (PDO no definido).");
    }

    // Recibir datos del formulario
    $id_comunidad = $_POST['id_comunidad'] ?? null;
    $id_categoria = $_POST['id_categoria'] ?? null;
    $id_material  = $_POST['id_material'] ?? null;
    $nombre_producto = $_POST['nombre'] ?? null;
    $descripcion_corta = $_POST['descripcion_previa'] ?? '';
    $descripcion_producto = $_POST['descripcion_completa'] ?? '';
    $precio = $_POST['precio'] ?? null;
    $stock  = $_POST['stock'] ?? null;
    $estado_producto = 'Disponible';

    // Validar campos requeridos
    if (!$id_comunidad || !$id_categoria || !$nombre_producto || !$precio || !$stock) {
        throw new Exception("Faltan campos obligatorios.");
    }

    // Subir imagen si existe
    $foto_url = '';
    if (!empty($_FILES['imagenes']['name'][0])) {
        $uploadDir = __DIR__ . '/../../../../uploads/productos/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $tmpName = $_FILES['imagenes']['tmp_name'][0];
        $fileName = time() . '_' . basename($_FILES['imagenes']['name'][0]);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $targetFile)) {
            $foto_url = '/MAYWATEXTIL/uploads/productos/' . $fileName;
        } else {
            throw new Exception("Error al subir la imagen.");
        }
    }

    // Consulta SQL con PDO
    $sql = "INSERT INTO tb_producto 
        (id_comunidad, id_categoria, id_material, nombre_producto, descripcion_producto, descripcion_corta, precio, stock, foto_url, estado_producto)
        VALUES (:id_comunidad, :id_categoria, :id_material, :nombre_producto, :descripcion_producto, :descripcion_corta, :precio, :stock, :foto_url, :estado_producto)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id_comunidad' => $id_comunidad,
        ':id_categoria' => $id_categoria,
        ':id_material' => $id_material,
        ':nombre_producto' => $nombre_producto,
        ':descripcion_producto' => $descripcion_producto,
        ':descripcion_corta' => $descripcion_corta,
        ':precio' => $precio,
        ':stock' => $stock,
        ':foto_url' => $foto_url,
        ':estado_producto' => $estado_producto
    ]);

    $response['success'] = true;
    $response['id_insertado'] = $pdo->lastInsertId();

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
exit;
