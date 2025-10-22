<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== "Admin") {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tailwind Admin Template</title>
    <meta name="author" content="David Grzyb">
    <meta name="description" content="">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');
        .font-family-karla { font-family: karla; }
        .bg-sidebar { background: #3d68ff; }
        .cta-btn { color: #3d68ff; }
        .upgrade-btn { background: #1947ee; }
        .upgrade-btn:hover { background: #0038fd; }
        .active-nav-link { background: #1947ee; }
        .nav-item:hover { background: #1947ee; }
        .account-link:hover { background: #3d68ff; }
    </style>
</head>
<body class="bg-gray-100 font-family-karla flex">

    <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <div class="p-6">
            <a href="index.html" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
            <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-br-lg rounded-bl-lg rounded-tr-lg shadow-lg hover:shadow-xl hover:bg-gray-300 flex items-center justify-center">
                <i class="fas fa-plus mr-3"></i> New Report
            </button>
        </div>
        <nav class="text-white text-base font-semibold pt-3">
            <a href="index.html" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="/MAYWATEXTIL/admin/comunidad.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-table mr-3"></i>
                Comunidad
            </a>
            <a href="/MAYWATEXTIL/admin/producto.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sticky-note mr-3"></i>
                Añadir Producto
            </a>
            <a href="/MAYWATEXTIL/admin/pedidos.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sticky-note mr-3"></i>
                Pedidos
            </a>
            
            
        </nav>
    </aside>

    <div class="w-full flex flex-col h-screen overflow-y-hidden">
        <!-- Desktop Header -->
        <header class="w-full items-center bg-white py-2 px-6 hidden sm:flex">
            <div class="w-1/2"></div>
            <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
                <button @click="isOpen = !isOpen" class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
                    <img src="https://source.unsplash.com/uJ8LNVCBjFQ/400x400">
                </button>
                <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
                <div x-show="isOpen" class="absolute w-32 bg-white rounded-lg shadow-lg py-2 mt-16">
                    <a href="/MAYWATEXTIL/admin/logout.php" class="block px-4 py-2 account-link hover:text-white">Sign Out</a>
                </div>
            </div>
        </header>

    
        <div class="w-full overflow-x-hidden border-t flex flex-col">
            <main class="flex-1 p-8 overflow-auto">
                <h1 class="text-3xl font-bold mb-6">Panel de Control</h1>

                <!-- GRAFICOS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Ventas por Mes</h2>
                    <canvas id="graficoVentas"></canvas>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Pedidos por Departamento</h2>
                    <canvas id="graficoDepartamentos"></canvas>
                    </div>
                </div>

                <!-- LISTAS -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Productos más vendidos</h2>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Producto</th>
                            <th class="p-2 text-center">Vendidos</th>
                            <th class="p-2 text-center">%</th>
                        </tr>
                        </thead>
                        <tbody id="tablaProductos"></tbody>
                    </table>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow">
                    <h2 class="text-lg font-semibold mb-2">Clientes frecuentes</h2>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 text-left">Cliente</th>
                            <th class="p-2 text-center">Pedidos</th>
                        </tr>
                        </thead>
                        <tbody id="tablaClientes"></tbody>
                    </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>

    <script>
        async function cargarDashboard() {
        try {
            // --- Ventas por mes ---
            const vRes = await fetch('/MAYWATEXTIL/api/admin/dashboard/ventas_mes.php');
            const ventas = await vRes.json();
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
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
            });

            // --- Pedidos por departamento ---
            const dRes = await fetch('/MAYWATEXTIL/api/admin/dashboard/pedidos_departamento.php');
            const dep = await dRes.json();
            new Chart(document.getElementById('graficoDepartamentos'), {
            type: 'pie',
            data: {
                labels: dep.map(d => d.departamento),
                datasets: [{
                data: dep.map(d => d.total_pedidos),
                backgroundColor: ['#9D4EDD','#7B2CBF','#5A189A','#3C096C','#240046']
                }]
            },
            options: { responsive: true }
            });

            // --- Productos top ---
            const pRes = await fetch('/MAYWATEXTIL/api/admin/dashboard/productos_top.php');
            const productos = await pRes.json();
            document.getElementById('tablaProductos').innerHTML = productos.map(p => `
            <tr>
                <td class="p-2">${p.nombre_producto}</td>
                <td class="p-2 text-center">${p.total_vendidos}</td>
                <td class="p-2 text-center">${p.porcentaje}%</td>
            </tr>`).join('');

            // --- Clientes top ---
            const cRes = await fetch('/MAYWATEXTIL/api/admin/dashboard/clientes_top.php');
            const clientes = await cRes.json();
            document.getElementById('tablaClientes').innerHTML = clientes.map(c => `
            <tr>
                <td class="p-2">${c.cliente}</td>
                <td class="p-2 text-center">${c.total_pedidos}</td>
            </tr>`).join('');

        } catch (e) {
            console.error('Error cargando dashboard:', e);
        }
        }
        window.addEventListener('DOMContentLoaded', cargarDashboard);
    </script>
</body>
</html>