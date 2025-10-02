<!-- Sección de contacto -->
<section class="py-16 px-4 bg-gradient-to-b from-[#0F0423] to-[#46386F] text-white">
  <div class="max-w-3xl mx-auto text-center mb-10">
    <h2 class="text-3xl font-bold mb-4">Contáctanos</h2>
    <p class="text-sm md:text-base">
      ¿Quieres más información, hacer un pedido especial o colaborar con nosotros? Escríbenos y te responderemos pronto.
    </p>
  </div>

  <!-- Formulario -->
  <div class="max-w-3xl mx-auto bg-gradient-to-br from-[#7B2CBF] to-[#5A189A] p-6 rounded-3xl shadow-xl">
    <form action="#" method="POST" class="space-y-5 text-left text-white font-medium">

      <!-- Nombre y Correo -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label for="nombre" class="block mb-1">Nombre</label>
          <input type="text" id="nombre" name="nombre"
                 class="w-full rounded-full px-4 py-2 bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Tu nombre">
        </div>
        <div>
          <label for="correo" class="block mb-1">Correo</label>
          <input type="email" id="correo" name="correo"
                 class="w-full rounded-full px-4 py-2 bg-white/20 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white" placeholder="Tu correo">
        </div>
      </div>

      <!-- Mensaje -->
      <div>
        <label for="mensaje" class="block mb-1">Mensaje</label>
        <textarea id="mensaje" name="mensaje" rows="4"
                  class="w-full rounded-lg px-4 py-2 bg-white/20 text-white placeholder-white/70 resize-none focus:outline-none focus:ring-2 focus:ring-white"
                  placeholder="Escribe tu mensaje..."></textarea>
      </div>

      <!-- Botón -->
      <div class="text-center">
        <button type="submit"
                class="bg-gradient-to-r from-[#7B2CBF] to-[#C77DFF] hover:from-[#5A189A] hover:to-[#E0AAFF] transition px-6 py-2 rounded-full font-semibold flex items-center justify-center gap-2 mx-auto text-white shadow-md">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
            <path d="M2.01 21L23 12 2.01 3v7l15 2-15 2z" />
          </svg>
          Enviar mensaje
        </button>
      </div>
    </form>
  </div>
</section>
