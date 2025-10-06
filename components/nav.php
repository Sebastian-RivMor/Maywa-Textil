<?php session_start(); ?>
<nav class="relative z-50 w-full text-white rounded-none"
     style="background:linear-gradient(to right,#0F0423,#46386F);border-bottom:1px solid #6A386B;">

  <div class="mx-auto max-w-7xl px-3 sm:px-5">
    <!-- altura del contenedor (opcional): h-24 ≈ 96px para respirar -->
    <div class="flex h-24 items-center justify-between">

      <!-- Logo 65x65 -->
      <a href="/MAYWATEXTIL/public/index.php?page=home" class="flex items-center gap-3">
        <img src="/MAYWATEXTIL/public/assets/img/logo.png" alt="Maywa Textil" class="h-12 w-12 rounded-full ring-1 ring-white/20"/>
      </a>

      <!-- Botón hamburguesa (móvil) -->
      <button id="menuToggle" aria-label="Abrir menú" aria-expanded="false"
        class="md:hidden inline-flex items-center justify-center p-2 rounded-md hover:bg-white/10 focus:outline-none">
        <svg id="iconOpen" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
        <svg id="iconClose" class="h-6 w-6 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>

      <!-- Links (desktop) — texto 18px -->
      <div class="hidden md:flex flex-1 justify-center">
        <ul class="flex items-center gap-10">
          <li><a href="#categorias" class="nav-link text-[18px]">Categorías</a></li>
          <li><a href="/MAYWATEXTIL/public/index.php?page=productos" class="nav-link text-[18px]">Productos</a></li>
          <li><a href="/MAYWATEXTIL/public/index.php?page=conoce" class="nav-link text-[18px]">Comunidades</a></li>
          <li><a href="/MAYWATEXTIL/public/index.php?page=somos" class="nav-link text-[18px]">Quiénes Somos</a></li>
        </ul>
      </div>

      <!-- Botones (desktop) — 191.91 x 64 px, texto 18px -->
      <div class="hidden md:flex items-center gap-2">
        <?php if (isset($_SESSION['usuario'])): $u=$_SESSION['usuario']; ?>
          <a href="/MAYWATEXTIL/api/auth/logout.php"
             class="rounded-3xl text-white bg-[#9d4edd] hover:bg-[#b368ff] shadow-lg shadow-fuchsia-900/30 transition flex items-center justify-center"
             style="min-width:191.91px;height:64px;padding:0 20px;font-size:18px;">
            Salir
          </a>
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

  <!-- Menú móvil (links 18px, botones 64px alto) -->
  <div id="mobileMenu" class="md:hidden hidden px-4 pb-3">
    <ul class="space-y-2 pt-2 border-t border-white/10">
      <li><a href="/categorias" class="block py-2 nav-link text-[18px]">Categorías</a></li>
      <li><a href="index.php?page=productos" class="block py-2 nav-link text-[18px]">Productos</a></li>
      <li><a href="index.php?page=comunidades" class="block py-2 nav-link text-[18px]">Comunidades</a></li>
      <li><a href="index.php?page=somos" class="block py-2 nav-link text-[18px]">Quiénes Somos</a></li>
    </ul>
    <div class="mt-3 flex flex-col gap-2">
      <?php if (isset($_SESSION['usuario'])): ?>
        <a href="/MAYWATEXTIL/api/auth/logout.php"
           class="rounded-3xl bg-[#9d4edd] hover:bg-[#b368ff] text-white transition flex items-center justify-center"
           style="height:64px;font-size:18px;">
          Salir
        </a>
      <?php else: ?>
        <a href="index.php?page=sesion"
           class="rounded-3xl border border-white/25 bg-white/5 hover:bg:white/10 transition flex items-center justify-center"
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

  /* 🔧 Ajustes responsive para móviles */
  @media (max-width: 768px) {
    nav img {
      width: 40px !important;
      height: 40px !important;
    }

    .nav-link {
      font-size: 15px !important;
    }

    #mobileMenu a {
      font-size: 15px !important;
    }

    /* Botones más chicos en móviles */
    #mobileMenu a,
    nav a[style*="min-width"] {
      min-width: 140px !important;
      height: 40px !important;
      font-size: 15px !important;
    }

    /* Altura total del nav más baja */
    nav .h-24 {
      height: 80px !important;
    }
  }

</style>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const btn = document.getElementById("menuToggle");
    const menu = document.getElementById("mobileMenu");
    const openI = document.getElementById("iconOpen");
    const closeI = document.getElementById("iconClose");
    if(!btn || !menu) return;
    btn.addEventListener("click", () => {
      const isOpen = !menu.classList.contains("hidden");
      menu.classList.toggle("hidden");
      btn.setAttribute("aria-expanded", String(!isOpen));
      openI.classList.toggle("hidden");
      closeI.classList.toggle("hidden");
    });
  });
</script>