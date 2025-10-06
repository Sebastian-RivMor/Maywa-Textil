<?php if (isset($producto) && is_array($producto)): ?>
<div class="block bg-[#5a2d82] rounded-2xl overflow-hidden shadow-lg border border-white/10 
            hover:scale-[1.03] transition-transform duration-200">

    <!-- Enlace solo en imagen y título -->
    <a href="/MAYWATEXTIL/pages/seleccion.php?id=<?= htmlspecialchars($producto['id_producto']) ?>">
        <!-- Imagen -->
        <div class="bg-white flex items-center justify-center p-4">
            <img 
                src="<?= !empty($producto['foto_url']) ? htmlspecialchars($producto['foto_url']) : '/MAYWATEXTIL/public/assets/img/no-image.png' ?>" 
                alt="<?= htmlspecialchars($producto['nombre_producto']) ?>" 
                class="w-full h-56 object-contain rounded-xl">
        </div>
    </a>

    <!-- Contenido -->
    <div class="p-4 flex-1 flex flex-col items-center text-center">
        <h3 class="text-white font-bold text-lg leading-tight mb-1">
            <?= htmlspecialchars($producto['nombre_producto']) ?>
        </h3>
        <p class="text-white/80 text-sm leading-snug">
            <?= htmlspecialchars($producto['descripcion_corta']) ?>
        </p>
    </div>

    <!-- Precio + Botón -->
    <div class="bg-[#6a1b9a] px-5 py-3 flex items-center justify-between rounded-b-2xl shadow-inner">
        <span class="text-white font-semibold text-lg">
            S/. <?= number_format($producto['precio'], 2) ?>
        </span>

        <!-- Botón agregar (sin redirigir) -->
        <button
            data-product
            data-id="<?= $producto['id_producto'] ?>"
            data-nombre="<?= htmlspecialchars($producto['nombre_producto']) ?>"
            data-categoria="<?= htmlspecialchars($producto['categoria'] ?? '') ?>"
            data-stock="<?= htmlspecialchars($producto['stock'] ?? 0) ?>"
            data-precio="<?= htmlspecialchars($producto['precio']) ?>"
            data-foto="<?= htmlspecialchars($producto['foto_url']) ?>"
            class="px-5 py-2 rounded-full text-sm font-semibold 
                    bg-gradient-to-r from-[#9D4EDD] to-[#C77DFF] text-white 
                    hover:scale-105 transition-transform">
            Agregar
        </button>
    </div>
</div>
<?php endif; ?>