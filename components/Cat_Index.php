<section id="categorias" class="w-[1440px] h-[522px] mx-auto flex items-center justify-center">
  <!-- Contenedor interno -->
  <div class="w-[1100px] h-[422px]">
    <!-- Cabecera -->
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl md:text-2xl font-bold text-white">
        Descubre por categoría
      </h2>

      <a href="/MAYWATEXTIL/public/index.php?page=productos"
         class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-sm font-medium text-white ring-1 ring-[#C2B7DF] hover:bg-[#E8C1FF]/30">
        Ver Todo →
      </a>
    </div>

    <!-- Grid de categorías -->
    <div class="grid grid-cols-4 gap-6 h-[calc(422px-3rem)]"> 
      <!-- 👆 grid-cols-4 asegura que 4 items ocupen todo el ancho -->

      <!-- Item -->
      <a class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/poncho3.png" alt="Chompas"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Ponchos</p>
      </a>

      <a class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/sueter.png" alt="Sueter"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Chompas y suéteres</p>
      </a>

      <a class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/chaleco.png" alt="Chalecos"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Chalecos</p>
      </a>

      <a class="group text-center">
        <div class="rounded-[30px] overflow-hidden shadow-md ring-1 ring-black/10 h-[300px]">
          <img src="assets/img/faldas.png" alt="Faldas"
               class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-[1.03]" />
        </div>
        <p class="mt-3 text-base font-medium text-white">Faldas</p>
      </a>
    </div>
  </div>
</section>
