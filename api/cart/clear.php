<?php
header("Content-Type: application/json");
session_start();
require_once __DIR__ . '/../../config/db.php';

// 1. Limpia el carrito persistente en la base de datos si el usuario está logueado
// El código original salía si el usuario no estaba logueado. Ahora solo verificamos si existe.
if (isset($_SESSION['usuario']['id_usuario'])) {
    $id_usuario = $_SESSION['usuario']['id_usuario'];
    
    // Asume que $conn es la conexión PDO proporcionada por db.php
    // Original: $conn->prepare("DELETE FROM carrito WHERE id_usuario = ?")->execute([$id_usuario]);
    if (isset($conn)) {
      $conn->prepare("DELETE FROM carrito WHERE id_usuario = ?")->execute([$id_usuario]);
    }
}

// 2. Limpia el carrito de la sesión de PHP (CRÍTICO para que la UI se actualice)
unset($_SESSION['cart']); // <--- Esta línea es la corrección.

echo json_encode(["success" => true]);