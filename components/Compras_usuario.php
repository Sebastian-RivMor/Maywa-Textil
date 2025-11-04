<?php
// Iniciar sesión si no está iniciada
session_start();

// Conexión a la base de datos
require_once __DIR__ . '/../config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'Debe iniciar sesión']);
    exit;
}

// Obtener el ID del usuario desde la sesión
$userId = $_SESSION['usuario']['id']; // Usamos el ID almacenado en la sesión

// Consulta para obtener los pedidos y sus detalles, agrupados por id_pedido
$query = "
    SELECT 
        p.id_pedido, p.total, p.estado_pedido, p.fecha_pedido, e.estado_envio, 
        dp.cantidad, dp.precio_unitario, pr.nombre_producto, pr.foto_url
    FROM tb_pedido p
    JOIN tb_detalle_pedido dp ON p.id_pedido = dp.id_pedido
    JOIN tb_producto pr ON dp.id_producto = pr.id_producto
    LEFT JOIN tb_envio e ON p.id_pedido = e.id_pedido
    WHERE p.id_usuario = :user_id
    ORDER BY p.fecha_pedido DESC
";

$stmt = $pdo->prepare($query);
$stmt->execute(['user_id' => $userId]); // Ejecutar consulta con el ID del usuario
$pedidos = $stmt->fetchAll();

// Agrupar productos por id_pedido
$pedidosAgrupados = [];
foreach ($pedidos as $pedido) {
    $pedidosAgrupados[$pedido['id_pedido']][] = $pedido;
}

// Mostrar los pedidos y sus detalles agrupados
?>

<div class="container mx-auto p-6">
    <h1 class="text-3xl text-white mb-6">Mis Compras</h1>

    <?php if (count($pedidosAgrupados) > 0): ?>
        <?php foreach ($pedidosAgrupados as $idPedido => $productos): ?>
            <div class="bg-[#181818] rounded-lg shadow-lg p-6 mb-6">
                <!-- Estructura horizontal -->
                <div class="flex justify-between items-center">
                    <!-- Encabezados con los datos -->
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 font-semibold">Pedido #<?= $idPedido ?></p>
                        <p class="text-sm text-gray-500">Fecha: <?= date('d-m-Y', strtotime($productos[0]['fecha_pedido'])) ?></p>
                    </div>

                    <div class="flex-1 text-center">
                        <p class="text-sm text-gray-500 font-semibold">Estado del Pedido</p>
                        <span class="text-sm text-[#9d4edd]"><?= ucfirst($productos[0]['estado_pedido']) ?></span>
                    </div>

                    <div class="flex-1 text-center">
                        <p class="text-sm text-gray-500 font-semibold">Total</p>
                        <span class="text-lg font-bold text-white">S/. <?= number_format($productos[0]['total'], 2) ?></span>
                    </div>

                    <!-- Botón Ver Productos -->
                    <div class="flex-1 text-center">
                        <button 
                            class="px-4 py-2 bg-[#9d4edd] hover:bg-[#b368ff] text-white rounded-md"
                            data-id="<?= $idPedido ?>"
                            onclick="openModal(<?= $idPedido ?>)"
                        >
                            Ver Productos
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal para los productos del pedido -->
            <div id="modal-<?= $idPedido ?>" class="modal hidden fixed inset-0 flex items-center justify-center backdrop-blur-lg">
                <div class="modal-content bg-white p-8 rounded-2xl max-w-2xl w-full shadow-2xl">
                    <h2 class="text-2xl font-semibold mb-4 text-[#9d4edd]">Productos del Pedido #<?= $idPedido ?></h2>
                    <div class="flex flex-wrap space-x-4">
                        <?php foreach ($productos as $producto): ?>
                            <div class="w-1/3 p-4 bg-[#f7f7f7] rounded-lg shadow-lg">
                                <img src="<?= $producto['foto_url'] ?>" alt="<?= $producto['nombre_producto'] ?>" class="w-full h-auto rounded-lg mb-4">
                                <p class="text-center text-lg font-semibold"><?= $producto['nombre_producto'] ?></p>
                                <p class="text-center text-sm text-gray-500">Cantidad: <?= $producto['cantidad'] ?></p>
                                <p class="text-center text-lg font-bold text-[#9d4edd]">S/. <?= number_format($producto['precio_unitario'], 2) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="mt-4 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition" onclick="closeModal(<?= $idPedido ?>)">Cerrar</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-white">No has realizado compras aún.</p>
    <?php endif; ?>
</div>

<script>
    // Función para abrir el modal
    function openModal(id) {
        document.getElementById('modal-' + id).classList.remove('hidden');
    }

    // Función para cerrar el modal
    function closeModal(id) {
        document.getElementById('modal-' + id).classList.add('hidden');
    }
</script>

<style>
    /* Efecto de desenfoque (blur) para el fondo cuando el modal está abierto */
    .backdrop-blur-lg {
        backdrop-filter: blur(15px);
    }
</style>
