<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?? 'Mi Proyecto'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body class="min-h-screen bg-gradient-to-b from-[#341D58] via-[#5A189A] via-20% via-[#9D4EDD] via-40% via-[#E0AAFF] via-60% via-[#9D4EDD] via-80% to-[#5A189A]">
    
    <!-- Navbar (global) -->
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <!-- Contenido dinámico de cada página -->
    <?= $content; ?>

    <!-- Footer (global) -->
    <?php include __DIR__ . '/../components/Footer.php'; ?>
  </body>
</html>
