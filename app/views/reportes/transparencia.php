<?php
require_once APP_ROOT . '/views/layout/header.php';
$counts = $data['counts'];
$salas = $data['salas'];
$bajas = $data['bajas'];
?>

<div class="space-y-8">
    
    <!-- Encabezado del Portal de Transparencia -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-users-viewfinder text-violet-400"></i>
                <span>Portal de Veeduría y Transparencia Pública</span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">Acceso libre de control social sobre el inventario y disposición de activos tecnológicos del colegio.</p>
        </div>
        
        <a href="<?= BASE_URL ?>/dashboard" class="py-2 px-4 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-all flex items-center space-x-2">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Volver al Panel</span>
        </a>
    </div>

    <!-- Fila de Resumen de Disponibilidad de Hardware -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
        
        <!-- Total -->
        <div class="glass-panel rounded-2xl p-5 border border-white/5 bg-black/10 text-center">
            <span class="block text-xxs font-bold text-slate-400 uppercase">Equipos Registrados</span>
            <span class="block text-2xl font-extrabold text-white mt-1"><?= $counts['total'] ?></span>
        </div>

        <!-- Operativos -->
        <div class="glass-panel rounded-2xl p-5 border border-white/5 bg-black/10 text-center">
            <span class="block text-xxs font-bold text-slate-400 uppercase">Operativos (Uso)</span>
            <span class="block text-2xl font-extrabold text-emerald-400 mt-1"><?= $counts['operativos'] ?></span>
        </div>

        <!-- En Mantenimiento -->
        <div class="glass-panel rounded-2xl p-5 border border-white/5 bg-black/10 text-center">
            <span class="block text-xxs font-bold text-slate-400 uppercase">En Mantenimiento</span>
            <span class="block text-2xl font-extrabold text-yellow-500 mt-1"><?= $counts['mantenimiento'] ?></span>
        </div>

        <!-- Obsoletos -->
        <div class="glass-panel rounded-2xl p-5 border border-white/5 bg-black/10 text-center">
            <span class="block text-xxs font-bold text-slate-400 uppercase">Obsoletos</span>
            <span class="block text-2xl font-extrabold text-indigo-400 mt-1"><?= $counts['obsoletos'] ?></span>
        </div>

        <!-- Dados de Baja -->
        <div class="glass-panel rounded-2xl p-5 border border-white/5 bg-black/10 text-center">
            <span class="block text-xxs font-bold text-slate-400 uppercase">Dados de Baja (RAEE)</span>
            <span class="block text-2xl font-extrabold text-rose-500 mt-1"><?= $counts['deBaja'] ?></span>
        </div>

    </div>

    <!-- Mapeo de Distribución de Hardware y RAEE -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Distribución por dependencias -->
        <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 space-y-4 lg:col-span-1">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-location-crosshairs text-violet-400"></i>
                <span>Distribución por Salas</span>
            </h3>
            
            <div class="space-y-3.5 text-xs">
                <?php if (empty($salas)): ?>
                    <p class="text-slate-500 italic text-center">No hay salas cargadas.</p>
                <?php else: ?>
                    <?php foreach ($salas as $s): ?>
                        <div class="flex justify-between items-center p-2.5 bg-black/25 rounded-xl border border-white/5">
                            <span class="font-semibold text-slate-300"><?= htmlspecialchars($s['nombre_sala']) ?></span>
                            <span class="px-2 py-0.5 rounded bg-violet-600/20 text-violet-400 font-bold border border-violet-500/10 text-[10px]">
                                <?= $s['cantidad'] ?> equipos
                            </span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Evidencia de bajas RAEE ambientales -->
        <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 space-y-4 lg:col-span-2">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-recycle text-emerald-400"></i>
                <span>Auditoría Ambiental de Desechos (Ley 1672)</span>
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider bg-black/10">
                            <th class="py-3 px-2">Activo</th>
                            <th class="py-3 px-2">Acta Consejo</th>
                            <th class="py-3 px-2">Fecha Baja</th>
                            <th class="py-3 px-2">Certificado RAEE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bajas)): ?>
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-500 italic">No hay registros de destrucción ecológica cargados en la bitácora pública.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($bajas as $b): ?>
                                <tr class="border-b border-white/5 text-slate-300 hover:bg-white/[0.01]">
                                    <td class="py-3 px-2">
                                        <span class="font-semibold text-white"><?= htmlspecialchars($b['nombre_marca'] . ' ' . $b['modelo']) ?></span>
                                    </td>
                                    <td class="py-3 px-2 font-mono text-slate-400"><?= htmlspecialchars($b['numero_acta_consejo']) ?></td>
                                    <td class="py-3 px-2 whitespace-nowrap"><?= $b['fecha_baja'] ?></td>
                                    <td class="py-3 px-2">
                                        <span class="px-2 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold font-mono">
                                            <?= htmlspecialchars($b['numero_certificado_raee']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
