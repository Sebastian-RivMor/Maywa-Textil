<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== "Admin") {
    header("Location: index.php");
    exit;
}

$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Administrador';
$currentPage   = 'dashboard'; // para marcar el menú
$pageTitle     = 'Bienvenido';
$pageSubtitle  = 'Administra tus productos, pedidos y comunidades de Maywa Textil.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo | Maywa Textil</title>
    <meta name="author" content="Maywa Textil">
    <meta name="description" content="Panel administrativo de Maywa Textil">

    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome (ICONOS) -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <!-- ChartJS (v3+) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

        :root {
            --maywa-dark: #1b0033;
            --maywa-primary: #7b2cbf;
            --maywa-accent: #f72585;
            --maywa-soft: #d06bff;
        }

        .font-family-karla { font-family: 'Karla', sans-serif; }

        .bg-sidebar {
            background: linear-gradient(to bottom, var(--maywa-dark), var(--maywa-primary));
        }

        .bg-header {
            background: linear-gradient(to right, var(--maywa-dark), var(--maywa-primary));
        }

        .btn-maywa {
            background: linear-gradient(to right, var(--maywa-primary), var(--maywa-accent));
        }

        .btn-maywa:hover {
            filter: brightness(1.05);
        }

        .active-nav-link {
            background: rgba(255, 255, 255, 0.12);
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, 0.18);
        }

        .account-link:hover {
            background: var(--maywa-primary);
            color: #fff;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-900 via-purple-700 to-pink-600 font-family-karla flex">

    <?php include __DIR__ . '/sidebar.php'; ?>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="w-full flex flex-col h-screen overflow-y-hidden">
        <?php include __DIR__ . '/header_admin.php'; ?>

        <!-- CONTENIDO -->
        <div class="w-full overflow-x-hidden border-t border-purple-200/40 flex flex-col">
            <main class="flex-1 p-6 md:p-8 overflow-auto">
                <h1 class="text-3xl font-bold text-white mb-2 drop-shadow-sm">Panel de control</h1>
                <p class="text-purple-100 mb-6 text-sm">
                    Resumen general de ventas, pedidos y clientes frecuentes.
                </p>

                <!-- TARJETAS DE RESUMEN -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-white backdrop-blur-sm p-4 rounded-2xl shadow-lg flex items-center">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <i class="fa-solid fa-coins text-purple-700"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Ventas del mes</p>
                            <p class="text-xl font-bold text-gray-800" id="resumenVentas">S/ 0.00</p>
                        </div>
                    </div>
                    <div class="bg-white backdrop-blur-sm p-4 rounded-2xl shadow-lg flex items-center">
                        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                            <i class="fa-solid fa-cart-shopping text-pink-600"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Pedidos</p>
                            <p class="text-xl font-bold text-gray-800" id="resumenPedidos">0</p>
                        </div>
                    </div>
                    <div class="bg-white backdrop-blur-sm p-4 rounded-2xl shadow-lg flex items-center">
                        <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                            <i class="fa-solid fa-users text-purple-700"></i>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-500 uppercase">Clientes frecuentes</p>
                            <p class="text-xl font-bold text-gray-800" id="resumenClientes">0</p>
                        </div>
                    </div>
                </div>

                <!-- GRAFICOS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="bg-white backdrop-blur-sm p-4 rounded-2xl shadow-lg">
                        <h2 class="text-lg font-semibold mb-2 text-gray-800">Ventas por mes</h2>
                        <div class="h-64">
                            <canvas id="graficoVentas" class="w-full h-full"></canvas>
                        </div>
                    </div>
                    <div class="bg-white backdrop-blur-sm p-4 rounded-2xl shadow-lg">
                        <h2 class="text-lg font-semibold mb-2 text-gray-800">Pedidos por departamento</h2>
                        <div class="h-64">
                            <canvas id="graficoDepartamentos" class="w-full h-full"></canvas>
                        </div>
                    </div>
                </div>

                <!-- LISTAS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="bg-white backdrop-blur-sm p-4 rounded-2xl shadow-lg">
                        <h2 class="text-lg font-semibold mb-3 text-gray-800">Productos más vendidos</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-purple-50 text-purple-700">
                            <tr>
                                <th class="p-2 text-left rounded-l-lg">Producto</th>
                                <th class="p-2 text-center">Vendidos</th>
                                <th class="p-2 text-center rounded-r-lg">%</th>
                            </tr>
                            </thead>
                            <tbody id="tablaProductos"></tbody>
                        </table>
                    </div>
                    <div class="bg-white backdrop-blur-sm p-4 rounded-2xl shadow-lg">
                        <h2 class="text-lg font-semibold mb-3 text-gray-800">Clientes frecuentes</h2>
                        <table class="w-full text-sm">
                            <thead class="bg-purple-50 text-purple-700">
                            <tr>
                                <th class="p-2 text-left rounded-l-lg">Cliente</th>
                                <th class="p-2 text-center rounded-r-lg">Pedidos</th>
                            </tr>
                            </thead>
                            <tbody id="tablaClientes"></tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- MODAL ALERTA STOCK -->
    <div id="stockModal"
         class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-900 bg-opacity-60">
        <div class="bg-white p-8 rounded-2xl shadow-2xl w-11/12 md:w-1/3">
            <h2 class="text-xl font-bold mb-3 text-purple-800 flex items-center space-x-2">
                <i class="fa-solid fa-triangle-exclamation text-yellow-500"></i>
                <span>¡Alerta de stock!</span>
            </h2>
            <p id="stockMessage" class="text-gray-700 mb-6">
                Hay poco stock en el producto: <span id="productName" class="font-semibold"></span>.
            </p>
            <div class="flex justify-end space-x-3">
                <button id="closeModal"
                        class="px-4 py-2 rounded-xl border text-gray-600 hover:bg-gray-100 text-sm">
                    Cerrar
                </button>
                <a href="producto.php"
                   class="px-4 py-2 rounded-xl btn-maywa text-white text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Ir a productos</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        async function cargarDashboard() {
            try {
                // --- Ventas por mes ---
                const vRes = await fetch('../api/admin/dashboard/ventas_mes.php');
                const ventas = await vRes.json();

                if (ventas.length) {
                    const totalMes = ventas.reduce((acc, v) => acc + parseFloat(v.total_ventas), 0);
                    document.getElementById('resumenVentas').innerText = 'S/ ' + totalMes.toFixed(2);
                    document.getElementById('resumenPedidos').innerText =
                        ventas.reduce((acc, v) => acc + (parseInt(v.total_pedidos || 0)), 0);
                }

                new Chart(document.getElementById('graficoVentas'), {
                    type: 'bar',
                    data: {
                        labels: ventas.map(v => v.mes),
                        datasets: [{
                            label: 'Total S/',
                            data: ventas.map(v => v.total_ventas),
                            backgroundColor: '#7B2CBF'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });

                // --- Pedidos por departamento ---
                const dRes = await fetch('../api/admin/dashboard/pedidos_departamento.php');
                const dep = await dRes.json();

                new Chart(document.getElementById('graficoDepartamentos'), {
                    type: 'pie',
                    data: {
                        labels: dep.map(d => d.departamento),
                        datasets: [{
                            data: dep.map(d => d.total_pedidos),
                            backgroundColor: ['#9D4EDD','#7B2CBF','#5A189A','#3C096C','#F72585','#FF9E00'],
                            radius: '100%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: { boxWidth: 12 }
                            }
                        }
                    }
                });

                // --- Productos top ---
                const pRes = await fetch('../api/admin/dashboard/productos_top.php');
                const productos = await pRes.json();
                document.getElementById('tablaProductos').innerHTML = productos.map(p => `
                    <tr class="border-b last:border-b-0 hover:bg-purple-50/60">
                        <td class="p-2 text-gray-800">${p.nombre_producto}</td>
                        <td class="p-2 text-center text-gray-700">${p.total_vendidos}</td>
                        <td class="p-2 text-center text-gray-700">${p.porcentaje}%</td>
                    </tr>
                `).join('');

                // --- Clientes top ---
                const cRes = await fetch('../api/admin/dashboard/clientes_top.php');
                const clientes = await cRes.json();
                document.getElementById('tablaClientes').innerHTML = clientes.map(c => `
                    <tr class="border-b last:border-b-0 hover:bg-purple-50/60">
                        <td class="p-2 text-gray-800">${c.cliente}</td>
                        <td class="p-2 text-center text-gray-700">${c.total_pedidos}</td>
                    </tr>
                `).join('');

                document.getElementById('resumenClientes').innerText = clientes.length;
            } catch (e) {
                console.error('Error cargando dashboard:', e);
            }
        }

        async function verificarStockAlLoguearse() {
            try {
                const res = await fetch('../api/admin/alerta/alerta.php');
                const data = await res.json();

                if (data.success && data.stock <= 2) {
                    document.getElementById('productName').innerText = data.product_name;
                    document.getElementById('stockMessage').innerText =
                        `Hay poco stock en el producto: ${data.product_name}. Complete el stock cuanto antes.`;
                    document.getElementById('stockModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error("Error al verificar el stock:", error);
            }
        }

        document.getElementById('closeModal').addEventListener('click', function () {
            document.getElementById('stockModal').classList.add('hidden');
        });

        window.addEventListener('DOMContentLoaded', () => {
            cargarDashboard();
            verificarStockAlLoguearse();
        });
</script>

</body>
</html>
