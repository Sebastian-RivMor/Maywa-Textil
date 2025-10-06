<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../../config/db.php";

try {
    $stmt = $pdo->query("SELECT id_material, nombre_material FROM tb_material ORDER BY nombre_material ASC");
    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($materiales);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
