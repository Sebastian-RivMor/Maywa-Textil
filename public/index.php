<?php

$page = $_GET['page'] ?? 'home';
$file = __DIR__ . '/../pages/' . $page . '.php';

if (!file_exists($file)) {
  $file = __DIR__ . '/../pages/home.php';
  $page = 'home';
}

if ($page === 'logout') {
    include '../api/auth/logout.php';
    exit;
}


// Capturamos el contenido de la página
ob_start();
include $file;
$content = ob_get_clean();

// Definimos título dinámico
$title = ucfirst($page) . " - MAYWA";

// Si la página definió un layout, usamos ese, si no, main.php por defecto
$layout = $layout ?? 'main.php';
include __DIR__ . '/../layouts/' . $layout;
