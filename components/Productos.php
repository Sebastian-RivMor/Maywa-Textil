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
<div class="max-w-7xl mx-auto px-4">
  <div class="rounded-[28px] shadow-2xl overflow-hidden bg-gradient-to-br from-[#2b134b] to-[#1a0f2e] border border-white/10">

    <!-- Header -->
    <div class="px-5 md:px-8 pt-5 md:pt-7 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <h2 class="text-white text-xl md:text-2xl font-bold">Todos los productos</h2>
      <!-- Buscador -->
      <div class="flex items-center bg-[#2a1653]/90 border border-white/10 rounded-full pl-4">
        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round"
             d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 110-15 7.5 7.5 0 010 15z"/></svg>
        <input type="text" placeholder="Ingrese nombre del producto"
               class="w-48 md:w-80 bg-transparent text-white placeholder-white/70 px-3 py-2 focus:outline-none">
        <button class="ml-2 mr-1 px-4 py-2 text-sm font-semibold rounded-full
                       bg-[#9D4EDD] hover:bg-[#7b3fc4] text-white">Buscar</button>
      </div>
    </div>

    <!-- CUERPO -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-5 md:px-8 pb-8 pt-6">

      <!-- SIDEBAR FILTROS -->
      <aside class="md:col-span-1 space-y-6">
        <div x-data="{ open: true }" class="bg-[#2a1653] rounded-lg border border-white/10">
          <button @click="open = !open"
            class="w-full flex items-center justify-between px-3 py-2 text-white/90 font-semibold">
            <span>Categorías</span>
            <span :class="open ? 'rotate-180 text-[#C77DFF]' : ''"
              class="transform transition-transform text-white/70">▾</span>
          </button>
          <div x-show="open" x-transition class="px-4 pb-3 text-sm text-white/90 space-y-2 border-t border-white/10">
            <a href="#" class="block hover:text-[#C77DFF]">Chompas</a>
            <a href="#" class="block hover:text-[#C77DFF]">Ponchos</a>
            <a href="#" class="block hover:text-[#C77DFF]">Carteras</a>
            <a href="#" class="block hover:text-[#C77DFF]">Bufandas</a>
            <a href="#" class="block hover:text-[#C77DFF]">Casacas</a>
          </div>
        </div>
        <!-- Precio -->
        <div x-data="{ open: false }" class="bg-[#2a1653] rounded-lg border border-white/10">
            <button @click="open = !open"
            class="w-full flex items-center justify-between px-3 py-2 text-white/90 font-semibold">
            <span>Precio</span>
            <span :class="open ? 'rotate-180 text-[#C77DFF]' : ''"
                    class="transform transition-transform text-white/70">▾</span>
            </button>
            <div x-show="open" x-transition
                class="px-4 pb-3 text-sm text-white/90 space-y-2 border-t border-white/10">
            <a href="#" class="block hover:text-[#C77DFF]">Todos los precios</a>
            <a href="#" class="block hover:text-[#C77DFF]">S/. 30 - S/. 70</a>
            <a href="#" class="block hover:text-[#C77DFF]">S/. 71 - S/. 111</a>
            <a href="#" class="block hover:text-[#C77DFF]">S/. 112 - S/. 152</a>
            <a href="#" class="block hover:text-[#C77DFF]">Más de S/. 152</a>
            </div>
        </div>
        <!-- Material -->
        <div x-data="{ open: false }" class="bg-[#2a1653] rounded-lg border border-white/10">
            <button @click="open = !open"
            class="w-full flex items-center justify-between px-3 py-2 text-white/90 font-semibold">
            <span>Material</span>
            <span :class="open ? 'rotate-180 text-[#C77DFF]' : ''"
                    class="transform transition-transform text-white/70">▾</span>
            </button>
            <div x-show="open" x-transition
                class="px-4 pb-3 text-sm text-white/90 space-y-2 border-t border-white/10">
            <a href="#" class="block hover:text-[#C77DFF]">Todos los materiales</a>
            <a href="#" class="block hover:text-[#C77DFF]">Alpaca</a>
            <a href="#" class="block hover:text-[#C77DFF]">Seda</a>
            <a href="#" class="block hover:text-[#C77DFF]">Lana</a>
            </div>
        </div>
      </aside>

      <!-- SECCIÓN DE PRODUCTOS -->
      <section class="md:col-span-3 w-full px-4">
        <h2 class="text-center text-white font-bold text-2xl mb-6">Catálogo de Productos</h2>
        <div id="productos-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php
          if (!empty($productos_pagina)) {
            foreach ($productos_pagina as $producto) {
              include __DIR__ . '/../components/Card_product.php';
            }
          } else {
            echo '<p class="text-white text-center col-span-full">No hay productos disponibles.</p>';
          }
          ?>
        </div>

        <div id="paginacion" class="flex justify-center items-center gap-2 mt-6">
          <?php if ($pagina_actual > 1): ?>
            <a href="/MAYWATEXTIL/pages/productos.php?pagina=<?= $pagina_actual - 1 ?>"
              class="w-8 h-8 flex items-center justify-center text-sm rounded-md bg-[#2a1653] border border-white/10 text-white hover:bg-[#7b3fc4]">‹</a>
          <?php endif; ?>

          <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="/MAYWATEXTIL/pages/productos.php?pagina=<?= $i ?>"
              class="w-8 h-8 flex items-center justify-center text-sm rounded-md 
              <?= $i == $pagina_actual ? 'bg-[#9D4EDD] text-white' : 'bg-[#2a1653] border border-white/10 text-white hover:bg-[#7b3fc4]' ?>">
              <?= $i ?>
            </a>
          <?php endfor; ?>

          <?php if ($pagina_actual < $total_paginas): ?>
            <a href="/MAYWATEXTIL/pages/productos.php?pagina=<?= $pagina_actual + 1 ?>"
              class="w-8 h-8 flex items-center justify-center text-sm rounded-md bg-[#2a1653] border border-white/10 text-white hover:bg-[#7b3fc4]">›</a>
          <?php endif; ?>
        </div>
      </section>
    </div>
  </div>
</div>
