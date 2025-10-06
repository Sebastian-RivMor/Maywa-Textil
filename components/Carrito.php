<!-- CARRITO FLOTANTE CON PANEL -->
<div x-data="cartComponent()" 
     x-init="loadCart()" 
     class="fixed bottom-6 right-6 z-50 select-none">

  <!-- Botón flotante -->
  <button @click="open = !open"
    class="relative w-16 h-16 rounded-full bg-gradient-to-tr from-[#9D4EDD] to-[#7b3fc4] 
           hover:from-[#7b3fc4] hover:to-[#9D4EDD] flex items-center justify-center 
           shadow-[0_0_20px_rgba(157,78,221,0.4)] transition-all duration-300 
           border border-white/20 backdrop-blur-sm">

    <img src="/MAYWATEXTIL/public/assets/icons/carrito.svg" 
         alt="Carrito" 
         class="w-8 h-8 invert opacity-90">

    <span x-text="cart.length"
          class="absolute -top-1.5 -right-1.5 bg-white text-[#9D4EDD] text-xs font-bold 
                 w-5 h-5 rounded-full flex items-center justify-center shadow-md">
    </span>
  </button>

  <!-- Panel flotante -->
  <div x-show="open"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 translate-y-2"
       x-transition:enter-end="opacity-100 translate-y-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100 translate-y-0"
       x-transition:leave-end="opacity-0 translate-y-2"
       class="absolute bottom-20 right-0 w-80 bg-[#2a1653]/95 backdrop-blur-xl 
              border border-white/10 rounded-2xl shadow-2xl text-white p-4 origin-bottom-right">

    <!-- Si está vacío -->
    <template x-if="cart.length === 0">
      <div class="flex flex-col items-center justify-center py-8 text-white/70 text-sm">
        <img src="/MAYWATEXTIL/public/assets/icons/carrito.svg" 
             class="w-10 h-10 opacity-70 mb-2 invert" alt="Carrito vacío">
        <p>Tu carrito está vacío</p>
      </div>
    </template>

    <!-- Si hay productos -->
    <div x-show="cart.length > 0" class="max-h-64 overflow-y-auto space-y-3 custom-scroll">
        <template x-for="item in cart" :key="item.id">
            <div class="bg-[#3a0ca3]/10 rounded-lg p-3 border border-white/10 hover:bg-[#3a0ca3]/20 transition">
            <div class="flex items-center gap-3">
                <img :src="item.foto" class="w-12 h-12 object-cover rounded-md border border-white/10">
                <div class="flex-1">
                <p class="text-sm font-semibold truncate" x-text="item.nombre"></p>
                <p class="text-xs text-white/60">
                    <span x-text="'Categoría: ' + (item.categoria || 'N/A')"></span><br>
                    <span x-text="'Stock: ' + item.stock"></span>
                </p>
                <p class="text-sm font-bold mt-1">S/. <span x-text="item.precio"></span></p>
                </div>
                <div class="flex flex-col items-center gap-1">
                <button @click="updateQty(item.id, 'add')" class="text-[#C77DFF] font-bold text-lg leading-none">+</button>
                <span class="text-sm" x-text="item.cantidad"></span>
                <button @click="updateQty(item.id, 'subtract')" class="text-[#C77DFF] font-bold text-lg leading-none">−</button>
                <button @click="removeItem(item.id)" class="text-red-400 text-xs mt-1 hover:text-red-600">✕</button>
                </div>
            </div>
            </div>
        </template>
    </div>


    <!-- Total -->
    <template x-if="cart.length > 0">
      <div class="mt-4 border-t border-white/10 pt-3">
        <div class="flex justify-between items-center mb-2">
          <p class="text-sm font-semibold">Total:</p>
          <p class="text-lg font-bold text-[#C77DFF]">S/. <span x-text="total()"></span></p>
        </div>
        <button class="w-full py-2 rounded-full bg-[#9D4EDD] hover:bg-[#7b3fc4] font-semibold text-sm transition">
          Finalizar compra
        </button>
      </div>
    </template>
  </div>
</div>
