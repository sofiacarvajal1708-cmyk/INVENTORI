<?php
define('APP_ROOT', 'C:/xampp/htdocs/INVENTORI-main/app');
require_once APP_ROOT . '/config/database.php';
$conn = Database::connect();

$newLabs = [
    [
        'nombre_sala' => 'Laboratorio de Ciencias Pedro Nel Ospina',
        'sede' => 'Pedro Nel Ospina',
        'categoria' => 'Laboratorios',
        'tiene_polo_a_tierra' => 1,
        'tiene_estabilizador' => 1,
        'tiene_red_structured' => 1
    ],
    [
        'nombre_sala' => 'Laboratorio de Física Pedro Nel Ospina',
        'sede' => 'Pedro Nel Ospina',
        'categoria' => 'Laboratorios',
        'tiene_polo_a_tierra' => 1,
        'tiene_estabilizador' => 1,
        'tiene_red_structured' => 1
    ],
    [
        'nombre_sala' => 'Laboratorio de Química Pedro Nel Ospina',
        'sede' => 'Pedro Nel Ospina',
        'categoria' => 'Laboratorios',
        'tiene_polo_a_tierra' => 1,
        'tiene_estabilizador' => 1,
        'tiene_red_structured' => 1
    ],
    [
        'nombre_sala' => 'Laboratorio de Física de Bachillerato',
        'sede' => 'Bachillerato Santa Margarita',
        'categoria' => 'Laboratorios',
        'tiene_polo_a_tierra' => 1,
        'tiene_estabilizador' => 1,
        'tiene_red_structured' => 1
    ],
    [
        'nombre_sala' => 'Laboratorio de Química de Bachillerato',
        'sede' => 'Bachillerato Santa Margarita',
        'categoria' => 'Laboratorios',
        'tiene_polo_a_tierra' => 1,
        'tiene_estabilizador' => 1,
        'tiene_red_structured' => 1
    ]
];

$stmtCheck = $conn->prepare("SELECT id_sala FROM salas WHERE nombre_sala = :nombre");
$stmtInsert = $conn->prepare("INSERT INTO salas (nombre_sala, sede, categoria, tiene_polo_a_tierra, tiene_estabilizador, tiene_red_structured, ultima_revision_infraestructura) VALUES (:nombre, :sede, :cat, :polo, :est, :red, NOW())");

foreach ($newLabs as $lab) {
    $stmtCheck->execute(['nombre' => $lab['nombre_sala']]);
    if (!$stmtCheck->fetch()) {
        $stmtInsert->execute([
            'nombre' => $lab['nombre_sala'],
            'sede' => $lab['sede'],
            'cat' => $lab['categoria'],
            'polo' => $lab['tiene_polo_a_tierra'],
            'est' => $lab['tiene_estabilizador'],
            'red' => $lab['tiene_red_structured']
        ]);
        echo "Inserted: " . $lab['nombre_sala'] . " (" . $lab['sede'] . ")\n";
    } else {
        echo "Already exists: " . $lab['nombre_sala'] . "\n";
    }
}
