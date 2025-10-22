<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== "Admin") {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Añadir Comunidad</title>
  <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
  <style>
    .bg-sidebar { background: #3d68ff; }
    .cta-btn { color: #3d68ff; }
    .active-nav-link { background: #1947ee; }
    .nav-item:hover { background: #1947ee; }
  </style>
</head>

<body class="bg-gray-100 font-sans flex" x-data="{ openModal: false }">
  <!-- Sidebar -->
  <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
    <div class="p-6">
      <a href="#" class="text-white text-3xl font-semibold uppercase hover:text-gray-300">Admin</a>
      <button class="w-full bg-white cta-btn font-semibold py-2 mt-5 rounded-lg shadow-md hover:bg-gray-300 flex items-center justify-center">
        <i class="fas fa-plus mr-3"></i> New Report
      </button>
    </div>
    <nav class="text-white text-base font-semibold pt-3">
      <a href="dashboard.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
      </a>
      <a href="/MAYWATEXTIL/admin/comunidad.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-table mr-3"></i>
                Comunidad
            </a>
      <a href="producto.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
        <i class="fas fa-sticky-note mr-3"></i> Añadir Producto
      </a>
        <a href="/MAYWATEXTIL/admin/pedidos.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sticky-note mr-3"></i>
                Pedidos
        </a>
    </nav>
  </aside>

    <!-- Main content -->
    <main class="w-full flex flex-col h-screen overflow-y-auto p-6"
        x-data="{ openModal: false, comunidades: [], nombre: '', descripcion: '' }"
        x-init="
            // Cargar comunidades al iniciar
            fetch('/MAYWATEXTIL/api/admin/comunidad/get_comunidades.php')
            .then(res => res.json())
            .then(data => comunidades = data);
        ">

    <!-- Botón añadir -->
    <div class="flex justify-end mb-6">
        <button @click='openModal = true'
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md">
        + Añadir Comunidad
        </button>
    </div>

    <!-- Modal -->
    <div x-show="openModal"
        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
        x-transition>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative"
            @click.away="openModal = false">
        
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-lg font-bold text-gray-700">Nueva Comunidad</h2>
            <button @click="openModal = false" class="text-gray-500 hover:text-gray-700 text-lg">&times;</button>
        </div>

        <!-- Formulario dinámico -->
        <form @submit.prevent="
            fetch('/MAYWATEXTIL/api/admin/comunidad/add_comunidad.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre_comunidad: nombre, descripcion: descripcion })
            })
            .then(res => res.json())
            .then(data => {
            if (data.success) {
                // limpiar inputs
                nombre = ''; descripcion = '';
                openModal = false;
                // recargar tabla
                fetch('/MAYWATEXTIL/api/admin/comunidad/get_comunidades.php')
                .then(res => res.json())
                .then(data => comunidades = data);
            } else {
                alert(data.error || 'Error al registrar comunidad');
            }
            });
        " class="space-y-4">

            <!-- ID autoincremental -->
            <div>
            <label class="block text-sm font-semibold text-gray-600">ID Comunidad</label>
            <input type="text" value="Auto" readonly
                    class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500">
            </div>

            <!-- Nombre -->
            <div>
            <label class="block text-sm font-semibold text-gray-600">Nombre de la comunidad</label>
            <input type="text" x-model="nombre"
                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
            </div>

            <!-- Descripción -->
            <div>
            <label class="block text-sm font-semibold text-gray-600">Descripción</label>
            <textarea x-model="descripcion" rows="3"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 resize-none" required></textarea>
            </div>

            <!-- Botón -->
            <div class="flex justify-end">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md">
                Añadir Comunidad
            </button>
            </div>
        </form>
        </div>
    </div>

    <!-- Tabla de comunidades -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden mt-4">
    <table class="min-w-full text-left">
        <thead class="bg-gray-200 text-gray-700 text-sm uppercase">
        <tr>
            <th class="px-6 py-3">ID Comunidad</th>
            <th class="px-6 py-3">Nombre</th>
            <th class="px-6 py-3">Descripción</th>
            <th class="px-6 py-3 text-center">Acciones</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 text-gray-700">
        <!-- Render dinámico con Alpine -->
        <template x-for="com in comunidades" :key="com.id_comunidad">
            <tr class="hover:bg-gray-50">
            <td class="px-6 py-3" x-text="com.id_comunidad"></td>
            <td class="px-6 py-3" x-text="com.nombre_comunidad"></td>
            <td class="px-6 py-3" x-text="com.descripcion"></td>
            <td class="px-6 py-3 text-center">
                <button
                @click="
                    if (confirm('¿Seguro que deseas eliminar esta comunidad?')) {
                    fetch('/MAYWATEXTIL/api/admin/comunidad/delete_comunidad.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id_comunidad: com.id_comunidad })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                        comunidades = comunidades.filter(c => c.id_comunidad != com.id_comunidad);
                        } else {
                        alert(data.error || 'Error al eliminar');
                        }
                    });
                    }
                "
                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm font-semibold shadow">
                Eliminar
                </button>
            </td>
            </tr>
        </template>
        </tbody>
    </table>
    </div>
    </main>

  <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>

    <script>
        var chartOne = document.getElementById('chartOne');
        var myChart = new Chart(chartOne, {
            type: 'bar',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        var chartTwo = document.getElementById('chartTwo');
        var myLineChart = new Chart(chartTwo, {
            type: 'line',
            data: {
                labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
