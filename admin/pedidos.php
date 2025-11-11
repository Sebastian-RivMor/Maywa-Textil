<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== "Admin") {
    header("Location: index.php");
    exit;
}

$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Administrador';

// Para marcar ítem activo y textos del header
$currentPage  = 'pedidos';
$pageTitle    = 'Pedidos';
$pageSubtitle = 'Revisa y gestiona los pedidos realizados en Maywa Textil.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de pedidos | Maywa Textil</title>
    <meta name="author" content="Maywa Textil">
    <meta name="description" content="Panel de administración de pedidos Maywa Textil">

    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <!-- AlpineJS (para header) -->
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

<div class="w-full flex flex-col h-screen overflow-y-hidden">
    <?php include __DIR__ . '/header_admin.php'; ?>

    <!-- CONTENIDO -->
    <div class="w-full overflow-x-hidden border-t border-purple-200/40 flex flex-col">
        <main class="flex-1 p-6 md:p-8 overflow-auto">
            <!-- Título -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-white drop-shadow-sm">Pedidos</h1>
                <p class="text-purple-100 text-sm">
                    Consulta el estado de los pedidos y actualiza su progreso.
                </p>
            </div>

            <!-- TARJETA LISTADO DE PEDIDOS -->
            <div class="bg-white backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden">
                <!-- Header tarjeta -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-purple-100 bg-purple-50/60">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-purple-900">Listado de pedidos</h2>
                            <p class="text-xs text-purple-500">
                                Revisa los pedidos recientes y su estado actual.
                            </p>
                        </div>
                    </div>
                    <span id="total-pedidos" class="text-xs text-purple-500">0 registrados</span>
                </div>

                <!-- Tabla -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-purple-50 text-purple-700 uppercase">
                        <tr>
                            <th class="px-6 py-3 text-left">#</th>
                            <th class="px-6 py-3 text-left">Cliente</th>
                            <th class="px-6 py-3 text-left">Fecha</th>
                            <th class="px-6 py-3 text-left">Estado</th>
                            <th class="px-6 py-3 text-right">Total (S/)</th>
                            <th class="px-6 py-3 text-center">Acciones</th>
                        </tr>
                        </thead>
                        <tbody id="tabla-pedidos" class="divide-y divide-gray-200 text-gray-700 bg-white"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- MODAL DETALLE (MEJORADO) -->
<div id="modal-detalle"
     class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-full md:max-w-3xl p-6 md:p-7 relative overflow-y-auto max-h-[90vh]">
        <!-- Header modal -->
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-lg font-bold text-gray-800 flex items-center space-x-2">
                <span class="bg-purple-100 text-purple-700 w-8 h-8 rounded-full flex items-center justify-center">
                    <i class="fa-solid fa-receipt text-sm"></i>
                </span>
                <span>Detalle del pedido</span>
            </h2>
            <button onclick="cerrarModal()"
                    class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
        </div>

        <!-- Info pedido -->
        <div id="info-pedido" class="grid grid-cols-1 md:grid-cols-2 gap-y-2 gap-x-4 text-sm mb-5">
            <p><span class="font-semibold text-gray-600">Cliente:</span> <span id="detalle-cliente"></span></p>
            <p><span class="font-semibold text-gray-600">Departamento:</span> <span id="detalle-departamento"></span></p>
            <p><span class="font-semibold text-gray-600">Dirección:</span> <span id="detalle-direccion"></span></p>
            <p><span class="font-semibold text-gray-600">Fecha pedido:</span> <span id="detalle-fecha"></span></p>
            <p><span class="font-semibold text-gray-600">Fecha envío:</span> <span id="detalle-envio"></span></p>
            <p>
                <span class="font-semibold text-gray-600">Estado envío:</span>
                <span id="detalle-estado-envio" class="font-semibold text-purple-700"></span>
            </p>
        </div>

        <!-- Productos -->
        <h3 class="font-semibold text-gray-800 mb-2">Productos</h3>
        <div class="border rounded-xl overflow-hidden mb-5">
            <table class="w-full text-sm">
                <thead class="bg-purple-50 text-purple-700">
                <tr>
                    <th class="p-2 text-left">Producto</th>
                    <th class="p-2 text-center">Cantidad</th>
                    <th class="p-2 text-center">Precio (S/)</th>
                </tr>
                </thead>
                <tbody id="detalle-productos" class="bg-white"></tbody>
            </table>
        </div>

        <!-- Modificar estado -->
        <div class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 mt-2">
            <div class="flex items-center space-x-2">
                <label for="nuevo-estado" class="font-semibold text-gray-700 text-sm">Modificar estado:</label>
                <select id="nuevo-estado"
                        class="border rounded-lg px-3 py-1.5 text-sm focus:ring focus:ring-purple-200">
                    <!-- Las opciones se cargarán aquí dinámicamente -->
                </select>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button"
                        onclick="cerrarModal()"
                        class="px-4 py-2 rounded-xl border text-gray-600 hover:bg-gray-100 text-sm">
                    Cancelar
                </button>
                <button type="button"
                        onclick="actualizarEstado()"
                        class="btn-maywa text-white px-5 py-2 rounded-xl font-semibold shadow-md text-sm flex items-center space-x-2">
                    <i class="fa-solid fa-floppy-disk text-xs"></i>
                    <span>Guardar cambios</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const API_PEDIDOS = '../api/admin/pedidos/get_pedidos.php';
const API_DETALLE = '../api/admin/pedidos/get_pedido_detalle.php';
const API_ESTADOS = '../api/admin/pedidos/get_estados_pedido.php'; // Nuevo API para estados

// Función para obtener y cargar los estados de la base de datos
async function cargarEstados() {
    try {
        const res = await fetch(API_ESTADOS);
        const data = await res.json();

        if (data.error) throw new Error(data.error);

        const estadoSelect = document.getElementById('nuevo-estado');
        estadoSelect.innerHTML = ''; // Limpiar las opciones previas

        // Agregar las opciones al select
        data.estados.forEach(estado => {
            const option = document.createElement('option');
            option.value = estado;
            option.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            estadoSelect.appendChild(option);
        });

    } catch (err) {
        console.error('Error al cargar estados:', err);
    }
}

function pintarFilasPedidos(rows) {
    const tbody = document.getElementById('tabla-pedidos');
    tbody.innerHTML = rows.map(p => ` 
        <tr class="hover:bg-purple-50/60">
            <td class="px-6 py-3">${p.id_pedido}</td>
            <td class="px-6 py-3">${p.cliente ?? ''} ${p.apellido ?? ''}</td>
            <td class="px-6 py-3">${p.fecha_pedido ?? ''}</td>
            <td class="px-6 py-3">${p.estado_pedido ?? ''}</td>
            <td class="px-6 py-3 text-right">${p.total ?? ''}</td>
            <td class="px-6 py-3 text-center">
                <button onclick="verDetalle(${p.id_pedido})" class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs md:text-sm hover:bg-blue-700">
                    Ver detalle
                </button>
            </td>
        </tr> 
    `).join('');

    const totalSpan = document.getElementById('total-pedidos');
    if (totalSpan) {
        totalSpan.textContent = rows.length + ' registrados';
    }
}

async function parseJsonSeguro(res) {
    const ct = res.headers.get('content-type') || '';
    if (!ct.includes('application/json')) {
        const raw = await res.text();
        throw new Error(`Respuesta no-JSON (${res.status}). Inicio: ${raw.slice(0,200)}`);
    }
    return res.json();
}

function normalizarLista(payload) {
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
            const texto = await res.text().catch(() => '');
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
        const res = await fetch(`${API_DETALLE}?id_pedido=${encodeURIComponent(id)}`);
        const data = await res.json();
        if (data.error) throw new Error(data.error);

        document.getElementById('detalle-cliente').textContent =
            `${data.cliente ?? ''} ${data.apellido ?? ''}`;
        document.getElementById('detalle-direccion').textContent = data.direccion_entrega ?? '';
        document.getElementById('detalle-departamento').textContent = data.departamento_envio ?? '';
        document.getElementById('detalle-fecha').textContent = data.fecha_pedido ?? '';
        document.getElementById('detalle-envio').textContent = data.fecha_envio ?? '—';
        document.getElementById('detalle-estado-envio').textContent = data.estado_envio ?? 'Pendiente';
        document.getElementById('nuevo-estado').value = data.estado_pedido ?? 'pendiente';

        const tbody = document.getElementById('detalle-productos');
        if (Array.isArray(data.productos) && data.productos.length > 0) {
            tbody.innerHTML = data.productos.map(p => ` 
                <tr class="border-t">
                    <td class="p-2">${p.nombre_producto}</td>
                    <td class="p-2 text-center">${p.cantidad}</td>
                    <td class="p-2 text-center">${p.precio_unitario}</td>
                </tr> 
            `).join('');
        } else {
            tbody.innerHTML = `<tr><td class="p-2 text-center" colspan="3">Sin productos</td></tr>`;
        }

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
        const res = await fetch('../api/admin/pedidos/update_estado_pedido.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id_pedido: id, estado: nuevoEstado })
        });
        const data = await res.json();
        if (data.error) throw new Error(data.error);

        alert('Estado actualizado correctamente');
        cerrarModal();
        cargarPedidos();
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

document.addEventListener('DOMContentLoaded', () => {
    cargarEstados();
    cargarPedidos();
});
</script>

</body>
</html>
