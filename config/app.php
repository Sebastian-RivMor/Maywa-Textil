<?php
// Detecta base automáticamente si sirves desde subcarpeta en localhost
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host   = $_SERVER['HTTP_HOST'];
$base   = rtrim(str_replace('\\','/', dirname($_SERVER['SCRIPT_NAME'])), '/'); // ej: /Maywa-Textil
define('BASE_URL', $base ?: ''); // '' si está en raíz
?>
