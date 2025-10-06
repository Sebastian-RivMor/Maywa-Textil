<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../../config/db.php";

// Recibir JSON del body
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['id_comunidad'])) {
    echo json_encode(["error" => "ID de comunidad no proporcionado"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tb_comunidad WHERE id_comunidad = ?");
    $stmt->execute([$data['id_comunidad']]);

    echo json_encode(["success" => true, "message" => "Comunidad eliminada correctamente"]);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
