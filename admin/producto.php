<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== "Admin") {
    header("Location: index.php");
    exit;
}

$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Administrador';

// Para marcar el ítem activo y textos del header
$currentPage  = 'productos';
$pageTitle    = 'Productos';
$pageSubtitle = 'Administra el catálogo de productos de Maywa Textil.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de productos | Maywa Textil</title>
    <meta name="author" content="Maywa Textil">
    <meta name="description" content="Panel de administración de productos Maywa Textil">

    <!-- Tailwind -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

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

<div class="w-full flex flex-col h-screen overflow-y-hidden">
    <?php include __DIR__ . '/header_admin.php'; ?>

    <!-- CONTENIDO -->
    <div class="w-full overflow-x-hidden border-t border-purple-200/40 flex flex-col">
        <main class="flex-1 p-6 md:p-8 overflow-auto"
              x-data="productoManager()"
              x-init="init()">

            <!-- Título y botón (igual estilo que comunidades) -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
                <div>
                    <h1 class="text-3xl font-bold text-white drop-shadow-sm">Productos</h1>
                    <p class="text-purple-100 text-sm">
                        Administra el catálogo de productos artesanales de Maywa Textil.
                    </p>
                </div>
                <button @click="openModal = true"
                        class="btn-maywa hover:shadow-xl text-white px-6 py-2 rounded-xl font-semibold shadow-md flex items-center space-x-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Nuevo producto</span>
                </button>
            </div>

            <!-- MODAL EDICIÓN (MEJORADO) -->
            <div x-show="openEditModal"
                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                 x-transition
                 @click.self="openEditModal = false">
                <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-full md:max-w-3xl p-6 md:p-7 relative overflow-y-auto max-h-[90vh]">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center space-x-2">
                            <span class="bg-yellow-100 text-yellow-700 w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </span>
                            <span>Modificar producto</span>
                        </h2>
                        <button @click="openEditModal = false"
                                class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>

                    <form @submit.prevent="actualizarProducto"
                          enctype="multipart/form-data"
                          class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                        <!-- ID -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">ID producto</label>
                            <input type="text" x-model="editForm.id_producto"
                                   class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500" readonly>
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Nombre</label>
                            <input type="text" x-model="editForm.nombre_producto"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200" required>
                        </div>

                        <!-- Descripción corta -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-600 mb-1">Descripción corta</label>
                            <textarea x-model="editForm.descripcion_corta" rows="2"
                                      class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200 resize-none" required></textarea>
                        </div>

                        <!-- Descripción completa -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-600 mb-1">Descripción completa</label>
                            <textarea x-model="editForm.descripcion_producto" rows="3"
                                      class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200 resize-none" required></textarea>
                        </div>

                        <!-- Precio -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Precio (S/.)</label>
                            <input type="number" step="0.01" x-model="editForm.precio"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200" required>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Stock</label>
                            <input type="number" x-model="editForm.stock"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200" required>
                        </div>

                        <!-- Estado -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Estado</label>
                            <select x-model="editForm.estado_producto"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200">
                                <option value="Disponible">Disponible</option>
                                <option value="Agotado">Agotado</option>
                            </select>
                        </div>

                        <!-- Imagen -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-600 mb-1">Actualizar imagen (opcional)</label>
                            <input type="file" @change="editForm.imagen = $event.target.files[0]"
                                   accept="image/*"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200">
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-2 space-x-3">
                            <button type="button"
                                    @click="openEditModal = false"
                                    class="px-4 py-2 rounded-xl border text-gray-600 hover:bg-gray-100 text-sm">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-xl font-semibold shadow-md flex items-center space-x-2 text-sm">
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                <span>Guardar cambios</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- MODAL NUEVO PRODUCTO (MEJORADO) -->
            <div x-show="openModal"
                 class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
                 x-transition
                 @click.self="openModal = false">
                <div class="bg-white rounded-2xl shadow-2xl w-11/12 md:w-full md:max-w-3xl p-6 md:p-7 relative overflow-y-auto max-h-[90vh]">
                    <div class="flex justify-between items-center border-b pb-3 mb-4">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center space-x-2">
                            <span class="bg-purple-100 text-purple-700 w-8 h-8 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-shirt text-sm"></i>
                            </span>
                            <span>Nuevo producto</span>
                        </h2>
                        <button @click="openModal = false"
                                class="text-gray-400 hover:text-gray-600 text-xl leading-none">&times;</button>
                    </div>

                    <form @submit.prevent="agregarProducto"
                          enctype="multipart/form-data"
                          class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                        <!-- Comunidad -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Comunidad</label>
                            <select x-model="id_comunidad"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200"
                                    required>
                                <option value="">Seleccione comunidad...</option>
                                <template x-for="c in comunidades" :key="c.id_comunidad">
                                    <option :value="c.id_comunidad" x-text="c.nombre_comunidad"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Categoría -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Categoría</label>
                            <select x-model="id_categoria"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200"
                                    required>
                                <option value="">Seleccione categoría...</option>
                                <template x-for="cat in categorias" :key="cat.id_categoria">
                                    <option :value="cat.id_categoria" x-text="cat.nombre_categoria"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Material -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Material</label>
                            <select x-model="id_material"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200"
                                    required>
                                <option value="">Seleccione material...</option>
                                <template x-for="m in materiales" :key="m.id_material">
                                    <option :value="m.id_material" x-text="m.nombre_material"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Nombre</label>
                            <input type="text" x-model="nombre"
                                   placeholder="Ej. Poncho de alpaca tejido a mano"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200" required>
                        </div>

                        <!-- Descripción corta -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-600 mb-1">Descripción corta</label>
                            <textarea x-model="descripcion_corta" rows="2"
                                      placeholder="Resumen breve que se mostrará en las tarjetas de producto."
                                      class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200 resize-none" required></textarea>
                        </div>

                        <!-- Descripción completa -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-600 mb-1">Descripción completa</label>
                            <textarea x-model="descripcion_completa" rows="3"
                                      placeholder="Describe la historia, materiales y cuidados del producto."
                                      class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200 resize-none" required></textarea>
                        </div>

                        <!-- Precio -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Precio (S/.)</label>
                            <input type="number" step="0.01" x-model="precio"
                                   placeholder="0.00"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200" required>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label class="block font-semibold text-gray-600 mb-1">Stock</label>
                            <input type="number" x-model="stock"
                                   placeholder="Cantidad disponible"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200" required>
                        </div>

                        <!-- Imagen -->
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-gray-600 mb-1">Imagen principal</label>
                            <input type="file" @change="imagen = $event.target.files[0]"
                                   accept="image/*"
                                   class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-purple-200">
                        </div>

                        <div class="md:col-span-2 flex justify-end mt-2 space-x-3">
                            <button type="button"
                                    @click="openModal = false"
                                    class="px-4 py-2 rounded-xl border text-gray-600 hover:bg-gray-100 text-sm">
                                Cancelar
                            </button>
                            <button type="submit"
                                    class="btn-maywa hover:shadow-xl text-white px-5 py-2 rounded-xl font-semibold shadow-md flex items-center space-x-2 text-sm">
                                <i class="fa-solid fa-floppy-disk text-xs"></i>
                                <span>Guardar producto</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TARJETA LISTADO (similar a Comunidades) -->
            <div class="bg-white backdrop-blur-sm shadow-xl rounded-2xl overflow-hidden mt-4">
                <!-- Header tarjeta -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-purple-100 bg-purple-50/60">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-700">
                            <i class="fa-solid fa-shirt"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-semibold text-purple-900">Listado de productos</h2>
                            <p class="text-xs text-purple-500">
                                Catálogo de productos artesanales disponibles.
                            </p>
                        </div>
                    </div>
                    <span class="text-xs text-purple-500"
                          x-text="productos.length + ' registrados'"></span>
                </div>

                <!-- Tabla -->
                <table class="w-full table-auto text-left border-collapse text-sm">
                    <thead>
                    <tr class="bg-purple-50 text-purple-700 uppercase">
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Precio</th>
                        <th class="px-4 py-3">Stock</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="p in productos" :key="p.id_producto">
                        <tr class="border-b last:border-b-0 hover:bg-purple-50/60">
                            <td class="px-4 py-3" x-text="p.id_producto"></td>
                            <td class="px-4 py-3" x-text="p.nombre_producto"></td>
                            <td class="px-4 py-3" x-text="p.descripcion_corta"></td>
                            <td class="px-4 py-3" x-text="`S/ ${p.precio}`"></td>
                            <td class="px-4 py-3" x-text="p.stock"></td>
                            <td class="px-4 py-3" x-text="p.estado_producto"></td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <button
                                    @click="abrirModalEdicion(p)"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs md:text-sm">
                                    Modificar
                                </button>
                                <button
                                    @click="eliminarProducto(p.id_producto)"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs md:text-sm">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

<script>
function productoManager() {
    return {
        openModal: false,
        openEditModal: false,

        comunidades: [],
        categorias: [],
        materiales: [],
        productos: [],

        id_comunidad: '',
        id_categoria: '',
        id_material: '',
        nombre: '',
        descripcion_corta: '',
        descripcion_completa: '',
        precio: '',
        stock: '',
        imagen: null,

        editForm: {
            id_producto: '',
            nombre_producto: '',
            descripcion_corta: '',
            descripcion_producto: '',
            precio: '',
            stock: '',
            estado_producto: '',
            imagen: null
        },

        init() {
            this.cargarListas();
            this.cargarProductos();
        },

        cargarListas() {
            Promise.all([
                fetch('../api/admin/comunidad/get_comunidades.php').then(r => r.json()),
                fetch('../api/admin/categoria/get_categorias.php').then(r => r.json()),
                fetch('../api/admin/material/get_materiales.php').then(r => r.json())
            ])
            .then(([coms, cats, mats]) => {
                const toArray = (x) => Array.isArray(x) ? x : (x?.data ?? x?.rows ?? []);
                this.comunidades = toArray(coms);
                this.categorias  = toArray(cats);
                this.materiales  = toArray(mats);
            })
            .catch(err => console.error('Error cargando listas:', err));
        },

        cargarProductos() {
            fetch('../api/admin/productos/get_producto.php')
                .then(r => r.json())
                .then(data => {
                    this.productos.splice(0, this.productos.length, ...data);
                })
                .catch(err => console.error('Error cargando productos:', err));
        },

        agregarProducto() {
            const form = new FormData();
            form.append('id_comunidad', this.id_comunidad);
            form.append('id_categoria', this.id_categoria);
            form.append('id_material', this.id_material);
            form.append('nombre', this.nombre);
            form.append('descripcion_previa', this.descripcion_corta);
            form.append('descripcion_completa', this.descripcion_completa);
            form.append('precio', this.precio);
            form.append('stock', this.stock);
            if (this.imagen) form.append('imagenes[]', this.imagen);

            fetch('../api/admin/productos/add_producto.php', {
                method: 'POST',
                body: form
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Producto añadido correctamente');
                    this.openModal = false;
                    this.cargarProductos();
                } else {
                    alert(data.error || 'Error al guardar producto');
                }
            })
            .catch(err => console.error('Error al enviar producto:', err));
        },

        abrirModalEdicion(p) {
            this.editForm = { ...p };
            this.editForm.imagen = null;
            this.openEditModal = true;
        },

        actualizarProducto() {
            const form = new FormData();
            form.append('id_producto', this.editForm.id_producto);
            form.append('nombre_producto', this.editForm.nombre_producto);
            form.append('descripcion_corta', this.editForm.descripcion_corta);
            form.append('descripcion_producto', this.editForm.descripcion_producto);
            form.append('precio', this.editForm.precio);
            form.append('stock', this.editForm.stock);
            form.append('estado_producto', this.editForm.estado_producto);
            if (this.editForm.imagen) form.append('imagen', this.editForm.imagen);

            fetch('../api/admin/productos/update_producto.php', {
                method: 'POST',
                body: form
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Producto actualizado correctamente');
                    this.openEditModal = false;
                    this.cargarProductos();
                } else {
                    alert(data.error || 'Error al actualizar producto');
                }
            })
            .catch(err => console.error('Error al actualizar producto:', err));
        },

        eliminarProducto(id) {
            if (!confirm('¿Seguro que deseas eliminar este producto?')) return;

            fetch('../api/admin/productos/delete_producto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `id_producto=${encodeURIComponent(id)}`
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Producto eliminado correctamente');
                    this.cargarProductos();
                } else {
                    alert(data.error || 'Error al eliminar producto');
                }
            })
            .catch(err => console.error('Error al eliminar producto:', err));
        }
    };
}
</script>


</body>
</html>
