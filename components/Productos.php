<!-- PANEL de productos (solo el cuadro) -->
<div class="max-w-7xl mx-auto px-4">
  <div class="rounded-[28px] shadow-2xl overflow-hidden
              bg-gradient-to-br from-[#2b134b] to-[#1a0f2e] border border-white/10">

    <!-- Header del panel -->
    <div class="px-5 md:px-8 pt-5 md:pt-7">
      <div class="flex items-center justify-between gap-4">
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
    </div>

    <!-- Cuerpo -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-5 md:px-8 pb-8 pt-6">

      <!-- Asegúrate de tener Alpine.js -->
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

        <!-- Sidebar filtros -->
        <aside class="md:col-span-1 space-y-6">

        <!-- Categoría -->
        <div x-data="{ open: true }" class="bg-[#2a1653] rounded-lg border border-white/10">
            <button @click="open = !open"
            class="w-full flex items-center justify-between px-3 py-2 text-white/90 font-semibold">
            <span>Todos los productos</span>
            <span :class="open ? 'rotate-180 text-[#C77DFF]' : ''"
                    class="transform transition-transform text-white/70">▾</span>
            </button>
            <div x-show="open" x-transition
                class="px-4 pb-3 text-sm text-white/90 space-y-2 border-t border-white/10">
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


      <!-- Catálogo (3x3) -->
      <div class="md:col-span-3">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php
            include __DIR__ . '/../components/Card_product.php';
            include __DIR__ . '/../components/Card_product.php';
            include __DIR__ . '/../components/Card_product.php';
            include __DIR__ . '/../components/Card_product.php';
            include __DIR__ . '/../components/Card_product.php';
            ?>
        
        </div>

        <!-- Paginación -->
        <div class="flex justify-center items-center gap-2 mt-6">
          <button class="w-7 h-7 text-sm rounded-md bg-[#2a1653] border border-white/10
                         text-white hover:bg-[#7b3fc4]">1</button>
          <button class="w-7 h-7 text-sm rounded-md bg-[#9D4EDD] text-white">2</button>
          <button class="w-7 h-7 text-sm rounded-md bg-[#2a1653] border border-white/10
                         text-white hover:bg-[#7b3fc4]">3</button>
        </div>
      </div>
    </div>
  </div>
</div>
