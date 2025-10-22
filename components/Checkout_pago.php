<?php
require_once __DIR__ . '/../config/db.php';

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// 🔹 Obtener departamentos
$stmt = $pdo->query("SELECT id_departamento, nombre_departamento FROM tb_departamento ORDER BY nombre_departamento ASC");
$departamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔹 Productos del carrito
$carrito = $_SESSION['carrito'] ?? [];

// 🔹 Total del carrito
$total = $_SESSION['total_carrito'] ?? 0;
?>

<section class="min-h-screen py-15 px-6">
  <div class="max-w-5xl mx-auto bg-white/10 backdrop-blur-lg border border-white/20 shadow-2xl rounded-2xl p-8 text-white">

    <h1 class="text-3xl font-bold text-center mb-10">Finalizar Compra</h1>

    <!-- Lista de productos -->
    <?php if (!empty($carrito)): ?>
      <div id="checkout-lista" class="mb-8">
        <h3 class="text-lg font-semibold mb-3 text-[#E0AAFF]">Productos en tu carrito</h3>

        <div class="max-h-60 overflow-y-auto space-y-3 pr-2 custom-scroll">
          <?php foreach ($carrito as $item): ?>
            <div class="flex justify-between items-center bg-white/10 p-3 rounded-lg border border-white/10">
              <div class="flex items-center gap-3">
                <img src="<?= htmlspecialchars($item['foto'] ?? '/MAYWATEXTIL/public/assets/img/no-image.png') ?>"
                     alt="<?= htmlspecialchars($item['nombre']) ?>"
                     class="w-12 h-12 object-cover rounded-md border border-white/10">
                <div>
                  <p class="text-sm font-semibold"><?= htmlspecialchars($item['nombre']) ?></p>
                  <p class="text-xs text-white/60">S/. <?= number_format($item['precio'], 2) ?></p>
                </div>
              </div>

              <div class="flex items-center gap-2">
                <button class="bg-[#3a0ca3]/40 hover:bg-[#9D4EDD] text-white font-bold w-6 h-6 rounded-md"
                        onclick="updateQty('<?= $item['id'] ?>', 'subtract')">−</button>
                <span class="text-sm font-semibold"><?= intval($item['cantidad'] ?? 1) ?></span>
                <button class="bg-[#3a0ca3]/40 hover:bg-[#9D4EDD] text-white font-bold w-6 h-6 rounded-md"
                        onclick="updateQty('<?= $item['id'] ?>', 'add')">+</button>
                <button class="text-red-400 hover:text-red-600 ml-2"
                        onclick="removeItem('<?= $item['id'] ?>')">✕</button>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php else: ?>
      <p class="text-center text-white/80 mb-8">Tu carrito está vacío 🛒</p>
    <?php endif; ?>

    <!-- Combo: Departamento -->
    <div class="mb-6">
      <label for="departamento" class="block text-sm font-medium mb-2 text-white/90">Departamento</label>
      <select id="departamento" name="departamento" required
        class="w-full px-4 py-2 rounded-lg border border-white/20 bg-white text-[#2b134b] font-medium
               focus:outline-none focus:ring-2 focus:ring-[#9D4EDD] focus:border-transparent transition-colors duration-200">
        <option value="">Seleccione un departamento</option>
        <?php foreach ($departamentos as $d): ?>
          <option value="<?= htmlspecialchars($d['id_departamento']) ?>">
            <?= htmlspecialchars($d['nombre_departamento']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Input: Dirección -->
    <div class="mb-6">
      <label for="direccion" class="block text-sm font-medium mb-2">Dirección de envío</label>
      <input type="text" id="direccion" name="direccion" required
             placeholder="Ej. Av. Los Olivos 234 - Lima"
             class="w-full px-4 py-2 rounded-lg border border-white/20 bg-white/10 placeholder-white/60 text-white focus:outline-none focus:border-[#9D4EDD]" />
    </div>

    <!-- Total -->
    <div class="flex justify-between items-center mb-6">
      <span class="text-lg font-medium">Total a pagar:</span>
      <span class="text-2xl font-bold text-[#E0AAFF]" id="checkout-total">
        S/. <?= number_format($total, 2) ?>
      </span>
    </div>

    <!-- Botón: Pagar -->
    <div class="text-center">
      <button onclick="openPagoModal()"
              class="w-full py-3 rounded-full bg-gradient-to-r from-[#9D4EDD] to-[#7B2CBF] hover:from-[#7B2CBF] hover:to-[#5A189A]
                    font-semibold text-white text-lg shadow-md transition">
        Pagar
      </button>
    </div>

    <?php include __DIR__ . '/Modal_Pago.php'; ?>


  </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const lista = document.querySelector("#checkout-lista");
  const totalEl = document.querySelector("#checkout-total");

  // --- FUNCIÓN PRINCIPAL: CARGA EL CARRITO ---
  async function cargarCheckout() {
    try {
      const res = await fetch("/MAYWATEXTIL/api/cart/list.php");
      if (!res.ok) throw new Error("Error al obtener carrito");
      const data = await res.json();

      // Si está vacío
      if (!data || data.length === 0) {
        lista.innerHTML = `
          <p class="text-center text-white/80 py-6 text-sm">
            🛒 Tu carrito está vacío.
          </p>`;
        totalEl.textContent = "S/. 0.00";
        return;
      }

      // Limpiar lista
      lista.innerHTML = "";

      let total = 0;

      data.forEach((item) => {
        const precio = parseFloat(item.precio) || 0;
        const cantidad = parseInt(item.cantidad) || 1;
        const subtotal = precio * cantidad;
        total += subtotal;

        lista.insertAdjacentHTML(
          "beforeend",
          `
          <div class="flex justify-between items-center bg-white/10 p-3 rounded-lg border border-white/10 mb-2">
            <div class="flex items-center gap-3">
              <img src="${item.foto}" alt="${item.nombre}" 
                   class="w-12 h-12 object-cover rounded-md border border-white/10">
              <div>
                <p class="text-sm font-semibold">${item.nombre}</p>
                <p class="text-xs text-white/60">S/. ${precio.toFixed(2)}</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button class="btn-cantidad bg-[#3a0ca3]/40 hover:bg-[#9D4EDD] text-white font-bold w-6 h-6 rounded-md"
                      data-id="${item.id}" data-action="subtract">−</button>
              <span class="text-sm font-semibold">${cantidad}</span>
              <button class="btn-cantidad bg-[#3a0ca3]/40 hover:bg-[#9D4EDD] text-white font-bold w-6 h-6 rounded-md"
                      data-id="${item.id}" data-action="add">+</button>
              <button class="btn-eliminar text-red-400 hover:text-red-600 text-lg ml-2"
                      data-id="${item.id}">✕</button>
            </div>
          </div>
        `
        );
      });

      totalEl.textContent = `S/. ${total.toFixed(2)}`;
    } catch (error) {
      console.error("Error cargando checkout:", error);
    }
  }

  // --- ACTUALIZAR CANTIDAD ---
  async function cambiarCantidad(id, action) {
    try {
      const res = await fetch("/MAYWATEXTIL/api/cart/update.php", {
        method: "POST",
        body: new URLSearchParams({ id, action }),
      });
      if (!res.ok) throw new Error("Error al actualizar cantidad");
      await res.json();
      window.dispatchEvent(new CustomEvent("refresh-cart")); // sincroniza carrito flotante
      await cargarCheckout(); // refresca visualmente la lista
    } catch (err) {
      console.error("Error cambiando cantidad:", err);
    }
  }

  // --- ELIMINAR PRODUCTO ---
  async function eliminarProducto(id) {
    try {
      const res = await fetch("/MAYWATEXTIL/api/cart/remove.php", {
        method: "POST",
        body: new URLSearchParams({ id }),
      });
      if (!res.ok) throw new Error("Error al eliminar producto");
      await res.json();
      window.dispatchEvent(new CustomEvent("refresh-cart"));
      await cargarCheckout();
    } catch (err) {
      console.error("Error eliminando producto:", err);
    }
  }

  // --- DELEGACIÓN DE EVENTOS ---
  lista.addEventListener("click", (e) => {
    const btnCantidad = e.target.closest(".btn-cantidad");
    const btnEliminar = e.target.closest(".btn-eliminar");

    if (btnCantidad) {
      const id = btnCantidad.dataset.id;
      const action = btnCantidad.dataset.action;
      cambiarCantidad(id, action);
    }

    if (btnEliminar) {
      const id = btnEliminar.dataset.id;
      eliminarProducto(id);
    }
  });

  // --- INICIALIZAR ---
  cargarCheckout();
  window.addEventListener("refresh-cart", cargarCheckout);
});
</script>
