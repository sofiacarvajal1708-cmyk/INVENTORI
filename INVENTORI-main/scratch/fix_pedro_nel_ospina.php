<?php
define('APP_ROOT', 'C:/xampp/htdocs/INVENTORI-main/app');
require_once APP_ROOT . '/config/database.php';
$conn = Database::connect();

// Update room names that have "Pedro Nel" without "Ospina"
$stmt = $conn->query("SELECT id_sala, nombre_sala FROM salas WHERE nombre_sala LIKE '%Pedro Nel%' AND nombre_sala NOT LIKE '%Pedro Nel Ospina%'");
$rooms = $stmt->fetchAll();

foreach ($rooms as $r) {
    $newName = str_replace('Pedro Nel', 'Pedro Nel Ospina', $r['nombre_sala']);
    $updateStmt = $conn->prepare("UPDATE salas SET nombre_sala = :newName WHERE id_sala = :id");
    $updateStmt->execute(['newName' => $newName, 'id' => $r['id_sala']]);
    echo "Updated sala ID {$r['id_sala']}: '{$r['nombre_sala']}' -> '{$newName}'\n";
}

// Update computer descriptions or sedes if any
$stmt2 = $conn->query("SELECT id_computador, sede FROM computadores WHERE sede LIKE '%Pedro Nel%' AND sede NOT LIKE '%Pedro Nel Ospina%'");
$comps = $stmt2->fetchAll();

foreach ($comps as $c) {
    $newSede = str_replace('Pedro Nel', 'Pedro Nel Ospina', $c['sede']);
    $updateStmt2 = $conn->prepare("UPDATE computadores SET sede = :newSede WHERE id_computador = :id");
    $updateStmt2->execute(['newSede' => $newSede, 'id' => $c['id_computador']]);
    echo "Updated computador ID {$c['id_computador']}: '{$c['sede']}' -> '{$newSede}'\n";
}

echo "Finished updating Pedro Nel -> Pedro Nel Ospina in DB.\n";
