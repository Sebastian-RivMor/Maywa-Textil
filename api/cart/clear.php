<?php
header("Content-Type: application/json");
session_start();
require_once __DIR__ . '/../../config/db.php';

if (!isset($_SESSION['usuario']['id_usuario'])) {
    echo json_encode(["success" => false]);
    exit;
}

$id_usuario = $_SESSION['usuario']['id_usuario'];
$conn->prepare("DELETE FROM carrito WHERE id_usuario = ?")->execute([$id_usuario]);

echo json_encode(["success" => true]);
