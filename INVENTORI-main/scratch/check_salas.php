<?php
define('APP_ROOT', 'C:/xampp/htdocs/INVENTORI-main/app');
require_once APP_ROOT . '/config/database.php';
$conn = Database::connect();
$stmt = $conn->query("SELECT * FROM salas");
$salas = $stmt->fetchAll();
print_r($salas);
