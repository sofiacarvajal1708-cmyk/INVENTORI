<?php
define('APP_ROOT', 'C:/xampp/htdocs/INVENTORI-main/app');
define('BASE_URL', 'http://localhost/INVENTORI-main/public');

require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/core/Model.php';
require_once APP_ROOT . '/models/Computador.php';

$conn = Database::connect();
$stmt = $conn->query("SELECT id_sala FROM salas LIMIT 1");
$sala = $stmt->fetch();
$validSalaId = $sala['id_sala'];

$compModel = new Computador();

// Verify create test equipment
$testData = [
    'placa_sed' => 'TEST-SED-' . rand(1000, 9999),
    'id_marca' => 1,
    'modelo' => 'OptiPlex 7090 Test',
    'numero_serial' => 'SN-TEST-' . rand(1000, 9999),
    'id_sala_actual' => $validSalaId,
    'sede' => 'Santa Margarita',
    'descripcion_adicional' => 'Equipo de prueba de inserción',
    'estado_activo' => 'Operativo'
];

$newId = $compModel->create($testData);
if ($newId) {
    echo "SUCCESS: Created test equipo with ID: " . $newId . "\n";
    $fetched = $compModel->getById($newId);
    echo "Fetched equipo model: " . $fetched['modelo'] . " S/N: " . $fetched['numero_serial'] . "\n";
    // Delete test equipment
    $delStmt = $conn->prepare("DELETE FROM computadores WHERE id_computador = :id");
    $delStmt->execute(['id' => $newId]);
    echo "Cleaned up test equipo.\n";
} else {
    echo "FAILED to create test equipo.\n";
}
