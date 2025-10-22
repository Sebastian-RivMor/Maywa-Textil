<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!-- MODAL DE PAGO -->
<div id="modalPago"
     class="fixed inset-0 z-[999] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center">
  <div class="relative bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl text-gray-800">
    
    <!-- Botón cerrar -->
    <button id="cerrarModalPago"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-lg">✕</button>

    <!-- Cabecera -->
    <div class="flex items-center justify-between mb-3">
      <div class="flex items-center gap-2">
        <img src="/MAYWATEXTIL/public/assets/img/shopping-basket.png" class="w-6 h-6" alt="Carrito">
        <h2 class="text-sm font-semibold text-gray-600">Checkout seguro</h2>
      </div>
      <p class="text-xs text-gray-500 font-semibold">N° Pedido <span id="numPedido" class="text-gray-700"></span></p>
    </div>

    <!-- Opciones -->
    <div id="opcionesPago" class="grid grid-cols-3 gap-2 mb-6">
      <button id="opcionTarjeta"
              class="option-pago border-2 border-[#9D4EDD] bg-[#F8F5FF] rounded-lg py-3 flex flex-col items-center gap-1">
        <img src="/MAYWATEXTIL/public/assets/img/credit-card.png" class="h-6" alt="Tarjeta">
        <span class="text-xs font-semibold text-[#5A189A]">Tarjeta</span>
      </button>
      <button id="opcionYape"
              class="option-pago border border-gray-200 rounded-lg py-3 flex flex-col items-center gap-1 hover:border-[#9D4EDD]">
        <img src="/MAYWATEXTIL/public/assets/img/yape.png" class="h-6" alt="Yape">
        <span class="text-xs font-medium text-gray-700">Yape</span>
      </button>
    </div>

    <!-- Formulario tarjeta -->
    <div id="formTarjeta" class="space-y-3">
      <input id="tarjetaNumero" type="text" maxlength="16" placeholder="Número de tarjeta"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
      <div class="flex gap-2">
        <input id="tarjetaMMYY" type="text" maxlength="5" placeholder="MM/YY"
               class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
        <input id="tarjetaCVV" type="text" maxlength="3" placeholder="CVV"
               class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
      </div>
      <div class="flex gap-2">
        <input id="tarjetaNombres" type="text" placeholder="Nombres"
               class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
        <input id="tarjetaApellidos" type="text" placeholder="Apellidos"
               class="w-1/2 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
      </div>
      <input id="tarjetaCorreo" type="email" placeholder="Correo electrónico"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
    </div>

    <!-- Formulario Yape -->
    <div id="formYape" class="hidden space-y-3">
      <input id="yapeCodigo" type="text" maxlength="6" placeholder="Código Yape (6 dígitos)"
             class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
    </div>

    <!-- Botón pagar -->
    <button id="btnConfirmarPago"
            class="mt-6 w-full py-3 rounded-lg bg-[#3ED6A6] text-white font-semibold text-sm hover:bg-[#33C59A] transition">
      Pagar S/.<span id="montoPago">0.00</span>
    </button>

  </div>
</div>

<!-- Script del modal -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modalPago");
  const cerrar = document.getElementById("cerrarModalPago");
  const formTarjeta = document.getElementById("formTarjeta");
  const formYape = document.getElementById("formYape");
  const btnPagar = document.getElementById("btnConfirmarPago");
  const montoEl = document.getElementById("montoPago");
  const numPedido = document.getElementById("numPedido");

  // abrir modal desde el checkout
  window.openPagoModal = async function() {
    const carrito = await fetch('/MAYWATEXTIL/api/cart/list.php').then(r => r.json());
    const total = carrito.reduce((a,p) => a + (p.precio*p.cantidad), 0);
    montoEl.textContent = total.toFixed(2);
    numPedido.textContent = Date.now(); // id temporal, en backend se genera real
    modal.classList.remove("hidden");
  };

  cerrar.onclick = () => modal.classList.add("hidden");
  modal.onclick = (e) => { if (e.target === modal) modal.classList.add("hidden"); };

  // cambiar método de pago
  document.getElementById("opcionTarjeta").onclick = () => {
    formTarjeta.classList.remove("hidden");
    formYape.classList.add("hidden");
  };
  document.getElementById("opcionYape").onclick = () => {
    formYape.classList.remove("hidden");
    formTarjeta.classList.add("hidden");
  };

  // enviar pago
  btnPagar.onclick = async () => {
  const carrito = await fetch('/MAYWATEXTIL/api/cart/list.php').then(r => r.json());
  const direccion = document.getElementById("direccion").value;
  const id_departamento = document.getElementById("departamento").value;
  const total = carrito.reduce((a, p) => a + (p.precio * p.cantidad), 0);
  const metodo = !formYape.classList.contains("hidden") ? "yape" : "tarjeta de credito";

    // Convertir los productos del carrito al formato que el backend espera
    const productosFormateados = carrito.map(p => ({
    id_producto: p.id_producto || p.id || 0,
    cantidad: p.cantidad || 1,
    precio: p.precio || 0
    }));

    const payload = { metodo_pago: metodo, total, direccion, id_departamento, productos: productosFormateados };


  try {
    const res = await fetch("/MAYWATEXTIL/api/pago/procesar.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        credentials: "include", // 🔥 Esto es CLAVE para enviar la sesión
        body: JSON.stringify(payload)
    });

    const text = await res.text();
    const data = JSON.parse(text);

    if (data.success) {
      alert("✅ " + data.msg);
      modal.classList.add("hidden");

      // ✅ Limpiar carrito (opcionalmente, recargar la vista del carrito)
      await fetch("/MAYWATEXTIL/api/cart/clear.php"); 
      location.reload(); // refresca la página o actualiza contador
    } else {
      alert("❌ Error en el pago: " + (data.error || "Intente nuevamente."));
    }
  } catch (err) {
    console.error("Error en la comunicación con el servidor", err);
    alert("⚠️ Error interno del servidor.");
  }
};

});
</script>
