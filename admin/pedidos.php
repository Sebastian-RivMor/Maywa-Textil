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

    <main class="flex-1 p-8 overflow-auto">
        <h1 class="text-3xl font-bold mb-6">Pedidos</h1>

        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total (S/)</th>
                <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody id="tabla-pedidos" class="divide-y divide-gray-200 text-sm text-gray-700"></tbody>
            </table>
        </div>
        </main>

        <!-- Modal Detalle -->
        <div id="modal-detalle" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
            <div class="bg-white w-full max-w-3xl p-6 rounded-lg shadow-lg relative">
                <button onclick="cerrarModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800">✕</button>
                <h2 class="text-2xl font-semibold mb-4">Detalle del pedido</h2>

                <div id="info-pedido" class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm mb-4">
                <p><b>Cliente:</b> <span id="detalle-cliente"></span></p>
                <p><b>Departamento:</b> <span id="detalle-departamento"></span></p>
                <p><b>Dirección:</b> <span id="detalle-direccion"></span></p>
                <p><b>Fecha pedido:</b> <span id="detalle-fecha"></span></p>
                <p><b>Fecha envío:</b> <span id="detalle-envio"></span></p>
                <p><b>Estado envío:</b> <span id="detalle-estado-envio" class="font-semibold"></span></p>
                </div>

                <h3 class="font-semibold mb-2">Productos</h3>
                <table class="w-full text-sm mb-4 border">
                <thead class="bg-gray-100">
                    <tr>
                    <th class="p-2 text-left">Producto</th>
                    <th class="p-2 text-center">Cantidad</th>
                    <th class="p-2 text-center">Precio (S/)</th>
                    </tr>
                </thead>
                <tbody id="detalle-productos"></tbody>
                </table>

                <div class="flex items-center justify-between mt-4">
                <div>
                    <label for="nuevo-estado" class="mr-2 font-semibold">Modificar estado:</label>
                    <select id="nuevo-estado" class="border rounded p-1">
                    <option value="pendiente">Pendiente</option>
                    <option value="enviado">Enviado</option>
                    <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <button onclick="actualizarEstado()" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    Guardar cambios
                </button>
                </div>
            </div>
        </div>


    <script>
    const API_PEDIDOS = '/MAYWATEXTIL/api/admin/pedidos/get_pedidos.php';
    const API_DETALLE = '/MAYWATEXTIL/api/admin/pedidos/get_pedido_detalle.php';

    function pintarFilasPedidos(rows) {
    const tbody = document.getElementById('tabla-pedidos');
    tbody.innerHTML = rows.map(p => `
        <tr>
        <td class="px-6 py-3">${p.id_pedido}</td>
        <td class="px-6 py-3">${p.cliente ?? ''} ${p.apellido ?? ''}</td>
        <td class="px-6 py-3">${p.fecha_pedido ?? ''}</td>
        <td class="px-6 py-3">${p.estado_pedido ?? ''}</td>
        <td class="px-6 py-3 text-right">${p.total ?? ''}</td>
        <td class="px-6 py-3 text-center">
            <button onclick="verDetalle(${p.id_pedido})"
            class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
            Ver detalle
            </button>
        </td>
        </tr>
    `).join('');
    }

    async function parseJsonSeguro(res) {
    // Asegura JSON y muestra diagnóstico útil en consola si llega HTML o texto
    const ct = res.headers.get('content-type') || '';
    if (!ct.includes('application/json')) {
        const raw = await res.text();
        throw new Error(`Respuesta no-JSON (${res.status}). Inicio: ${raw.slice(0,200)}`);
    }
    return res.json();
    }

    function normalizarLista(payload) {
    // Acepta: []  ó  {data: []}  ó  {rows: []}
    if (Array.isArray(payload)) return payload;
    if (payload && Array.isArray(payload.data)) return payload.data;
    if (payload && Array.isArray(payload.rows)) return payload.rows;
    return null;
    }

    async function cargarPedidos() {
    const tbody = document.getElementById('tabla-pedidos');
    tbody.innerHTML = `<tr><td class="px-6 py-3" colspan="6">Cargando...</td></tr>`;
    try {
        const res = await fetch(API_PEDIDOS, { credentials: 'same-origin' });
        if (!res.ok) {
        const texto = await res.text().catch(()=>'');
        throw new Error(`HTTP ${res.status} ${res.statusText} ${texto.slice(0,120)}`);
        }
        const payload = await parseJsonSeguro(res);
        if (payload?.error) throw new Error(payload.error);

        const rows = normalizarLista(payload);
        if (!rows) throw new Error('Respuesta inválida del servidor (no es lista).');

        pintarFilasPedidos(rows);
    } catch (e) {
        console.error('Error al cargar pedidos:', e);
        tbody.innerHTML = `<tr><td class="px-6 py-3 text-red-600" colspan="6">
        Error al cargar pedidos: ${e.message}
        </td></tr>`;
    }
    }

    async function verDetalle(id) {
        const modal = document.getElementById('modal-detalle');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        try {
            // 🔹 Ajusta la ruta según la ubicación real
            const res = await fetch(`/MAYWATEXTIL/api/admin/pedidos/get_pedido_detalle.php?id_pedido=${id}`);
            const data = await res.json();
            if (data.error) throw new Error(data.error);

            // 🔹 Mostrar datos del pedido (sección superior del modal)
            document.getElementById('detalle-cliente').textContent = `${data.cliente} ${data.apellido}`;
            document.getElementById('detalle-direccion').textContent = data.direccion_entrega;
            document.getElementById('detalle-departamento').textContent = data.departamento_envio;
            document.getElementById('detalle-fecha').textContent = data.fecha_pedido;
            document.getElementById('detalle-envio').textContent = data.fecha_envio ?? '—';
            document.getElementById('detalle-estado-envio').textContent = data.estado_envio ?? 'pendiente';
            document.getElementById('nuevo-estado').value = data.estado_pedido ?? 'pendiente';

            // 🔹 Renderizar productos correctamente
            const tbody = document.getElementById('detalle-productos');
            if (Array.isArray(data.productos) && data.productos.length > 0) {
            tbody.innerHTML = data.productos.map(p => `
                <tr>
                <td class="p-2">${p.nombre_producto}</td>
                <td class="p-2 text-center">${p.cantidad}</td>
                <td class="p-2 text-center">${p.precio_unitario}</td>
                </tr>
            `).join('');
            } else {
            tbody.innerHTML = `<tr><td class="p-2 text-center" colspan="3">Sin productos</td></tr>`;
            }

            // Guardar id temporal en el modal
            modal.dataset.id = id;

        } catch (err) {
            console.error('Error al cargar detalle:', err);
            alert('No se pudo cargar la información del pedido');
        }
    }

    async function actualizarEstado() {
    const modal = document.getElementById('modal-detalle');
    const id = modal.dataset.id;
    const nuevoEstado = document.getElementById('nuevo-estado').value;

    try {
        const res = await fetch('/MAYWATEXTIL/api/admin/pedidos/update_estado_pedido.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_pedido: id, estado: nuevoEstado })
        });
        const data = await res.json();
        if (data.error) throw new Error(data.error);

        alert('Estado actualizado correctamente');
        cerrarModal();
        cargarPedidos(); // Refresca la tabla principal
    } catch (err) {
        console.error(err);
        alert('Error al actualizar estado: ' + err.message);
    }
    }
    function cerrarModal() {
        const modal = document.getElementById('modal-detalle');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    window.addEventListener('DOMContentLoaded', cargarPedidos);
</script>

</body>
</html>

