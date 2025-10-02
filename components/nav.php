<?php session_start(); ?>
<!-- NAVBAR -->
<nav class="relative z-50 bg-gradient-to-r from-[#1b0f2e] via-[#2a0d3f] to-[#4a1b7b] text-white">
  <div class="absolute inset-x-0 top-0 h-px bg-white/10"></div>
  <div class="mx-auto max-w-7xl p-3 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">
      
      <!-- Logo -->
      <a href="index.php?page=home" class="flex items-center gap-3">
        <img src="assets/img/logo.png" alt="Maywa Textil" class="h-12 w-12 rounded-full ring-1 ring-white/20"/>
      </a>

      <!-- Links -->
      <div class="hidden md:flex items-center gap-8">
        <a href="/categorias" class="nav-link">Categorías</a>
        <a href="index.php?page=productos" class="nav-link">Productos</a>
        <a href="index.php?page=conoce" class="nav-link">Conoce</a>
        <a href="index.php?page=somos" class="nav-link">Quiénes Somos</a>
      </div>

      <!-- Sección derecha -->
      <div class="hidden md:flex items-center gap-3">
        <?php if (isset($_SESSION['usuario'])): ?>
          <!-- Usuario logueado -->
          <div class="relative">
            <button id="userMenuBtn" class="flex items-center gap-2 rounded-3xl px-4 py-2 bg-[#9d4edd] hover:bg-[#b368ff] text-white font-semibold">
              <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <!-- Dropdown -->
            <div id="userMenu" class="absolute right-0 mt-2 hidden bg-white text-gray-800 rounded-lg shadow-lg w-40">
              <a href="/MAYWATEXTIL/api/auth/logout.php" class="block px-4 py-2 hover:bg-gray-100">Cerrar Sesión</a>
            </div>
          </div>
        <?php else: ?>
          <!-- Invitado -->
          <a href="index.php?page=sesion"
             class="rounded-3xl px-6 py-2 text-sm font-semibold border border-white/25 bg-white/5 hover:bg-white/10 hover:border-white/40 backdrop-blur transition">
            Iniciar Sesión
          </a>
          <a href="index.php?page=register"
             class="rounded-3xl px-6 py-2 text-sm font-semibold bg-[#9d4edd] hover:bg-[#b368ff] text-white shadow-lg shadow-fuchsia-900/30 transition">
            Crear Cuenta
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>

<script>
  // Toggle menú usuario
  document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("userMenuBtn");
    const menu = document.getElementById("userMenu");
    if(btn && menu){
      btn.addEventListener("click", () => {
        menu.classList.toggle("hidden");
      });
    }
  });
</script>
