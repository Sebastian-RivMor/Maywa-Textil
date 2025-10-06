<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bd_maywa1";
// $dbname = "bd_maywa";
// $dbname = "bd_maywa_test";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
