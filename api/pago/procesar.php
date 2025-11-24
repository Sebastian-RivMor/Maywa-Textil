<?php
header("Content-Type: application/json; charset=utf-8");
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error_log.txt');
session_start();

require_once __DIR__ . '/../../config/db.php';

try {
    if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
        echo json_encode(["success" => false, "error" => "Sesión no iniciada"]);
        exit;
    }

    $id_usuario = $_SESSION['usuario']['id'];
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode(["success" => false, "error" => "No se recibieron datos"]);
        exit;
    }

    $metodo = $data["metodo_pago"] ?? "";
    $total = $data["total"] ?? 0;
    $productos = $data["productos"] ?? [];
    $direccion = $data["direccion"] ?? "";
    $id_departamento = $data["id_departamento"] ?? null;

    if ($total <= 0 || empty($metodo) || empty($productos)) {
        echo json_encode(["success" => false, "error" => "Datos incompletos"]);
        exit;
    }

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insertar en tb_pedido
    $sql = "INSERT INTO tb_pedido (id_usuario, total, direccion_entrega, id_departamento_envio)
            VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_usuario, $total, $direccion, $id_departamento]);
    $id_pedido = $pdo->lastInsertId();

    // Insertar detalle del pedido
    $sql_det = "INSERT INTO tb_detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario)
                VALUES (?, ?, ?, ?)";
    $stmt_det = $pdo->prepare($sql_det);

    error_log("DEBUG PRODUCTOS: " . json_encode($productos, JSON_PRETTY_PRINT));


    foreach ($productos as $p) {
        $id_producto = isset($p['id_producto']) ? (int)$p['id_producto'] : null;
        $cantidad = isset($p['cantidad']) ? (int)$p['cantidad'] : 1;
        $precio = isset($p['precio']) ? (float)$p['precio'] : 0;

        if ($id_producto === null || $id_producto <= 0) {
            throw new Exception("Producto inválido en el detalle del pedido.");
        }

        $stmt_det->execute([$id_pedido, $id_producto, $cantidad, $precio]);

    }

    // Insertar registro de pago
    $sql_pago = "INSERT INTO tb_pago (id_pedido, monto_pagado, metodo_pago, estado_pago)
                 VALUES (?, ?, ?, 'completado')";
    $stmt_pago = $pdo->prepare($sql_pago);
    $stmt_pago->execute([$id_pedido, $total, $metodo]);

    // Insertar registro de envío
    $sql_envio = "INSERT INTO tb_envio (id_pedido, direccion_envio, estado_envio)
                  VALUES (?, ?, 'pendiente')";
    $stmt_envio = $pdo->prepare($sql_envio);
    $stmt_envio->execute([$id_pedido, $direccion]);

    echo json_encode([
        "success" => true,
        "msg" => "✅ Pago procesado y pedido registrado correctamente",
        "id_pedido" => $id_pedido
    ]);

} catch (Throwable $e) {
    error_log("Error en procesar.php: " . $e->getMessage());
    echo json_encode(["success" => false, "error" => "Error interno: " . $e->getMessage()]);
}
