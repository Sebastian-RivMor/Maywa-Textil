<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== "Admin") {
    header("Location: index.php");
    exit;
}

$nombreUsuario = $_SESSION['usuario']['nombre'] ?? 'Administrador';
$currentPage   = 'comunidades'; // para marcar el menú activo
$pageTitle     = 'Comunidades';
$pageSubtitle  = 'Gestiona las comunidades artesanas asociadas a Maywa Textil.';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Comunidades | Maywa Textil</title>

  <!-- Tailwind -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

  <!-- Font Awesome (ICONOS) -->
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>

  <!-- AlpineJS (v2) -->
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

  <!-- CONTENEDOR PRINCIPAL -->
  <div class="w-full flex flex-col h-screen overflow-y-hidden"
       x-data="comunidadesPage()"
       x-init="cargarComunidades()">

    <?php include __DIR__ . '/header_admin.php'; ?>

    <!-- CONTENIDO -->
    <div class="w-full overflow-x-hidden border-t border-purple-200/40 flex flex-col">
            <main class="flex-1 p-6 md:p-8 overflow-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
            <h1 class="text-3xl font-bold text-white drop-shadow-sm">Comunidades</h1>
            <p class="text-purple-100 text-sm mt-1">
                Registra y administra las comunidades artesanas con las que trabajas.
            </p>
            </div>
            <button @click="openModal = true"
                    class="btn-maywa text-white px-5 py-2 rounded-xl font-semibold shadow-lg flex items-center space-x-2 text-sm">
            <i class="fa-solid fa-plus"></i>
            <span>Nueva comunidad</span>
            </button>
        </div>

        <!-- TABLA -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-800 flex items-center space-x-2">
                <i class="fa-solid fa-people-roof text-purple-700"></i>
                <span>Listado de comunidades</span>
            </h2>
            <span class="text-xs text-gray-500" x-text="comunidades.length + ' registradas'"></span>
            </div>

            <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead class="bg-purple-50 text-purple-700 text-xs uppercase">
                <tr>
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Nombre</th>
                <th class="px-6 py-3">Descripción</th>
                <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-700">
                <template x-if="comunidades.length === 0">
                <tr>
                    <td colspan="4" class="px-6 py-5 text-center text-gray-400 text-sm">
                    Aún no hay comunidades registradas.
                    </td>
                </tr>
                </template>

                <template x-for="com in comunidades" :key="com.id_comunidad">
                <tr class="hover:bg-purple-50/60">
                    <td class="px-6 py-3" x-text="com.id_comunidad"></td>
                    <td class="px-6 py-3 font-medium" x-text="com.nombre_comunidad"></td>
                    <td class="px-6 py-3" x-text="com.descripcion"></td>
                    <td class="px-6 py-3 text-center">
                    <button
                        @click="eliminarComunidad(com.id_comunidad)"
                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-semibold shadow inline-flex items-center space-x-1">
                        <i class="fa-solid fa-trash"></i>
                        <span>Eliminar</span>
                    </button>
                    </td>
                </tr>
                </template>
                </tbody>
            </table>
            </div>
        </div>
        </main>
    </div>

    <!-- MODAL NUEVA COMUNIDAD -->
    <div x-show="openModal"
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
         x-transition>
      <div class="bg-white rounded-2xl shadow-2xl w-11/12 max-w-lg p-6 relative"
           @click.away="openModal = false">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
          <h2 class="text-lg font-bold text-gray-800 flex items-center space-x-2">
            <i class="fa-solid fa-people-group text-purple-700"></i>
            <span>Nueva comunidad</span>
          </h2>
          <button @click="openModal = false"
                  class="text-gray-500 hover:text-gray-700 text-xl leading-none">&times;</button>
        </div>

        <form @submit.prevent="guardarComunidad" class="space-y-4">
          <!-- ID -->
          <div>
            <label class="block text-xs font-semibold text-gray-500 mb-1">ID Comunidad</label>
            <input type="text" value="Auto"
                   readonly
                   class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-500 text-sm">
          </div>

          <!-- Nombre -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre de la comunidad</label>
            <input type="text" x-model="nombre"
                   class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-purple-200 focus:border-purple-400 outline-none"
                   placeholder="Ej. Asociación de Mujeres Artesanas Makyss"
                   required>
          </div>

          <!-- Descripción -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Descripción</label>
            <textarea x-model="descripcion" rows="3"
                      class="w-full border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-purple-200 focus:border-purple-400 outline-none resize-none"
                      placeholder="Breve descripción de la comunidad, su origen, técnicas textiles, etc."
                      required></textarea>
          </div>

          <!-- Botones -->
          <div class="flex justify-end space-x-3 pt-2">
            <button type="button"
                    @click="openModal = false"
                    class="px-4 py-2 rounded-xl border text-gray-600 hover:bg-gray-100 text-sm">
              Cancelar
            </button>
            <button type="submit"
                    class="btn-maywa text-white px-6 py-2 rounded-xl font-semibold shadow-md text-sm flex items-center space-x-2">
              <i class="fa-solid fa-check"></i>
              <span>Guardar</span>
            </button>
          </div>
        </form>
      </div>
    </div>

  </div> <!-- /contenedor principal -->

  <script>
    function comunidadesPage() {
      return {
        openModal: false,
        comunidades: [],
        nombre: '',
        descripcion: '',

        async cargarComunidades() {
          try {
            const res = await fetch('../api/admin/comunidad/get_comunidades.php');
            const data = await res.json();
            this.comunidades = Array.isArray(data) ? data : [];
          } catch (e) {
            console.error('Error al cargar comunidades:', e);
          }
        },

        async guardarComunidad() {
          try {
            const res = await fetch('../api/admin/comunidad/add_comunidad.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({
                nombre_comunidad: this.nombre,
                descripcion: this.descripcion
              })
            });
            const data = await res.json();

            if (data.success) {
              this.nombre = '';
              this.descripcion = '';
              this.openModal = false;
              await this.cargarComunidades();
            } else {
              alert(data.error || 'Error al registrar comunidad');
            }
          } catch (e) {
            console.error('Error guardando comunidad:', e);
            alert('Ocurrió un error al registrar la comunidad');
          }
        },

        async eliminarComunidad(id) {
          if (!confirm('¿Seguro que deseas eliminar esta comunidad?')) return;

          try {
            const res = await fetch('../api/admin/comunidad/delete_comunidad.php', {
              method: 'POST',
              headers: { 'Content-Type': 'application/json' },
              body: JSON.stringify({ id_comunidad: id })
            });
            const data = await res.json();

            if (data.success) {
              this.comunidades = this.comunidades.filter(c => c.id_comunidad != id);
            } else {
              alert(data.error || 'Error al eliminar comunidad');
            }
          } catch (e) {
            console.error('Error eliminando comunidad:', e);
            alert('Ocurrió un error al eliminar la comunidad');
          }
        }
      }
    }
</script>

</body>
</html>
