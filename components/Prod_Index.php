<!-- SECCIÓN: Productos (ancho centrado controlable) -->
<section class="w-[1440px] mx-auto flex justify-center py-10">
  <div class="w-[1200px]">
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-xl md:text-2xl font-bold text-white">Productos Destacados</h2>
      <a href="/MAYWATEXTIL/public/index.php?page=productos" class="text-sm font-medium text-white hover:underline">
        Ir a catálogo →
      </a>
    </div>

    <?php
    // Ruta del endpoint API
    $apiUrl = "http://localhost/MAYWATEXTIL/api/admin/productos/get_producto.php";
    $productosJson = @file_get_contents($apiUrl);
    $productos = json_decode($productosJson, true);

    if ($productosJson === false || !is_array($productos) || empty($productos)) {
        echo "<p class='text-white/80 text-center mt-6'>No hay productos disponibles por el momento.</p>";
    } else {
        // Mezclar aleatoriamente los productos
        shuffle($productos);

        // Tomar solo 4 aleatorios
        $productos_destacados = array_slice($productos, 0, 4);

        echo "<div class='grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6'>";
        foreach ($productos_destacados as $producto) {
        include __DIR__ . '/Card_product.php';
        }
        echo "</div>";
    }
    ?>

  </div>
</section>
