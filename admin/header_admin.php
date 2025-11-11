<?php
if (!isset($nombreUsuario)) {
    $nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Administrador';
}

// Título y subtítulo de la página actual
$pageTitle    = $pageTitle    ?? 'Panel de control';
$pageSubtitle = $pageSubtitle ?? 'Administra tus productos, pedidos y comunidades de Maywa Textil.';
?>
<header class="w-full bg-header py-3 px-6 hidden sm:flex items-center shadow-lg">
    <div class="w-1/2 text-white">
        <h2 class="font-semibold text-lg">
            <?= htmlspecialchars($pageTitle) ?>,
            <span class="text-pink-200"><?= htmlspecialchars($nombreUsuario) ?></span>
        </h2>
        <p class="text-xs text-purple-100 mt-1">
            <?= htmlspecialchars($pageSubtitle) ?>
        </p>
    </div>
    <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end items-center space-x-3">
        <div class="text-right text-white mr-2 hidden md:block">
            <p class="text-sm font-semibold"><?= htmlspecialchars($nombreUsuario) ?></p>
            <p class="text-xs text-purple-200">Administrador</p>
        </div>

        <button @click="isOpen = !isOpen"
                class="relative z-10 w-11 h-11 rounded-full overflow-hidden border-2 border-pink-400 shadow-md bg-white flex items-center justify-center">
            <span class="text-sm font-bold text-purple-700">
                <?= strtoupper(substr($nombreUsuario, 0, 1)) ?>
            </span>
        </button>

        <button x-show="isOpen" @click="isOpen = false"
                class="h-full w-full fixed inset-0 cursor-default"></button>

        <div x-show="isOpen"
             class="absolute mt-16 right-0 w-44 bg-white rounded-xl shadow-xl py-2 text-sm transform origin-top-right transition">
            <p class="px-4 py-2 text-gray-500 text-xs border-b">Cuenta</p>
            <a href="#"
               class="block px-4 py-2 account-link text-gray-700 flex items-center space-x-2">
                <i class="fa-regular fa-user text-purple-600"></i>
                <span>Mi perfil (pronto)</span>
            </a>
            <a href="logout.php"
               class="block px-4 py-2 account-link text-gray-700 flex items-center space-x-2 rounded-b-xl">
                <i class="fa-solid fa-right-from-bracket text-purple-600"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>
    </div>
</header>

<!-- 🔹 MODAL PARA ELEGIR TIPO DE REPORTE -->
<div id="reporteModal"
     class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 max-w-md p-6 relative">
        <button type="button"
                id="reporteModalCerrar"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-xl leading-none">
            &times;
        </button>

        <h2 class="text-lg font-bold text-gray-800 mb-1">Generar reporte</h2>
        <p class="text-sm text-gray-600 mb-4">
            Selecciona el tipo de reporte que deseas descargar.
        </p>

        <div class="space-y-3">
            <!-- HU13 – Ventas por producto -->
            <button type="button"
                    data-tipo="ventas_producto"
                    class="opcion-reporte w-full flex items-center justify-between px-4 py-3 rounded-xl border border-purple-200 hover:border-purple-400 hover:bg-purple-50 transition">
                <div class="text-left">
                    <p class="text-sm font-semibold text-purple-800">Ventas por producto</p>
                    <p class="text-xs text-gray-500">
                        Cantidad vendida y total por producto.
                    </p>
                </div>
                <i class="fa-solid fa-chart-column text-purple-600"></i>
            </button>

            <!-- HU14 – Pedidos por estado -->
            <button type="button"
                    data-tipo="pedidos_estado"
                    class="opcion-reporte w-full flex items-center justify-between px-4 py-3 rounded-xl border border-purple-200 hover:border-purple-400 hover:bg-purple-50 transition">
                <div class="text-left">
                    <p class="text-sm font-semibold text-purple-800">Pedidos por estado</p>
                    <p class="text-xs text-gray-500">
                        Pedidos agrupados por estado (pendiente, enviado, etc.).
                    </p>
                </div>
                <i class="fa-solid fa-clipboard-list text-purple-600"></i>
            </button>

            <!-- HU15 – Stock bajo -->
            <button type="button"
                    data-tipo="stock_bajo"
                    class="opcion-reporte w-full flex items-center justify-between px-4 py-3 rounded-xl border border-purple-200 hover:border-purple-400 hover:bg-purple-50 transition">
                <div class="text-left">
                    <p class="text-sm font-semibold text-purple-800">Stock bajo</p>
                    <p class="text-xs text-gray-500">
                        Productos con stock menor a 10 unidades.
                    </p>
                </div>
                <i class="fa-solid fa-box-open text-purple-600"></i>
            </button>
        </div>
    </div>
</div>

<script>
// 🔸 URL base del generador de reportes (desde /admin/*.php)
const REPORT_BASE_URL = '../api/admin/dashboard/generar_reporte.php';

document.addEventListener('DOMContentLoaded', () => {
    const btnReporte      = document.getElementById('btn-reporte');
    const modal           = document.getElementById('reporteModal');
    const btnCerrar       = document.getElementById('reporteModalCerrar');
    const opcionesReporte = document.querySelectorAll('.opcion-reporte');

    if (!btnReporte || !modal) return;

    // Abrir modal
    btnReporte.addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    });

    // Cerrar modal con la X
    if (btnCerrar) {
        btnCerrar.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    }

    // Cerrar modal al hacer click fuera del contenido
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    });

    // Click en cada opción de reporte
    opcionesReporte.forEach(btn => {
        btn.addEventListener('click', () => {
            const tipo = btn.getAttribute('data-tipo');
            if (!tipo) return;

            const url = `${REPORT_BASE_URL}?tipo=${encodeURIComponent(tipo)}`;
            window.open(url, '_blank');

            // Cerrar modal
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
    });
});
</script>
