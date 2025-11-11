<?php
session_start();
session_destroy();

// Redirigir al home del cliente
header("Location: ../public/index.php?page=home");
exit;
