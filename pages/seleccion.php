<?php
$layout = 'main.php';
$title = "Detalle del producto";
ob_start();

require_once __DIR__ . '/../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<p class='text-center text-white mt-10'>Producto no encontrado.</p>";
    exit;
}

$stmt = $pdo->prepare("SELECT p.*, c.nombre_comunidad 
                       FROM tb_producto p 
                       LEFT JOIN tb_comunidad c ON p.id_comunidad = c.id_comunidad
                       WHERE p.id_producto = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    echo "<p class='text-center text-white mt-10'>Producto no encontrado.</p>";
    exit;
}

$stmt2 = $pdo->query("SELECT * FROM tb_producto WHERE id_producto != $id LIMIT 4");
$relacionados = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="max-w-6xl mx-auto px-8 py-16">
  <div class="relative p-[2px] rounded-[30px] bg-gradient-to-tr from-[#ffffff40] to-[#ffffff10]">
    <!-- Contenedor interior con fondo degradado -->
    <div class="grid grid-cols-1 md:grid-cols-2 bg-gradient-to-br from-[#3a0ca3]/70 to-[#7209b7]/70 rounded-[28px] border border-white/20 backdrop-blur-md shadow-2xl overflow-hidden">
      
      <!-- Imagen -->
      <div class="bg-white flex items-center justify-center p-10 rounded-[26px] m-6 shadow-inner">
        <img 
          src="<?= htmlspecialchars($producto['foto_url']) ?>" 
          alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" 
          class="object-contain w-full max-h-[520px] transition-transform duration-300 hover:scale-105 rounded-2xl"
        >
      </div>

      <!-- Detalles -->
      <div class="flex flex-col justify-between text-white p-8 md:p-10">
        <div>
          <h2 class="text-lg font-medium text-[#E0AAFF] mb-2">Maywa Textil</h2>
          <h1 class="text-3xl md:text-4xl font-bold mb-3"><?= htmlspecialchars($producto['nombre_producto']) ?></h1>
          
          <div class="flex items-center gap-3 mb-5">
            <p class="text-2xl font-semibold text-[#E0AAFF]">S/. <?= number_format($producto['precio'], 2) ?></p>
            <span class="bg-white/10 text-sm px-3 py-1 rounded-full border border-white/20">
              <?= htmlspecialchars($producto['nombre_comunidad'] ?? 'Asociación artesanal') ?>
            </span>
          </div>

          <!-- Cantidad funcional -->
          <div class="mb-6">
            <label class="block font-semibold mb-2 text-base">Cantidad</label>
            <div class="flex items-center gap-4">
              <button id="btn-decrementar" class="bg-[#5a189a] w-10 h-10 flex items-center justify-center rounded-xl text-xl hover:bg-[#7B2CBF] transition">−</button>
              <input id="input-cantidad" type="number" value="1" min="1" class="w-16 text-center text-black rounded-xl py-2 border border-gray-300 shadow-inner">
              <button id="btn-incrementar" class="bg-[#5a189a] w-10 h-10 flex items-center justify-center rounded-xl text-xl hover:bg-[#7B2CBF] transition">+</button>
            </div>
          </div>

          <!-- Botones -->
          <div class="flex flex-col gap-3">
            <button
              data-product
              data-id="<?= $producto['id_producto'] ?>"
              data-nombre="<?= htmlspecialchars($producto['nombre_producto']) ?>"
              data-categoria="<?= htmlspecialchars($producto['categoria'] ?? '') ?>"
              data-stock="<?= htmlspecialchars($producto['stock'] ?? 0) ?>"
              data-precio="<?= htmlspecialchars($producto['precio']) ?>"
              data-foto="<?= htmlspecialchars($producto['foto_url']) ?>"
              class="w-full bg-gradient-to-r from-[#9D4EDD] to-[#7B2CBF] hover:from-[#7B2CBF] hover:to-[#5A189A] text-white py-3 rounded-xl font-semibold text-lg shadow-md transition">
              Añadir al carrito
          </button>
            <button class="w-full bg-gradient-to-r from-[#C77DFF] to-[#9D4EDD] hover:from-[#9D4EDD] hover:to-[#7B2CBF] text-white py-3 rounded-xl font-semibold text-lg shadow-md transition">
              Comprar ahora
            </button>
          </div>
        </div>

        <!-- Descripción -->
        <div class="mt-8 bg-white/10 p-5 rounded-2xl leading-relaxed text-sm backdrop-blur-md border border-white/10 overflow-y-auto max-h-[220px] scrollbar-thin scrollbar-thumb-[#9D4EDD] scrollbar-thumb-rounded-full scrollbar-track-transparent">
          <?= nl2br(htmlspecialchars($producto['descripcion_producto'])) ?>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- SECCIÓN: Productos relacionados (4 aleatorios) -->
<section class="max-w-7xl mx-auto px-6 py-16">
  <h2 class="text-2xl font-bold text-white mb-8 text-center">También te puede interesar</h2>

  <?php
  // Obtener 4 productos aleatorios distintos del actual
  $stmtRelacionados = $pdo->prepare("
      SELECT * FROM tb_producto 
      WHERE id_producto != ? 
      ORDER BY RAND() 
      LIMIT 4
  ");
  $stmtRelacionados->execute([$id]);
  $relacionados = $stmtRelacionados->fetchAll(PDO::FETCH_ASSOC);

  if (!empty($relacionados)) {
      echo '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">';
      foreach ($relacionados as $producto) {
          include __DIR__ . '/../components/Card_product.php';
      }
      echo '</div>';
  } else {
      echo '<p class="text-white/80 text-center mt-6">No hay productos relacionados por ahora.</p>';
  }
  ?>
</section>


<script>
  document.addEventListener("DOMContentLoaded", () => {
    const inputCantidad = document.getElementById("input-cantidad");
    const btnMas = document.getElementById("btn-incrementar");
    const btnMenos = document.getElementById("btn-decrementar");

    btnMas.addEventListener("click", () => {
      inputCantidad.value = parseInt(inputCantidad.value || 0) + 1;
    });

    btnMenos.addEventListener("click", () => {
      const actual = parseInt(inputCantidad.value || 1);
      if (actual > 1) inputCantidad.value = actual - 1;
    });
  });
</script>


<?php
$content = ob_get_clean();
include __DIR__ . "/../layouts/$layout";
?>
