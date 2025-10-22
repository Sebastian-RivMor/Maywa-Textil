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
        <div class="w-full p-6 bg-gray-100 h-full overflow-y-auto"
            x-data="productoManager()"
            x-init="init()">

            <!-- Botón -->
            <div class="flex justify-end mb-6">
                <button @click="openModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-lg">
                + Añadir Producto
                </button>
            </div>
            <!-- Modal de edición -->
            <div x-show="openEditModal"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 relative overflow-y-auto max-h-[90vh]"
                @click.away="openEditModal = false" x-transition>
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 class="text-lg font-bold text-gray-700">Modificar Producto</h2>
                <button @click="openEditModal = false" class="text-gray-500 hover:text-gray-700 text-lg">&times;</button>
                </div>

                <form @submit.prevent="actualizarProducto" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <!-- ID -->
                <div>
                    <label class="block font-semibold text-gray-600">ID Producto</label>
                    <input type="text" x-model="editForm.id_producto" class="w-full border rounded-lg px-3 py-2 bg-gray-100" readonly>
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block font-semibold text-gray-600">Nombre</label>
                    <input type="text" x-model="editForm.nombre_producto" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <!-- Descripción corta -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Descripción corta</label>
                    <textarea x-model="editForm.descripcion_corta" rows="2" class="w-full border rounded-lg px-3 py-2" required></textarea>
                </div>

                <!-- Descripción completa -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Descripción completa</label>
                    <textarea x-model="editForm.descripcion_producto" rows="3" class="w-full border rounded-lg px-3 py-2" required></textarea>
                </div>

                <!-- Precio -->
                <div>
                    <label class="block font-semibold text-gray-600">Precio (S/.)</label>
                    <input type="number" step="0.01" x-model="editForm.precio" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <!-- Stock -->
                <div>
                    <label class="block font-semibold text-gray-600">Stock</label>
                    <input type="number" x-model="editForm.stock" class="w-full border rounded-lg px-3 py-2" required>
                </div>

                <!-- Estado -->
                <div>
                    <label class="block font-semibold text-gray-600">Estado</label>
                    <select x-model="editForm.estado_producto" class="w-full border rounded-lg px-3 py-2">
                    <option value="Disponible">Disponible</option>
                    <option value="Agotado">Agotado</option>
                    </select>
                </div>

                <!-- Imagen -->
                <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Actualizar Imagen (opcional)</label>
                    <input type="file" @change="editForm.imagen = $event.target.files[0]" accept="image/*" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="md:col-span-2 flex justify-end mt-2">
                    <button type="submit"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md">
                    Guardar Cambios
                    </button>
                </div>
                </form>
            </div>
            </div>


            <!-- Modal -->
            <div x-show="openModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl p-6 relative overflow-y-auto max-h-[90vh]"
                    @click.away="openModal = false" x-transition>
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h2 class="text-lg font-bold text-gray-700">Añadir Producto</h2>
                    <button @click="openModal = false" class="text-gray-500 hover:text-gray-700 text-lg">&times;</button>
                </div>

                <form @submit.prevent="agregarProducto" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">

                    <!-- Comunidad -->
                    <div>
                    <label class="block font-semibold text-gray-600">Comunidad</label>
                    <select x-model="id_comunidad"
                            class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                            required>
                        <option value="">Seleccionar...</option>
                        <template x-for="c in comunidades" :key="c.id_comunidad">
                        <option :value="c.id_comunidad" x-text="c.nombre_comunidad"></option>
                        </template>
                    </select>
                    </div>

                        <!-- Categoría -->
                        <div>
                        <label class="block font-semibold text-gray-600">Categoría</label>
                        <select x-model="id_categoria"
                                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                required>
                            <option value="">Seleccionar...</option>
                            <template x-for="cat in categorias" :key="cat.id_categoria">
                            <option :value="cat.id_categoria" x-text="cat.nombre_categoria"></option>
                            </template>
                        </select>
                        </div>

                        <!-- Material -->
                        <div>
                        <label class="block font-semibold text-gray-600">Material</label>
                        <select x-model="id_material"
                                class="w-full border rounded-lg px-3 py-2 focus:ring focus:ring-blue-200"
                                required>
                            <option value="">Seleccionar...</option>
                            <template x-for="m in materiales" :key="m.id_material">
                            <option :value="m.id_material" x-text="m.nombre_material"></option>
                            </template>
                        </select>
                    </div>


                    <!-- Nombre -->
                    <div>
                    <label class="block font-semibold text-gray-600">Nombre</label>
                    <input type="text" x-model="nombre" class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <!-- Descripción corta -->
                    <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Descripción corta</label>
                    <textarea x-model="descripcion_corta" rows="2" class="w-full border rounded-lg px-3 py-2" required></textarea>
                    </div>

                    <!-- Descripción completa -->
                    <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Descripción completa</label>
                    <textarea x-model="descripcion_completa" rows="3" class="w-full border rounded-lg px-3 py-2" required></textarea>
                    </div>

                    <!-- Precio -->
                    <div>
                    <label class="block font-semibold text-gray-600">Precio (S/.)</label>
                    <input type="number" x-model="precio" step="0.01" class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <!-- Stock -->
                    <div>
                    <label class="block font-semibold text-gray-600">Stock</label>
                    <input type="number" x-model="stock" class="w-full border rounded-lg px-3 py-2" required>
                    </div>

                    <!-- Imagen -->
                    <div class="md:col-span-2">
                    <label class="block font-semibold text-gray-600">Imagen principal</label>
                    <input type="file" @change="imagen = $event.target.files[0]" accept="image/*" class="w-full border rounded-lg px-3 py-2">
                    </div>

                    <div class="md:col-span-2 flex justify-end mt-2">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-md">
                        Guardar Producto
                    </button>
                    </div>
                </form>
                </div>
            </div>

            <!-- Tabla -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden mt-6">
            <table class="w-full table-auto text-left border-collapse">
                <thead>
                <tr class="bg-gray-200 text-gray-700 text-sm uppercase">
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
                    <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-3" x-text="p.id_producto"></td>
                    <td class="px-4 py-3" x-text="p.nombre_producto"></td>
                    <td class="px-4 py-3" x-text="p.descripcion_corta"></td>
                    <td class="px-4 py-3" x-text="`S/. ${p.precio}`"></td>
                    <td class="px-4 py-3" x-text="p.stock"></td>
                    <td class="px-4 py-3" x-text="p.estado_producto"></td>
                    <td class="px-4 py-3 text-center space-x-2">
                        <button 
                        @click="abrirModalEdicion(p)"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                        Modificar
                        </button>
                        <button 
                        @click="eliminarProducto(p.id_producto)"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                        Eliminar
                        </button>
                    </td>
                    </tr>
                </template>
                </tbody>
            </table>
            </div>

        </div>
    </div>
    <script>
    function productoManager() {
    return {
        // 🔹 Variables de control de modales
        openModal: false,
        openEditModal: false,

        // 🔹 Listas de datos
        comunidades: [],
        categorias: [],
        materiales: [],
        productos: [],

        // 🔹 Campos del formulario de registro
        id_comunidad: '',
        id_categoria: '',
        id_material: '',
        nombre: '',
        descripcion_corta: '',
        descripcion_completa: '',
        precio: '',
        stock: '',
        imagen: null,

        // 🔹 Formulario de edición
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

        // ======================================================
        // Inicialización
        // ======================================================
        init() {
        this.cargarListas();
        this.cargarProductos();
        },

        // ======================================================
        // Cargar listas de comunidad, categoría y material
        // ======================================================
        cargarListas() {
        Promise.all([
            fetch('/MAYWATEXTIL/api/admin/comunidad/get_comunidades.php').then(r => r.json()),
            fetch('/MAYWATEXTIL/api/admin/categoria/get_categorias.php').then(r => r.json()),
            fetch('/MAYWATEXTIL/api/admin/material/get_materiales.php').then(r => r.json())
        ])
        .then(([coms, cats, mats]) => {
            const toArray = (x) => Array.isArray(x) ? x : (x?.data ?? x?.rows ?? []);
            this.comunidades = toArray(coms);
            this.categorias  = toArray(cats);
            this.materiales  = toArray(mats);
            console.log('Listas cargadas:', this.comunidades, this.categorias, this.materiales);
        })
        .catch(err => console.error('Error cargando listas:', err));
        },

        // ======================================================
        // Cargar productos desde el backend
        // ======================================================
        cargarProductos() {
        fetch('/MAYWATEXTIL/api/admin/productos/get_producto.php')
            .then(r => r.json())
            .then(data => {
            console.log('Productos cargados:', data);
            this.productos.splice(0, this.productos.length, ...data); // Alpine v2 FIX
            })
            .catch(err => console.error('Error cargando productos:', err));
        },

        // ======================================================
        // Agregar producto nuevo
        // ======================================================
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

        fetch('/MAYWATEXTIL/api/admin/productos/add_producto.php', {
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

        // ======================================================
        // Abrir modal de edición
        // ======================================================
        abrirModalEdicion(p) {
        this.editForm = { ...p };
        this.editForm.imagen = null;
        this.openEditModal = true;
        },

        // ======================================================
        // Actualizar producto existente
        // ======================================================
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

        fetch('/MAYWATEXTIL/api/admin/productos/update_producto.php', {
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

        // ======================================================
        // Eliminar producto
        // ======================================================
        eliminarProducto(id) {
        if (!confirm('¿Seguro que deseas eliminar este producto?')) return;

        fetch('/MAYWATEXTIL/api/admin/productos/delete_producto.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_producto=${id}`
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

