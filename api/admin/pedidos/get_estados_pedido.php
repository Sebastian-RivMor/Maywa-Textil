<?php
session_start();
header("Content-Type: application/json; charset=utf-8");
require_once __DIR__ . '/../../../config/db.php';

// Auth estricta (solo Admin)
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== "Admin") {
    http_response_code(401);
    echo json_encode(['error' => 'Acceso no autorizado. Solo administradores pueden acceder a esta información.']);
    exit;
}

try {
    // Definimos los estados disponibles en el enum
    $estados = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'];

    // Devolver los estados
    echo json_encode(['estados' => $estados]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al obtener los estados']);
}
?>
