<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../../config/db.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['nombre_comunidad']) || empty($data['descripcion'])) {
    echo json_encode(["error" => "Datos incompletos"]);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO tb_comunidad (nombre_comunidad, descripcion) VALUES (?, ?)");
    $stmt->execute([$data['nombre_comunidad'], $data['descripcion']]);
    echo json_encode(["success" => true, "message" => "Comunidad registrada correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
