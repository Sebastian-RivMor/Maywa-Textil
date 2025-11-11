<?php
// admin/sidebar.php
if (!isset($nombreUsuario)) {
    $nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Administrador';
}
$currentPage = $currentPage ?? '';
?>
<aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-2xl">
    <div class="p-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shadow-md">
                <span class="text-xs font-bold text-purple-700">MT</span>
            </div>
            <div>
                <h1 class="text-white text-xl font-semibold leading-tight">Maywa Textil</h1>
                <p class="text-purple-100 text-xs">Panel administrativo</p>
            </div>
        </div>

        <!-- 🔹 Botón que abre el modal de reportes -->
        <button
            id="btn-reporte"
            class="w-full btn-maywa text-white font-semibold py-2.5 mt-6 rounded-xl shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 transition"
            type="button">
            <i class="fa-solid fa-file-arrow-down"></i>
            <span>Generar reporte</span>
        </button>
    </div>

    <nav class="text-white text-sm font-semibold pt-3">
        <a href="dashboard.php"
           class="flex items-center text-white py-3.5 pl-6 nav-item transition
                  <?= $currentPage === 'dashboard' ? 'active-nav-link' : 'opacity-80 hover:opacity-100' ?>">
            <i class="fa-solid fa-chart-line mr-3"></i>
            <span>Dashboard</span>
        </a>
        <a href="comunidad.php"
           class="flex items-center text-white py-3.5 pl-6 nav-item transition
                  <?= $currentPage === 'comunidades' ? 'active-nav-link' : 'opacity-80 hover:opacity-100' ?>">
            <i class="fa-solid fa-people-group mr-3"></i>
            <span>Comunidades</span>
        </a>
        <a href="producto.php"
           class="flex items-center text-white py-3.5 pl-6 nav-item transition
                  <?= $currentPage === 'productos' ? 'active-nav-link' : 'opacity-80 hover:opacity-100' ?>">
            <i class="fa-solid fa-shirt mr-3"></i>
            <span>Productos</span>
        </a>
        <a href="pedidos.php"
           class="flex items-center text-white py-3.5 pl-6 nav-item transition
                  <?= $currentPage === 'pedidos' ? 'active-nav-link' : 'opacity-80 hover:opacity-100' ?>">
            <i class="fa-solid fa-boxes-stacked mr-3"></i>
            <span>Pedidos</span>
        </a>
    </nav>
</aside>
