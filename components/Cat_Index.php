<section class="w-[1440px] h-[522px] mx-auto flex items-center justify-center">
  <!-- Contenedor interno -->
  <div class="w-[1100px] h-[422px]">
    <!-- Cabecera -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl md:text-2xl font-bold text-white">
        Descubre por categoría
      </h2>

      <a href="/categorias"
         class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium text-white ring-1 ring-[#C2B7DF] hover:bg-[#E8C1FF]/30">
        Ver Todo →
      </a>
    </div>

    <!-- Grid de categorías -->
    <div class="grid grid-cols-4 gap-6 h-[calc(422px-3rem)]"> 
      <!-- 👆 grid-cols-4 asegura que 4 items ocupen todo el ancho -->

      <!-- Item -->
      <a href="/categorias/chompas" class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/chompas.png" alt="Chompas"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Chompas</p>
      </a>

      <a href="/categorias/ponchos" class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/ponchos.png" alt="Ponchos"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Ponchos</p>
      </a>

      <a href="/categorias/carteras" class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/cartera1.png" alt="Carteras"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Carteras</p>
      </a>

      <a href="/categorias/carteras" class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/cartera2.png" alt="Carteras"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Carteras</p>
      </a>
    </div>
  </div>
</section>
