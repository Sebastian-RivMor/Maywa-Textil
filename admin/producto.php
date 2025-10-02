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

    <!-- AlpineJS -->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>


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
            <a href="/MAYWATEXTIL/admin/dashboard.php" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="/MAYWATEXTIL/admin/producto.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sticky-note mr-3"></i>
                Añadir Producto
            </a>
            <a href="tables.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-table mr-3"></i>
                Tables
            </a>
            <a href="forms.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-align-left mr-3"></i>
                Forms
            </a>
            <a href="tabs.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-tablet-alt mr-3"></i>
                Tabbed Content
            </a>
            <a href="calendar.html" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-calendar mr-3"></i>
                Calendar
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
        <!-- Contenido principal -->
        <div class="w-full p-6 bg-gray-100 h-full overflow-y-auto" x-data="{ openModal: false }">

            <!-- Botón Agregar Producto -->
            <div class="flex justify-end mb-6">
                <button @click="openModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-lg">
                + Agregar Producto
                </button>
            </div>

            <!-- Modal -->
            <div x-show="openModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 relative overflow-y-auto max-h-[90vh]"
                @click.away="openModal = false" x-transition>
                
                <!-- Header -->
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-lg font-bold text-gray-700">Añadir Producto</h2>
                <button @click="openModal = false" class="text-gray-500 hover:text-gray-700 text-lg">&times;</button>
                </div>

                <!-- Formulario (distribuido en 2 columnas) -->
                <form action="/MAYWATEXTIL/admin/guardar_producto.php" method="POST" enctype="multipart/form-data"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                <!-- ID (readonly) -->
                <div>
                    <label class="block font-semibold text-gray-600">ID</label>
                    <input type="text" value="Auto" readonly
                        class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500">
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block font-semibold text-gray-600">Nombre</label>
                    <input type="text" name="nombre"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
                </div>

                <!-- Descripción previa -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Descripción previa</label>
                    <textarea name="descripcion_previa" rows="2"
                            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 resize-none" required></textarea>
                </div>

                <!-- Descripción completa -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Descripción completa</label>
                    <textarea name="descripcion_completa" rows="3"
                            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200 resize-none" required></textarea>
                </div>

                <!-- Tipo de producto -->
                <div>
                    <label class="block font-semibold text-gray-600">Tipo</label>
                    <select name="tipo" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
                    <option value="">Seleccionar...</option>
                    <option value="chompas">Chompas</option>
                    <option value="ponchos">Ponchos</option>
                    <option value="bufandas">Bufandas</option>
                    <option value="casacas">Casacas</option>
                    </select>
                </div>

                <!-- Precio -->
                <div>
                    <label class="block font-semibold text-gray-600">Precio (S/.)</label>
                    <input type="number" name="precio" step="0.01"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
                </div>

                <!-- Material -->
                <div>
                    <label class="block font-semibold text-gray-600">Material</label>
                    <select name="material" class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
                    <option value="">Seleccionar...</option>
                    <option value="lana">Lana</option>
                    <option value="alpaca">Alpaca</option>
                    <option value="seda">Seda</option>
                    <option value="oveja">Oveja</option>
                    </select>
                </div>

                <!-- Stock -->
                <div>
                    <label class="block font-semibold text-gray-600">Stock</label>
                    <input type="number" name="stock" min="0"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
                </div>

                <!-- Imágenes -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Imágenes del producto</label>
                    <input type="file" name="imagenes[]" multiple accept="image/*"
                        class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200" required>
                    <p class="text-xs text-gray-500 mt-1">Mínimo 3 imágenes (1 principal + 2 adicionales)</p>
                </div>

                <!-- Botón añadir -->
                <div class="md:col-span-2 flex justify-end mt-2">
                    <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md">
                    Añadir Producto
                    </button>
                </div>
                </form>
            </div>
            </div>


            <!-- Tabla de productos -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <table class="w-full table-auto text-left border-collapse">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 text-sm uppercase">
                    <th class="px-4 py-3">ID Producto</th>
                    <th class="px-4 py-3">Nombre</th>
                    <th class="px-4 py-3">Categoría</th>
                    <th class="px-4 py-3">Precio</th>
                    <th class="px-4 py-3">Material</th>
                    <th class="px-4 py-3">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ejemplo de fila -->
                    <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-3">1</td>
                    <td class="px-4 py-3">Chalina Andina</td>
                    <td class="px-4 py-3">Bufandas</td>
                    <td class="px-4 py-3">S/. 189.00</td>
                    <td class="px-4 py-3">Alpaca</td>
                    <td class="px-4 py-3">50</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>