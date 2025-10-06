<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../../config/db.php";

try {
    $stmt = $pdo->query("SELECT id_categoria, nombre_categoria FROM tb_categoria ORDER BY nombre_categoria ASC");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($categorias);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
