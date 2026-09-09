<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - IEBSM Inventario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        @media print {
            .no-print { display: none !important; }
            body { background-color: #ffffff; }
        }
    </style>
</head>
<body class="p-8">

    <!-- Acciones de Impresión (No Imprimible) -->
    <div class="no-print mb-6 flex justify-between items-center bg-slate-900 text-white p-4 rounded-xl shadow-lg">
        <div class="flex items-center space-x-3">
            <i class="fa-solid fa-file-pdf text-emerald-400 text-2xl"></i>
            <div>
                <h1 class="text-sm font-bold">Generación de Reporte PDF de Inventario</h1>
                <p class="text-xs text-slate-400">Si el cuadro de diálogo de impresión no se abre automáticamente, presione el botón "Imprimir Reporte".</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg text-xs flex items-center space-x-2 transition-all shadow-md active:scale-95">
                <i class="fa-solid fa-print"></i>
                <span>Imprimir Reporte / Guardar PDF</span>
            </button>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 font-bold rounded-lg text-xs">
                Cerrar
            </button>
        </div>
    </div>

    <!-- Membrete Institucional -->
    <div class="border-b-2 border-slate-800 pb-4 mb-6 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="<?= BASE_URL ?>/public/img/logo.png" alt="IEBSM Logo" class="h-16 w-16 object-contain">
            <div>
                <h1 class="text-lg font-bold uppercase text-slate-900 tracking-wide">Institución Educativa Barrio Santa Margarita</h1>
                <h2 class="text-sm font-semibold text-slate-700">Sistema de Control y Gestión Tecnológica</h2>
                <p class="text-xs text-slate-500">Reporte Oficial de Inventario Fijo de Equipos de Cómputo</p>
            </div>
        </div>
        <div class="text-right text-xs text-slate-600">
            <p><strong>Fecha de Generación:</strong> <?= date('d/m/Y H:i:s') ?></p>
            <p><strong>Generado Por:</strong> <?= Session::get('user_name') ?> (<?= Session::get('role_name') ?>)</p>
            <p><strong>Total Equipos Registrados:</strong> <?= count($computadores) ?></p>
        </div>
    </div>

    <!-- Filtros Aplicados si existen -->
    <?php if (!empty($filters['sede']) || !empty($filters['sala']) || !empty($filters['estado'])): ?>
    <div class="mb-4 p-3 bg-slate-100 border border-slate-300 rounded-lg text-xs text-slate-700 flex space-x-4">
        <span><strong>Filtros aplicados:</strong></span>
        <?php if (!empty($filters['sede'])): ?><span>Sede: <strong><?= htmlspecialchars($filters['sede']) ?></strong></span><?php endif; ?>
        <?php if (!empty($filters['sala'])): ?><span>ID Sala: <strong><?= htmlspecialchars($filters['sala']) ?></strong></span><?php endif; ?>
        <?php if (!empty($filters['estado'])): ?><span>Estado: <strong><?= htmlspecialchars($filters['estado']) ?></strong></span><?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Tabla Oficial -->
    <table class="w-full text-xs text-left border-collapse border border-slate-300">
        <thead>
            <tr class="bg-slate-800 text-white font-bold uppercase">
                <th class="p-2 border border-slate-300"># ID</th>
                <th class="p-2 border border-slate-300">Placa SED</th>
                <th class="p-2 border border-slate-300">Serial / S/N</th>
                <th class="p-2 border border-slate-300">Descripción del Equipo / Modelo</th>
                <th class="p-2 border border-slate-300">Sede</th>
                <th class="p-2 border border-slate-300">Ubicación / Sala</th>
                <th class="p-2 border border-slate-300">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($computadores)): ?>
                <tr>
                    <td colspan="7" class="p-4 text-center text-slate-500 font-medium">No se encontraron equipos registrados con los criterios seleccionados.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($computadores as $c): ?>
                    <tr class="border-b border-slate-300 hover:bg-slate-50">
                        <td class="p-2 border border-slate-300 font-mono text-center">#<?= $c['id_computador'] ?></td>
                        <td class="p-2 border border-slate-300 font-bold"><?= htmlspecialchars($c['placa_sed'] ?: 'N/A') ?></td>
                        <td class="p-2 border border-slate-300 font-mono"><?= htmlspecialchars($c['numero_serial']) ?></td>
                        <td class="p-2 border border-slate-300">
                            <strong><?= htmlspecialchars($c['nombre_marca'] . ' ' . $c['modelo']) ?></strong>
                            <?php if (!empty($c['procesador'])): ?>
                                <span class="block text-[10px] text-slate-500"><?= htmlspecialchars($c['procesador']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="p-2 border border-slate-300 font-medium"><?= htmlspecialchars($c['sede'] ?? $c['sala_sede'] ?? 'Santa Margarita') ?></td>
                        <td class="p-2 border border-slate-300"><?= htmlspecialchars($c['nombre_sala']) ?></td>
                        <td class="p-2 border border-slate-300 font-bold text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] 
                                <?= $c['estado_activo'] === 'Operativo' ? 'bg-emerald-100 text-emerald-800 border border-emerald-300' : '' ?>
                                <?= $c['estado_activo'] === 'En Mantenimiento' ? 'bg-amber-100 text-amber-800 border border-amber-300' : '' ?>
                                <?= $c['estado_activo'] === 'Obsoleto' ? 'bg-indigo-100 text-indigo-800 border border-indigo-300' : '' ?>
                                <?= $c['estado_activo'] === 'Dado de Baja' ? 'bg-rose-100 text-rose-800 border border-rose-300' : '' ?>
                            ">
                                <?= $c['estado_activo'] ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Bloque de Firmas Institucionales -->
    <div class="mt-16 grid grid-cols-2 gap-12 text-center text-xs text-slate-700">
        <div>
            <div class="border-t border-slate-800 pt-2 font-bold uppercase">Rectoría / Dirección Institucional</div>
            <p class="text-[10px] text-slate-500">IEBSM Barrio Santa Margarita</p>
        </div>
        <div>
            <div class="border-t border-slate-800 pt-2 font-bold uppercase">Encargado de Inventario / Almacén</div>
            <p class="text-[10px] text-slate-500">Control de Activos Fijos Tecnológicos</p>
        </div>
    </div>

    <script>
        // Abrir cuadro de diálogo de impresión automáticamente al cargar la página
        window.addEventListener('load', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
