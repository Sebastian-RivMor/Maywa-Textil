<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../../../config/db.php';

$response = ['success' => false];

try {
  if (!isset($pdo)) throw new Exception("No se pudo conectar a la base de datos.");

  $id = $_POST['id_producto'] ?? null;
  $nombre = $_POST['nombre_producto'] ?? '';
  $desc_corta = $_POST['descripcion_corta'] ?? '';
  $desc_comp = $_POST['descripcion_producto'] ?? '';
  $precio = $_POST['precio'] ?? 0;
  $stock = $_POST['stock'] ?? 0;
  $estado = $_POST['estado_producto'] ?? 'Disponible';
  $foto_url = '';

  if (!$id) throw new Exception("ID de producto no válido.");

  // Manejar imagen (opcional)
  if (!empty($_FILES['imagen']['name'])) {
    $uploadDir = __DIR__ . '/../../../../uploads/productos/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = time() . '_' . basename($_FILES['imagen']['name']);
    $targetFile = $uploadDir . $fileName;
    if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFile)) {
      $foto_url = '/MAYWATEXTIL/uploads/productos/' . $fileName;
    }
  }

  $sql = "UPDATE tb_producto SET 
            nombre_producto = :nombre,
            descripcion_corta = :desc_corta,
            descripcion_producto = :desc_comp,
            precio = :precio,
            stock = :stock,
            estado_producto = :estado"
            . ($foto_url ? ", foto_url = :foto_url" : "") .
          " WHERE id_producto = :id";

  $stmt = $pdo->prepare($sql);
  $params = [
    ':nombre' => $nombre,
    ':desc_corta' => $desc_corta,
    ':desc_comp' => $desc_comp,
    ':precio' => $precio,
    ':stock' => $stock,
    ':estado' => $estado,
    ':id' => $id
  ];
  if ($foto_url) $params[':foto_url'] = $foto_url;

  $stmt->execute($params);
  $response['success'] = true;

} catch (Exception $e) {
  $response['error'] = $e->getMessage();
}

echo json_encode($response);
