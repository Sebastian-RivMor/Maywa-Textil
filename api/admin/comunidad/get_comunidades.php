<?php
header("Content-Type: application/json");
require_once __DIR__ . "/../../../config/db.php";

try {
    $stmt = $pdo->query("SELECT id_comunidad, nombre_comunidad, descripcion FROM tb_comunidad ORDER BY id_comunidad DESC");
    $comunidades = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($comunidades);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
