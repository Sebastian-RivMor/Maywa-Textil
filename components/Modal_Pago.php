<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<div id="modalPago"
     class="fixed inset-0 z-[999] hidden bg-black/60 backdrop-blur-sm flex items-center justify-center">
  <div class="relative bg-white w-full max-w-md rounded-2xl p-6 shadow-2xl text-gray-800">
    
    <button id="cerrarModalPago"
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-lg">✕</button>
            
    <div id="pagoExitoso" class="hidden text-center py-10 space-y-3">
        <p class="text-7xl">✅</p>
        <h3 class="text-2xl font-bold text-[#3ED6A6]">¡Compra Exitosa!</h3>
        <p class="text-sm text-gray-600">Gracias por tu compra. Te estamos redirigiendo a la página principal...</p>
    </div>

    <div id="contenidoModal">
        
        <div id="mensajeError" class="hidden p-3 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="font-medium">Error de validación:</span> Por favor, complete todos los campos obligatorios.
        </div>

        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-2">
            <img src="/MAYWATEXTIL/public/assets/img/shopping-basket.png" class="w-6 h-6" alt="Carrito">
            <h2 class="text-sm font-semibold text-gray-600">Checkout seguro</h2>
          </div>
          <p class="text-xs text-gray-500 font-semibold">N° Pedido <span id="numPedido" class="text-gray-700"></span></p>
        </div>

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

        <div id="formYape" class="hidden space-y-3">
          <input id="yapeCodigo" type="text" maxlength="6" placeholder="Código Yape (6 dígitos)"
                 class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:border-[#9D4EDD]">
        </div>

        <button id="btnConfirmarPago"
                class="mt-6 w-full py-3 rounded-lg bg-[#3ED6A6] text-white font-semibold text-sm hover:bg-[#33C59A] transition">
          Pagar S/.<span id="montoPago">0.00</span>
        </button>
    </div> </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modalPago");
  const cerrar = document.getElementById("cerrarModalPago");
  const formTarjeta = document.getElementById("formTarjeta");
  const formYape = document.getElementById("formYape");
  const btnPagar = document.getElementById("btnConfirmarPago");
  const montoEl = document.getElementById("montoPago");
  const numPedido = document.getElementById("numPedido");
  
  // ELEMENTOS para la redirección y mensaje de éxito/error
  const contenidoModal = document.getElementById("contenidoModal");
  const pagoExitoso = document.getElementById("pagoExitoso");
  const mensajeError = document.getElementById("mensajeError"); // Nuevo elemento

  // CAMPOS DE FORMULARIO
  const tarjetaNumero = document.getElementById("tarjetaNumero");
  const tarjetaMMYY = document.getElementById("tarjetaMMYY");
  const tarjetaCVV = document.getElementById("tarjetaCVV");
  const tarjetaNombres = document.getElementById("tarjetaNombres");
  const tarjetaApellidos = document.getElementById("tarjetaApellidos");
  const tarjetaCorreo = document.getElementById("tarjetaCorreo");
  const yapeCodigo = document.getElementById("yapeCodigo");


  /**
   * Aplica o remueve las clases de error de Tailwind a un elemento input.
   * @param {HTMLElement} element - El input a modificar.
   * @param {boolean} isError - Si se debe mostrar el error (true) o revertir (false).
   */
  function toggleErrorClass(element, isError) {
    if (element) {
        if (isError) {
            // Aplicar borde rojo para error
            element.classList.add("border-red-500");
            element.classList.remove("border-gray-300", "focus:border-[#9D4EDD]");
        } else {
            // Revertir a borde normal
            element.classList.remove("border-red-500");
            element.classList.add("border-gray-300", "focus:border-[#9D4EDD]");
        }
    }
  }

  /**
   * Resetea todos los estilos de error y oculta el mensaje general.
   */
  function resetErrors() {
    mensajeError.classList.add("hidden");
    // Lista de todos los inputs de pago
    const allInputs = [tarjetaNumero, tarjetaMMYY, tarjetaCVV, tarjetaNombres, tarjetaApellidos, tarjetaCorreo, yapeCodigo];
    allInputs.forEach(input => toggleErrorClass(input, false));
    // Restablece el mensaje de error predeterminado (en caso de que un error de servidor lo haya modificado)
    mensajeError.querySelector('span').textContent = 'Error de validación:';
    mensajeError.lastChild.textContent = ' Por favor, complete todos los campos obligatorios.';
  }

  /**
   * Valida que los campos requeridos del formulario de pago activo estén llenos y marca los errores visualmente.
   * @returns {boolean} True si la validación es exitosa, false en caso contrario.
   */
  function validarFormulario() {
    resetErrors(); // Limpia errores al inicio
    let isValid = true;
    let firstInvalidInput = null;

    const esYape = !formYape.classList.contains("hidden");
    
    // Lista de inputs requeridos y sus funciones de validación
    let requiredInputs = [];
    if (esYape) {
      requiredInputs.push({ elem: yapeCodigo, validate: (v) => v.trim() && v.trim().length === 6 });
    } else {
      requiredInputs.push({ elem: tarjetaNumero, validate: (v) => v.trim() && v.trim().length === 16 });
      requiredInputs.push({ elem: tarjetaMMYY, validate: (v) => v.trim() && v.trim().length === 5 });
      requiredInputs.push({ elem: tarjetaCVV, validate: (v) => v.trim() && v.trim().length === 3 });
      requiredInputs.push({ elem: tarjetaNombres, validate: (v) => v.trim() });
      requiredInputs.push({ elem: tarjetaApellidos, validate: (v) => v.trim() });
      requiredInputs.push({ elem: tarjetaCorreo, validate: (v) => v.trim() && /^\S+@\S+\.\S+$/.test(v) });
    }

    // Ejecutar la validación
    requiredInputs.forEach(item => {
      if (!item.validate(item.elem.value)) {
        toggleErrorClass(item.elem, true); // Marcar con borde rojo
        isValid = false;
        if (!firstInvalidInput) {
            firstInvalidInput = item.elem;
        }
      } else {
        toggleErrorClass(item.elem, false); 
      }
    });
    
    if (!isValid) {
      mensajeError.classList.remove("hidden"); // Mostrar mensaje general de error
      if (firstInvalidInput) {
        firstInvalidInput.focus(); // Enfocar el primer campo inválido
      }
    }
    
    return isValid;
  }
  
  // abrir modal desde el checkout
  window.openPagoModal = async function() {
    resetErrors(); // Limpia errores al abrir
    const carrito = await fetch('/MAYWATEXTIL/api/cart/list.php').then(r => r.json());
    const total = carrito.reduce((a,p) => a + (p.precio*p.cantidad), 0);
    montoEl.textContent = total.toFixed(2);
    numPedido.textContent = Date.now(); // id temporal, en backend se genera real
    if (contenidoModal) contenidoModal.classList.remove("hidden");
    if (pagoExitoso) pagoExitoso.classList.add("hidden");
    modal.classList.remove("hidden");
  };

  cerrar.onclick = () => { modal.classList.add("hidden"); resetErrors(); };
  modal.onclick = (e) => { if (e.target === modal) { modal.classList.add("hidden"); resetErrors(); } };

  // Limpiar errores al cambiar de método de pago
  document.getElementById("opcionTarjeta").onclick = () => {
    formTarjeta.classList.remove("hidden");
    formYape.classList.add("hidden");
    resetErrors(); 
  };
  document.getElementById("opcionYape").onclick = () => {
    formYape.classList.remove("hidden");
    formTarjeta.classList.add("hidden");
    resetErrors();
  };
  
  // Agregar listeners a los inputs para limpiar el error al escribir
  [tarjetaNumero, tarjetaMMYY, tarjetaCVV, tarjetaNombres, tarjetaApellidos, tarjetaCorreo, yapeCodigo].forEach(input => {
      // Remover el estilo de error al empezar a escribir en el input
      input.oninput = () => toggleErrorClass(input, false);
  });


  // enviar pago
  btnPagar.onclick = async () => {
    // 🛑 VALIDACIÓN VISUAL: Detiene la ejecución si los campos no son válidos
    if (!validarFormulario()) {
        return; 
    }
    
  // --- Si la validación pasa, continúa con la lógica de pago ---
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
        credentials: "include", 
        body: JSON.stringify(payload)
    });

    const text = await res.text();
    const data = JSON.parse(text);

    if (data.success) {
      resetErrors(); // Limpia los errores antes de mostrar el éxito
      // 1. Mostrar check de compra exitosa
      contenidoModal.classList.add("hidden");
      pagoExitoso.classList.remove("hidden");

      // 2. Limpiar carrito
      await fetch("/MAYWATEXTIL/api/cart/clear.php"); 
      
      // 3. Redirigir al Home después de un breve tiempo
      setTimeout(() => {
          location.href = "/MAYWATEXTIL/"; 
      }, 2000); 
      
    } else {
      // Mostrar error general de servidor
      mensajeError.querySelector('span').textContent = 'Error de Servidor:';
      mensajeError.lastChild.textContent = ' ' + (data.error || "Intente nuevamente.");
      mensajeError.classList.remove("hidden");

      // Asegurar que el contenido de pago esté visible
      contenidoModal.classList.remove("hidden");
      pagoExitoso.classList.add("hidden");
    }
  } catch (err) {
    console.error("Error en la comunicación con el servidor", err);
    mensajeError.querySelector('span').textContent = 'Error de Servidor:';
    mensajeError.lastChild.textContent = " Error interno del servidor. Intente más tarde.";
    mensajeError.classList.remove("hidden");

    // Asegurar que el contenido de pago esté visible
    contenidoModal.classList.remove("hidden");
    pagoExitoso.classList.add("hidden");
  }
};

});
</script>