<!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= $title ?? 'Maywa'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
  </head>
  <body>
    
    <!-- Navbar (global) -->
    <?php include __DIR__ . '/../components/nav.php'; ?>

    <!-- Contenido dinámico de cada página -->
    <?= $content; ?>

  </body>
</html>
