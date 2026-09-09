<?php
define('APP_ROOT', 'C:/xampp/htdocs/INVENTORI-main/app');
require_once APP_ROOT . '/config/database.php';
$conn = Database::connect();

// 1. Update id_sala 3 (Física Santa Margarita)
$conn->exec("UPDATE salas SET nombre_sala = 'Laboratorio de Física de Santa Margarita' WHERE id_sala = 3");
echo "Updated id_sala 3 -> Laboratorio de Física de Santa Margarita\n";

// 2. Update id_sala 10 (Química Santa Margarita)
$conn->exec("UPDATE salas SET nombre_sala = 'Laboratorio de Química de Santa Margarita' WHERE id_sala = 10");
echo "Updated id_sala 10 -> Laboratorio de Química de Santa Margarita\n";

// 3. Update id_sala 11 (Ciencias Bachillerato)
$conn->exec("UPDATE salas SET nombre_sala = 'Laboratorio de Ciencias de Bachillerato' WHERE id_sala = 11");
echo "Updated id_sala 11 -> Laboratorio de Ciencias de Bachillerato\n";

// 4. Check if Laboratorio de Ciencias de Santa Margarita exists, if not insert it
$chk = $conn->query("SELECT id_sala FROM salas WHERE nombre_sala = 'Laboratorio de Ciencias de Santa Margarita'")->fetch();
if (!$chk) {
    $conn->exec("INSERT INTO salas (nombre_sala, sede, categoria, tiene_polo_a_tierra, tiene_estabilizador, tiene_red_structured, ultima_revision_infraestructura) VALUES ('Laboratorio de Ciencias de Santa Margarita', 'Santa Margarita', 'Laboratorios', 1, 1, 1, NOW())");
    echo "Inserted Laboratorio de Ciencias de Santa Margarita\n";
} else {
    echo "Already exists: Laboratorio de Ciencias de Santa Margarita\n";
}
