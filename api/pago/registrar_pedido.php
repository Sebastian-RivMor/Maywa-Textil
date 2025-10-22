<?php
header("Content-Type: application/json; charset=utf-8");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
session_start();

require_once __DIR__ . '/../../config/db.php';

try {
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
        echo json_encode(["ok" => false, "msg" => "Sesión no iniciada"]);
        exit;
    }

    $id_usuario = $_SESSION['usuario']['id'];
    $data = json_decode(file_get_contents("php://input"), true);

    $total = $data["total"] ?? 0;
    $metodo = $data["metodo_pago"] ?? "";
    $direccion = $data["direccion"] ?? "";
    $id_departamento = $data["id_departamento"] ?? null;
    $productos = $data["productos"] ?? [];

    if ($total <= 0 || empty($metodo)) {
        echo json_encode(["ok" => false, "msg" => "Datos incompletos"]);
        exit;
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pedido
    $sql = "INSERT INTO tb_pedido (id_usuario, total, direccion_entrega, id_departamento_envio)
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $total, $direccion, $id_departamento]);
    $id_pedido = $pdo->lastInsertId();

    // Detalle
    $sql_det = "INSERT INTO tb_detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
                VALUES (?, ?, ?, ?)";
    $stmt_det = $pdo->prepare($sql_det);
    foreach ($productos as $prod) {
        $stmt_det->execute([
            $id_pedido,
            $prod['id_producto'] ?? 0,
            $prod['cantidad'] ?? 1,
            $prod['precio'] ?? 0
        ]);
    }

    // Pago
    $sql_pago = "INSERT INTO tb_pago (id_pedido, monto_pagado, metodo_pago, estado_pago)
                 VALUES (?, ?, ?, 'completado')";
    $stmt_pago = $pdo->prepare($sql_pago);
    $stmt_pago->execute([$id_pedido, $total, $metodo]);

    // Envío
    $sql_envio = "INSERT INTO tb_envio (id_pedido, direccion_envio, estado_envio)
                  VALUES (?, ?, 'pendiente')";
    $stmt_envio = $pdo->prepare($sql_envio);
    $stmt_envio->execute([$id_pedido, $direccion]);

    echo json_encode(["ok" => true, "msg" => "Pedido registrado correctamente", "id_pedido" => $id_pedido]);

} catch (Throwable $e) {
    error_log("Error en registrar_pedido.php: " . $e->getMessage());
    echo json_encode(["ok" => false, "msg" => "Error al registrar pedido: " . $e->getMessage()]);
}
