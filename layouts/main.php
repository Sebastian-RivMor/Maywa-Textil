<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?? 'Maywa'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
  </head>
  <body style="font-family: 'Poppins', sans-serif;" class=" font-poppins min-h-screen bg-gradient-to-b from-[#341D58] via-[#5A189A] via-20% via-[#9D4EDD] via-40% via-[#E0AAFF] via-60% via-[#9D4EDD] via-80% to-[#5A189A]">
    
    <!-- Navbar (global) -->
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <?php include __DIR__ . '/../components/Carrito.php'; ?>

    <!-- Contenido dinámico de cada página -->
    <?= $content; ?>

    <!-- Footer (global) -->
    <?php include __DIR__ . '/../components/Footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
      const grid = document.getElementById("productos-grid");
      const pagination = document.getElementById("paginacion");

      pagination.addEventListener("click", async (e) => {
        const link = e.target.closest("a");
        if (!link) return;

        e.preventDefault();
        const url = link.getAttribute("href");

        try {
          grid.innerHTML = "<p class='text-center text-white col-span-full'>Cargando productos...</p>";
          const response = await fetch(url, { headers: { "X-Requested-With": "XMLHttpRequest" } });
          const text = await response.text();
          const parser = new DOMParser();
          const doc = parser.parseFromString(text, "text/html");

          const newGrid = doc.getElementById("productos-grid");
          const newPagination = doc.getElementById("paginacion");

          if (newGrid && newPagination) {
            grid.innerHTML = newGrid.innerHTML;
            pagination.innerHTML = newPagination.innerHTML;
            window.scrollTo({ top: grid.offsetTop - 100, behavior: "smooth" });
          } else {
            grid.innerHTML = "<p class='text-center text-red-400 col-span-full'>No se pudieron cargar los productos.</p>";
          }
        } catch (err) {
          console.error("Error:", err);
          grid.innerHTML = "<p class='text-center text-red-400 col-span-full'>Error al cargar productos.</p>";
        }
      });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
      function cartComponent() {
      return {
        open: false,
        cart: [],
        async loadCart() {
          const res = await fetch('/MAYWATEXTIL/api/cart/list.php');
          this.cart = await res.json();
        },
        async removeItem(id) {
          await fetch('/MAYWATEXTIL/api/cart/remove.php', {
            method: 'POST',
            body: new URLSearchParams({ id })
          });
          this.loadCart();
        },
        async updateQty(id, action) {
          await fetch('/MAYWATEXTIL/api/cart/update.php', {
            method: 'POST',
            body: new URLSearchParams({ id, action })
          });
          this.loadCart();
        },
        total() {
          return this.cart.reduce((a, p) => a + p.precio * (p.cantidad ?? 1), 0).toFixed(2);
        },
        init() {
          this.loadCart();
          window.addEventListener('refresh-cart', () => this.loadCart());
        }
      }
    }
    </script>
    <script>
      document.addEventListener('add-to-cart', async (e) => {
        try {
          const { id, nombre, categoria, stock, precio, foto } = e.detail;
          await fetch('/MAYWATEXTIL/api/cart/add.php', {
            method: 'POST',
            body: new URLSearchParams({ id, nombre, categoria, stock, precio, foto })
          });
          window.dispatchEvent(new CustomEvent('refresh-cart'));
        } catch (err) {
          console.error('Error agregando producto:', err);
        }
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
    /* --- Manejador global del carrito --- */
    document.addEventListener('DOMContentLoaded', () => {
      // Escucha cuando se hace clic en un botón que tenga data-product
      document.body.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-product]');
        if (!btn) return;

        e.preventDefault();

        const product = btn.dataset;
        try {
          await fetch('/MAYWATEXTIL/api/cart/add.php', {
            method: 'POST',
            body: new URLSearchParams({
              id: product.id,
              nombre: product.nombre,
              categoria: product.categoria,
              stock: product.stock,
              precio: product.precio,
              foto: product.foto
            })
          });
          // Notificar al carrito flotante
          window.dispatchEvent(new CustomEvent('refresh-cart'));
        } catch (err) {
          console.error('Error al agregar al carrito:', err);
        }
      });
    });
    </script>

  </body>
</html>