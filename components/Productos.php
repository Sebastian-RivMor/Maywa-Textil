<?php
// PANEL DE PRODUCTOS
$api_url = "http://localhost/MAYWATEXTIL/api/admin/productos/get_producto.php";
$productos = json_decode(@file_get_contents($api_url), true);

// Validar respuesta
if (!is_array($productos)) {
  echo "<p class='text-red-500 text-center'>❌ Error al cargar productos.</p>";
  return;
}

// --- CONFIGURACIÓN DE PAGINACIÓN ---
$productos_por_pagina = 9; // 3x3
$total_productos = count($productos);
$total_paginas = ceil($total_productos / $productos_por_pagina);
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? intval($_GET['pagina']) : 1;

// Evitar valores fuera de rango
if ($pagina_actual < 1) $pagina_actual = 1;
if ($pagina_actual > $total_paginas) $pagina_actual = $total_paginas;

// Calcular índice de inicio
$inicio = ($pagina_actual - 1) * $productos_por_pagina;

// Extraer solo los productos de esta página
$productos_pagina = array_slice($productos, $inicio, $productos_por_pagina);
?>

<!-- PANEL -->
<!-- MOVIDO: x-data="filters()" al contenedor principal para que el buscador y filtros lo usen -->
<div class="max-w-7xl mx-auto px-4" x-data="filters()">
  <div class="rounded-[28px] shadow-2xl overflow-hidden bg-gradient-to-br from-[#2b134b] to-[#1a0f2e] border border-white/10">

    <!-- Header -->
    <div class="px-5 md:px-8 pt-5 md:pt-7 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <h2 class="text-white text-xl md:text-2xl font-bold">Todos los productos</h2>
      <!-- Buscador -->
      <div class="flex items-center bg-[#2a1653]/90 border border-white/10 rounded-full pl-4">
        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/>
        </svg>
        <!-- AÑADIDO: x-model para vincular el input directamente a la variable `busqueda` (opcional, pero útil) -->
        <input id="buscador" x-model="busqueda" type="text" placeholder="Ingrese nombre del producto" class="w-48 md:w-80 bg-transparent text-white placeholder-white/70 px-3 py-2 focus:outline-none" @keydown.enter="handleSearch()">
        <!-- CORREGIDO: El botón ya llama a handleSearch() y ahora tiene acceso a la función -->
        <button @click="handleSearch()" class="ml-2 mr-1 px-4 py-2 text-sm font-semibold rounded-full bg-[#9D4EDD] hover:bg-[#7b3fc4] text-white">Buscar</button>
      </div>

    </div>

    <!-- CUERPO -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-5 md:px-8 pb-8 pt-6">

      <!-- SIDEBAR FILTROS -->
      <aside class="md:col-span-1 space-y-6">
        <!-- Categorías -->
        <div x-data="{ open: true }" class="bg-[#2a1653] rounded-lg border border-white/10">
          <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-white/90 font-semibold">
            <span>Categorías</span>
            <span :class="open ? 'rotate-180 text-[#C77DFF]' : ''" class="transform transition-transform text-white/70">▾</span>
          </button>
          <div x-show="open" x-transition class="px-4 pb-3 text-sm text-white/90 space-y-2 border-t border-white/10">
            <template x-for="categoria in categorias" :key="categoria.id_categoria">
              <a href="#" @click.prevent="applyFilter('categoria', categoria.id_categoria)" class="block hover:text-[#C77DFF]" x-text="categoria.nombre_categoria"></a>
            </template>
          </div>
        </div>

        <!-- Material -->
        <div x-data="{ open: false }" class="bg-[#2a1653] rounded-lg border border-white/10">
          <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-white/90 font-semibold">
            <span>Material</span>
            <span :class="open ? 'rotate-180 text-[#C77DFF]' : ''" class="transform transition-transform text-white/70">▾</span>
          </button>
          <div x-show="open" x-transition class="px-4 pb-3 text-sm text-white/90 space-y-2 border-t border-white/10">
            <template x-for="material in materiales" :key="material.id_material">
              <a href="#" @click.prevent="applyFilter('material', material.id_material)" class="block hover:text-[#C77DFF]" x-text="material.nombre_material"></a>
            </template>
          </div>
        </div>

        <!-- Precio -->
        <div x-data="{ open: false }" class="bg-[#2a1653] rounded-lg border border-white/10">
          <button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 text-white/90 font-semibold">
            <span>Precio</span>
            <span :class="open ? 'rotate-180 text-[#C77DFF]' : ''" class="transform transition-transform text-white/70">▾</span>
          </button>
          <div x-show="open" x-transition class="px-4 pb-3 text-sm text-white/90 space-y-2 border-t border-white/10">
            <a href="#" @click.prevent="applyFilter('precio', '30-70')" class="block hover:text-[#C77DFF]">S/. 30 - S/. 70</a>
            <a href="#" @click.prevent="applyFilter('precio', '71-111')" class="block hover:text-[#C77DFF]">S/. 71 - S/. 111</a>
            <a href="#" @click.prevent="applyFilter('precio', '112-152')" class="block hover:text-[#C77DFF]">S/. 112 - S/. 152</a>
            <a href="#" @click.prevent="applyFilter('precio', '152-9999')" class="block hover:text-[#C77DFF]">Más de S/. 152</a>
          </div>
        </div>

        <div class="mt-4">
          <button id="resetBtn" @click="resetFilters" class="w-full bg-[#C77DFF] text-white py-2 rounded-lg text-sm font-semibold hover:bg-[#9D4EDD] transition duration-200">
            Resetear Filtros
          </button>
         </div>
      </aside>


      <!-- SECCIÓN DE PRODUCTOS -->
      <section class="md:col-span-3 w-full px-4">
        <h2 class="text-center text-white font-bold text-2xl mb-6">Catálogo de Productos</h2>
        <div id="productos-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php
          // Aquí se generan los productos usando PHP
          if (!empty($productos_pagina)) {
            foreach ($productos_pagina as $producto) {
              include __DIR__ . '/../components/Card_product.php'; // Esto incluye la tarjeta de cada producto
            }
          } else {
            echo '<p class="text-white text-center col-span-full">No hay productos disponibles.</p>';
          }
          ?>
        </div>

        <!-- Paginación -->
        <div id="paginacion" class="flex justify-center items-center gap-2 mt-6">
          <?php if ($pagina_actual > 1): ?>
            <a href="/MAYWATEXTIL/pages/productos.php?pagina=<?= $pagina_actual - 1 ?>" class="w-8 h-8 flex items-center justify-center text-sm rounded-md bg-[#2a1653] border border-white/10 text-white hover:bg-[#7b3fc4]">‹</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="/MAYWATEXTIL/pages/productos.php?pagina=<?= $i ?>" class="w-8 h-8 flex items-center justify-center text-sm rounded-md <?= $i == $pagina_actual ? 'bg-[#9D4EDD] text-white' : 'bg-[#2a1653] border border-white/10 text-white hover:bg-[#7b3fc4]' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>

          <?php if ($pagina_actual < $total_paginas): ?>
            <a href="/MAYWATEXTIL/pages/productos.php?pagina=<?= $pagina_actual + 1 ?>" class="w-8 h-8 flex items-center justify-center text-sm rounded-md bg-[#2a1653] border border-white/10 text-white hover:bg-[#7b3fc4]">›</a>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
  function filters() {
    return {
      categorias: [],  // Lista de categorías
      materiales: [],   // Lista de materiales
      productos: [],    // Lista de productos
      categoriaSeleccionada: '',
      materialSeleccionado: '',
      precioSeleccionado: '',
      busqueda: '', // Para almacenar el término de búsqueda

      // Función para obtener productos con filtros y búsqueda
      async fetchFiltersAndProducts() {
        // CORREGIDO: Ahora el parámetro `buscar` se envía al backend
        const response = await fetch(`../api/admin/productos/filtrar.php?categoria=${this.categoriaSeleccionada}&material=${this.materialSeleccionado}&precio=${this.precioSeleccionado}&buscar=${this.busqueda}`);
        const data = await response.json();

        // Si hay categorías y materiales, los cargamos en el frontend
        if (data.categorias) {
          this.categorias = data.categorias;
        }
        if (data.materiales) {
          this.materiales = data.materiales;
        }

        // Cargar productos
        if (data.productos) {
          this.updateProductGrid(data.productos);
        }
      },

      // Función para actualizar el grid de productos
      updateProductGrid(productos) {
        let productosHTML = '';
        productos.forEach(producto => {
          // Aseguramos que el precio es un número antes de usar toFixed
          const precio = parseFloat(producto.precio);

          // Verificar si el precio es un número válido
          const precioFormateado = !isNaN(precio) ? precio.toFixed(2) : 'N/A'; // Si no es número, mostrar 'N/A'

          productosHTML += `
            <div class="block bg-[#5a2d82] rounded-2xl overflow-hidden shadow-lg border border-white/10 
                        hover:scale-[1.03] transition-transform duration-200">
              <a href="/MAYWATEXTIL/pages/seleccion.php?id=${producto.id_producto}">
                <div class="bg-white flex items-center justify-center p-4">
                  <img 
                    src="${producto.foto_url ? producto.foto_url : '/MAYWATEXTIL/public/assets/img/no-image.png'}" 
                    alt="${producto.nombre_producto}" 
                    class="w-full h-56 object-contain rounded-xl">
                </div>
              </a>
              <div class="p-4 flex-1 flex flex-col items-center text-center">
                <h3 class="text-white font-bold text-lg leading-tight mb-1">
                  ${producto.nombre_producto}
                </h3>
                <p class="text-white/80 text-sm leading-snug">
                  ${producto.descripcion_corta}
                </p>
              </div>
              <div class="bg-[#6a1b9a] px-5 py-3 flex items-center justify-between rounded-b-2xl shadow-inner">
                <span class="text-white font-semibold text-lg">
                  S/. ${precioFormateado}
                </span>
                <button
                  data-product
                  data-id="${producto.id_producto}"
                  data-nombre="${producto.nombre_producto}"
                  data-categoria="${producto.categoria || ''}"
                  data-stock="${producto.stock || 0}"
                  data-precio="${producto.precio}"
                  data-foto="${producto.foto_url}"
                  class="px-5 py-2 rounded-full text-sm font-semibold 
                        bg-gradient-to-r from-[#9D4EDD] to-[#C77DFF] text-white 
                        hover:scale-105 transition-transform">
                  Agregar
                </button>
              </div>
            </div>
          `;
        });

        // Si no hay productos, mostramos un mensaje
        if (productos.length === 0) {
          productosHTML = '<p class="text-white text-center col-span-full">No se encontraron productos con el término de búsqueda o filtros aplicados.</p>';
        }

        // Actualiza el grid de productos en el DOM
        document.getElementById("productos-grid").innerHTML = productosHTML;
        
        // Ocultar paginación si hay una búsqueda activa
        const paginacion = document.getElementById("paginacion");
        if (paginacion) {
          paginacion.style.display = this.busqueda || this.categoriaSeleccionada || this.materialSeleccionado || this.precioSeleccionado ? 'none' : 'flex';
        }
      },

      // Función para aplicar el filtro
      applyFilter(type, value) {
        // Establecemos el valor del filtro en la variable correspondiente
        if (type === 'categoria') {
          // Si el valor ya está seleccionado, lo deseleccionamos
          this.categoriaSeleccionada = this.categoriaSeleccionada === value ? '' : value;
        } else if (type === 'material') {
          // Si el valor ya está seleccionado, lo deseleccionamos
          this.materialSeleccionado = this.materialSeleccionado === value ? '' : value;
        } else if (type === 'precio') {
          // Si el valor ya está seleccionado, lo deseleccionamos
          this.precioSeleccionado = this.precioSeleccionado === value ? '' : value;
        }

        // Llamamos a la función para actualizar los productos con los filtros
        this.fetchFiltersAndProducts();
      },

      // Función para capturar el término de búsqueda y actualizar los productos
      handleSearch() {
        this.fetchFiltersAndProducts(); // Actualizar productos según la búsqueda
      },


      // Resetear los filtros
      resetFilters() {
        this.categoriaSeleccionada = '';
        this.materialSeleccionado = '';
        this.precioSeleccionado = '';
        this.busqueda = ''; // Limpiar la búsqueda también
        
        // Limpiar el input de búsqueda visualmente
        const buscadorInput = document.getElementById('buscador');
        if (buscadorInput) {
            buscadorInput.value = '';
        }

        this.fetchFiltersAndProducts(); // Volver a cargar los productos sin filtros
      },

      init() {
        this.fetchFiltersAndProducts(); // Llamada inicial para cargar productos y filtros
      }
    };
  }
  
</script>