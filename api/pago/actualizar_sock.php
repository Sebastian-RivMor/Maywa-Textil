<?php
header("Content-Type: application/json; charset=utf-8");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
session_start();

require_once __DIR__ . '/../../config/db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) {
        echo json_encode(["ok" => false, "msg" => "Datos inválidos"]);
        exit;
    }

    $productos = $data["productos"] ?? [];
    if (empty($productos)) {
        echo json_encode(["ok" => false, "msg" => "Sin productos a actualizar"]);
        exit;
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "UPDATE tb_producto SET stock = stock - ? WHERE id_producto = ?";
    $stmt = $pdo->prepare($sql);

    foreach ($productos as $prod) {
        $stmt->execute([$prod['cantidad'], $prod['id_producto']]);
    }

    echo json_encode(["ok" => true, "msg" => "Stock actualizado correctamente"]);

} catch (Throwable $e) {
    error_log("Error en actualizar_sock.php: " . $e->getMessage());
    echo json_encode(["ok" => false, "msg" => "Error al actualizar stock: " . $e->getMessage()]);
}
