<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
$canEdit = in_array($role, ['Rector', 'Almacenista']);
?>

<div class="space-y-6">

    <!-- Encabezado de Sección -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Inventario de Equipos de Cómputo</h2>
            <p class="text-xs text-slate-400 mt-1">Control exhaustivo de terminales, licenciamiento y ubicación física en salas.</p>
        </div>
        
        <?php if ($canEdit): ?>
            <a href="<?= BASE_URL ?>/computadores/crear" class="py-2.5 px-4 bg-gradient-to-r from-violet-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-violet-600/20 active:scale-95 transition-all">
                <i class="fa-solid fa-circle-plus"></i>
                <span>Registrar Computador</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- Barra de Filtros -->
    <div class="glass-panel rounded-2xl p-5 border border-white/5 bg-black/10">
        <form action="<?= BASE_URL ?>/computadores" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xxs font-bold text-slate-400 uppercase tracking-wider mb-2">Filtrar por Sala</label>
                <select name="sala" class="block w-full px-3 py-2 bg-black/45 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-1 focus:ring-violet-500">
                    <option value="">Todas las salas</option>
                    <?php foreach ($salas as $sala): ?>
                        <option value="<?= $sala['id_sala'] ?>" <?= ($filters['sala'] == $sala['id_sala']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sala['nombre_sala']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xxs font-bold text-slate-400 uppercase tracking-wider mb-2">Estado del Activo</label>
                <select name="estado" class="block w-full px-3 py-2 bg-black/45 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-1 focus:ring-violet-500">
                    <option value="">Todos los estados</option>
                    <option value="Operativo" <?= ($filters['estado'] == 'Operativo') ? 'selected' : '' ?>>Operativo</option>
                    <option value="En Mantenimiento" <?= ($filters['estado'] == 'En Mantenimiento') ? 'selected' : '' ?>>En Mantenimiento</option>
                    <option value="Obsoleto" <?= ($filters['estado'] == 'Obsoleto') ? 'selected' : '' ?>>Obsoleto</option>
                    <option value="Dado de Baja" <?= ($filters['estado'] == 'Dado de Baja') ? 'selected' : '' ?>>Dado de Baja</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-xl text-xs transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-filter"></i>
                    <span>Aplicar Filtros</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de Inventario de Computadores -->
    <div class="glass-panel rounded-2xl border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider bg-black/10">
                        <th class="py-4 px-4 text-center">ID</th>
                        <th class="py-4 px-4">Placas (SED/FSE)</th>
                        <th class="py-4 px-4">Serial / Modelo</th>
                        <th class="py-4 px-4">Ubicación Actual</th>
                        <th class="py-4 px-4">Estado</th>
                        <th class="py-4 px-4">Alertas Técnicas</th>
                        <th class="py-4 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($computadores)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-medium">No se encontraron computadores con los filtros seleccionados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($computadores as $comp): ?>
                            <?php 
                            // Detección de peligro por infraestructura (Polo a tierra o estabilizador ausente)
                            $salaEnRiesgo = (!$comp['tiene_polo_a_tierra'] || !$comp['tiene_estabilizador']) && ($comp['estado_activo'] === 'Operativo');
                            ?>
                            <tr class="border-b border-white/5 hover:bg-white/[0.01] transition-colors text-slate-300">
                                <td class="py-4 px-4 text-center text-slate-500 font-mono">#<?= $comp['id_computador'] ?></td>
                                <td class="py-4 px-4">
                                    <span class="block font-semibold text-white">SED: <?= htmlspecialchars($comp['placa_sed'] ?: 'N/A') ?></span>
                                    <span class="block text-[10px] text-slate-500">FSE: <?= htmlspecialchars($comp['placa_fse'] ?: 'N/A') ?></span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="block font-semibold text-white"><?= htmlspecialchars($comp['nombre_marca'] . ' ' . $comp['modelo']) ?></span>
                                    <span class="block text-[10px] text-slate-500 font-mono">S/N: <?= htmlspecialchars($comp['numero_serial']) ?></span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="flex items-center space-x-1.5 text-slate-300">
                                        <i class="fa-solid fa-location-dot text-violet-400"></i>
                                        <span><?= htmlspecialchars($comp['nombre_sala']) ?></span>
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <?php 
                                    $badgeClass = 'bg-slate-800 text-slate-400';
                                    if ($comp['estado_activo'] === 'Operativo') $badgeClass = 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400';
                                    if ($comp['estado_activo'] === 'En Mantenimiento') $badgeClass = 'bg-yellow-500/10 border-yellow-500/20 text-yellow-400';
                                    if ($comp['estado_activo'] === 'Obsoleto') $badgeClass = 'bg-indigo-500/10 border-indigo-500/20 text-indigo-400';
                                    if ($comp['estado_activo'] === 'Dado de Baja') $badgeClass = 'bg-rose-500/10 border-rose-500/20 text-rose-400';
                                    ?>
                                    <span class="px-2.5 py-0.5 rounded border text-[10px] font-semibold <?= $badgeClass ?>">
                                        <?= $comp['estado_activo'] ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($salaEnRiesgo): ?>
                                        <span class="inline-flex items-center space-x-1.5 text-rose-400 font-semibold text-[10px] animate-pulse bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20" title="Sala actual sin polo a tierra o sin estabilizador de voltaje.">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            <span>Riesgo de Daño</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-[10px] text-slate-500">Ninguna</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <!-- Ver Ficha / Hoja de Vida -->
                                        <a href="<?= BASE_URL ?>/computadores/ficha/<?= $comp['id_computador'] ?>" class="p-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg hover:text-white transition-all" title="Ver Hoja de Vida y Componentes">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                        
                                        <?php if ($canEdit && $comp['estado_activo'] !== 'Dado de Baja'): ?>
                                            <!-- Editar -->
                                            <a href="<?= BASE_URL ?>/computadores/editar/<?= $comp['id_computador'] ?>" class="p-1.5 bg-slate-800 hover:bg-violet-600/30 text-slate-300 rounded-lg hover:text-violet-400 transition-all border border-transparent hover:border-violet-500/20" title="Editar Activo">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
