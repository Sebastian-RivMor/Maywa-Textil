<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<nav class="relative z-50 w-full text-white rounded-none"
     style="background:linear-gradient(to right,#0F0423,#46386F);border-bottom:1px solid #6A386B;">

  <div class="mx-auto max-w-7xl px-3 sm:px-5">
    <div class="flex h-24 items-center justify-between">

      <!-- Logo -->
      <a href="index.php?page=home" class="flex items-center gap-2 shrink-0">
        <img src="assets/img/logo.png" alt="Maywa Textil"
             class="rounded-full ring-1 ring-white/20"
             style="width:65px;height:65px;">
      </a>

      <!-- Botón hamburguesa -->
      <button id="menuToggle" aria-label="Abrir men煤" aria-expanded="false"
        class="md:hidden inline-flex items-center justify-center p-2 rounded-md hover:bg-white/10 focus:outline-none">
        <svg id="iconOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="iconClose" class="h-6 w-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <!-- Links desktop -->
      <div class="hidden md:flex flex-1 justify-center">
        <ul class="flex items-center gap-10">
          <li><a href="index.php?page=productos" class="nav-link text-[18px]">Productos</a></li>
          <li><a href="index.php?page=conoce" class="nav-link text-[18px]">Comunidades</a></li>
          <li><a href="index.php?page=somos" class="nav-link text-[18px]">Quiénes Somos</a></li>
        </ul>
      </div>

      <!-- Botones / Usuario -->
      <div class="hidden md:flex items-center gap-2 relative">
        <?php if (isset($_SESSION['usuario'])): $u = $_SESSION['usuario']; ?>
          <!-- Botón de usuario con menú -->
          <div class="relative">
            <button id="userMenuBtn"
                    class="flex items-center gap-2 rounded-3xl bg-[#9d4edd] hover:bg-[#b368ff] text-white shadow-lg shadow-fuchsia-900/30 px-6 py-3 transition"
                    style="min-width:191.91px;height:64px;font-size:18px;">
              <span><?php echo htmlspecialchars($u['nombre'] ?? 'Usuario'); ?></span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>

            <!-- Menú desplegable -->
            <div id="userDropdown"
                 class="absolute right-0 mt-2 hidden bg-[#1E1B3A] text-white rounded-lg shadow-lg border border-white/10 w-48">
              <a href="/MAYWATEXTIL/api/auth/logout.php" class="block px-4 py-2 hover:bg-white/10">Cerrar sesión</a>
              <a href="index.php?page=compras" class="block px-4 py-2 hover:bg-white/10">Compras</a>
            </div>
          </div>
        <?php else: ?>
          <a href="index.php?page=sesion"
             class="rounded-3xl border border-white/25 bg-white/5 hover:bg-white/10 hover:border-white/40 backdrop-blur transition flex items-center justify-center"
             style="min-width:191.91px;height:64px;padding:0 20px;font-size:18px;">
            Iniciar Sesión
          </a>
          <a href="index.php?page=register"
             class="rounded-3xl bg-[#9d4edd] hover:bg-[#b368ff] text-white shadow-lg shadow-fuchsia-900/30 transition flex items-center justify-center"
             style="min-width:191.91px;height:64px;padding:0 20px;font-size:18px;">
            Crear Cuenta
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Menú movil -->
  <div id="mobileMenu" class="md:hidden hidden px-4 pb-3">
    <ul class="space-y-2 pt-2 border-t border-white/10">
      <li><a href="index.php?page=productos" class="block py-2 nav-link text-[18px]">Productos</a></li>
      <li><a href="index.php?page=comunidades" class="block py-2 nav-link text-[18px]">Comunidades</a></li>
      <li><a href="index.php?page=somos" class="block py-2 nav-link text-[18px]">Quiénes Somos</a></li>
    </ul>
    <div class="mt-3 flex flex-col gap-2">
      <?php if (isset($_SESSION['usuario'])): ?>
        <a href="/MAYWATEXTIL/api/auth/logout.php"
           class="rounded-3xl bg-[#6a1b9a] hover:bg-[#b368ff] text-white transition flex items-center justify-center"
           style="height:64px;font-size:18px;">
          Cerrar sesión
        </a>
      <?php else: ?>
        <a href="index.php?page=sesion"
           class="rounded-3xl border border-white/25 bg-white/5 hover:bg-white/10 transition flex items-center justify-center"
           style="height:64px;font-size:18px;">
          Iniciar Sesión
        </a>
        <a href="index.php?page=register"
           class="rounded-3xl bg-[#9d4edd] hover:bg-[#b368ff] text-white transition flex items-center justify-center"
           style="height:64px;font-size:18px;">
          Crear Cuenta
        </a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<style>
  .nav-link { color: rgba(255,255,255,.9); transition: color .2s; }
  .nav-link:hover { color:#fff; }
  nav, body, main, header { border-radius:0; overflow:visible; }

  /* Ajustes responsive */
  @media (max-width: 768px) {
    nav img { width: 40px !important; height: 40px !important; }
    .nav-link { font-size: 15px !important; }
    #mobileMenu a { font-size: 15px !important; }
    #mobileMenu a, nav a[style*="min-width"] {
      min-width: 140px !important; height: 40px !important; font-size: 15px !important;
    }
    nav .h-24 { height: 80px !important; }
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("menuToggle");
    const menu = document.getElementById("mobileMenu");
    const openI = document.getElementById("iconOpen");
    const closeI = document.getElementById("iconClose");

    if (btn && menu) {
      btn.addEventListener("click", () => {
        const isOpen = !menu.classList.contains("hidden");
        menu.classList.toggle("hidden");
        btn.setAttribute("aria-expanded", String(!isOpen));
        openI.classList.toggle("hidden");
        closeI.classList.toggle("hidden");
      });
    }

    // Menú del usuario (click)
    const userBtn = document.getElementById("userMenuBtn");
    const dropdown = document.getElementById("userDropdown");

    if (userBtn && dropdown) {
      userBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        dropdown.classList.toggle("hidden");
      });

      document.addEventListener("click", (e) => {
        if (!userBtn.contains(e.target) && !dropdown.contains(e.target)) {
          dropdown.classList.add("hidden");
        }
      });
    }
  });
</script>