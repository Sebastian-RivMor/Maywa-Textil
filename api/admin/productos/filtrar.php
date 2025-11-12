<?php
// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Incluir la conexión a la base de datos (ya está configurada en pdo.php)
require_once '../../../config/db.php'; 

// Ya tenemos la conexión en $pdo, no necesitamos crear una nueva instancia

// Recuperar los parámetros de los filtros
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';
$material = isset($_GET['material']) ? $_GET['material'] : '';
$precio = isset($_GET['precio']) ? $_GET['precio'] : '';
$buscar = isset($_GET['buscar']) ? $_GET['buscar'] : ''; // Añadido parámetro de búsqueda

// Consultar las categorías disponibles
$query_categoria = "SELECT * FROM tb_categoria";
$stmt_categoria = $pdo->prepare($query_categoria);
$stmt_categoria->execute();
$categorias = $stmt_categoria->fetchAll(PDO::FETCH_ASSOC);

// Consultar los materiales disponibles
$query_material = "SELECT * FROM tb_material";
$stmt_material = $pdo->prepare($query_material);
$stmt_material->execute();
$materiales = $stmt_material->fetchAll(PDO::FETCH_ASSOC);

// Consultar los productos filtrados
$query_producto = "SELECT * 
                   FROM tb_producto p
                   LEFT JOIN tb_categoria c ON p.id_categoria = c.id_categoria
                   LEFT JOIN tb_material m ON p.id_material = m.id_material
                   WHERE 1"; // Condición base

// Agregar filtros
if ($categoria) {
    $query_producto .= " AND p.id_categoria = :categoria";
}

if ($material) {
    $query_producto .= " AND p.id_material = :material";
}

if ($precio) {
    $precio_rangos = explode("-", $precio);  // Dividir por guion para obtener el rango
    $query_producto .= " AND p.precio BETWEEN :precio_min AND :precio_max";
}

// Agregar filtro de búsqueda por nombre del producto
if ($buscar) {
    $query_producto .= " AND p.nombre_producto LIKE :buscar";
}

// Preparar la consulta
$stmt_producto = $pdo->prepare($query_producto);

// Vincular parámetros
if ($categoria) {
    $stmt_producto->bindParam(':categoria', $categoria);
}

if ($material) {
    $stmt_producto->bindParam(':material', $material);
}

if ($precio) {
    $stmt_producto->bindParam(':precio_min', $precio_rangos[0]);
    $stmt_producto->bindParam(':precio_max', $precio_rangos[1]);
}

// Vincular el filtro de búsqueda
if ($buscar) {
    $buscarTerm = '%' . $buscar . '%'; // Agregar los comodines para LIKE
    $stmt_producto->bindParam(':buscar', $buscarTerm);
}

// Ejecutar la consulta
$stmt_producto->execute();
$productos = $stmt_producto->fetchAll(PDO::FETCH_ASSOC);

// Crear la respuesta con todos los datos
$response = [
    'categorias' => $categorias,
    'materiales' => $materiales,
    'productos' => $productos
];

// Devolver los datos en formato JSON
echo json_encode($response);
?>
