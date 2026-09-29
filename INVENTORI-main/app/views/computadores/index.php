<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
$canEdit = in_array($role, ['Rector', 'Almacenista', 'Contralor', 'Docente', 'Administrador']);

// Group computadores into sections by category and sede
$equiposSecciones = [
    'Pedro Nel Ospina' => [],
    'Santa Margarita' => [],
    'Bachillerato Santa Margarita' => [],
    'Otras Salas' => [],
    'Laboratorios' => []
];

foreach ($computadores as $comp) {
    $nombreSala = strtolower($comp['nombre_sala']);
    $sedeComp = $comp['sede'] ?? $comp['sala_sede'] ?? 'Santa Margarita';

    if (str_contains($nombreSala, 'laboratorio')) {
        $equiposSecciones['Laboratorios'][] = $comp;
    } elseif ($sedeComp === 'Pedro Nel Ospina' || str_contains($nombreSala, 'pedro nel')) {
        $equiposSecciones['Pedro Nel Ospina'][] = $comp;
    } elseif ($sedeComp === 'Santa Margarita' && str_contains($nombreSala, 'santa margarita')) {
        $equiposSecciones['Santa Margarita'][] = $comp;
    } elseif ($sedeComp === 'Bachillerato Santa Margarita' || str_contains($nombreSala, 'bachillerato')) {
        $equiposSecciones['Bachillerato Santa Margarita'][] = $comp;
    } else {
        $equiposSecciones['Otras Salas'][] = $comp;
    }
}
?>

<div class="space-y-6">

    <!-- Encabezado de Sección -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-desktop text-emerald-400"></i>
                <span>Inventario de Equipos de Cómputo</span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">Control exhaustivo de terminales, licenciamiento y ubicación física por Sedes, Salas y Laboratorios.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
            <!-- Botón IMPRIMIR INVENTARIO (PDF) -->
            <a href="<?= BASE_URL ?>/computadores/imprimir?sede=<?= urlencode($filters['sede'] ?? '') ?>&sala=<?= urlencode($filters['sala'] ?? '') ?>&laboratorio=<?= urlencode($filters['laboratorio'] ?? '') ?>&estado=<?= urlencode($filters['estado'] ?? '') ?>" 
               target="_blank" 
               class="py-2.5 px-4 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-semibold rounded-xl text-xs flex items-center space-x-2 shadow-sm transition-all btn-interactive">
                <i class="fa-solid fa-file-pdf text-rose-400 text-sm"></i>
                <span>Imprimir Inventario</span>
            </a>

            <?php if ($canEdit): ?>
                <!-- Botón AGREGAR NUEVO EQUIPO -->
                <a href="<?= BASE_URL ?>/computadores/crear" 
                   class="py-2.5 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all btn-interactive border border-blue-400/30">
                    <i class="fa-solid fa-plus-circle text-sm"></i>
                    <span>+ Agregar Nuevo Equipo</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         RECUADRO 1: FILTRAR SALAS POR SEDE
    ════════════════════════════════════════════════════════════ -->
    <div class="glass-panel rounded-2xl p-5 border border-emerald-500/20 bg-slate-900/70 shadow-xl space-y-3">
        <div class="flex items-center space-x-2 border-b border-white/10 pb-2.5">
            <i class="fa-solid fa-school text-emerald-400 text-sm"></i>
            <h3 class="text-xs font-bold text-white uppercase tracking-wider">Filtrar Salas de Cómputo por Sede</h3>
        </div>
        <form action="<?= BASE_URL ?>/computadores" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <!-- 1. Filtrar por Sede -->
            <div>
                <label class="block text-[10px] font-bold text-emerald-400 uppercase tracking-wider mb-2">Filtrar por Sede</label>
                <select name="sede" class="block w-full px-3 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Todas las Sedes</option>
                    <option value="Santa Margarita" <?= ($filters['sede'] == 'Santa Margarita') ? 'selected' : '' ?>>Sede Santa Margarita</option>
                    <option value="Pedro Nel Ospina" <?= ($filters['sede'] == 'Pedro Nel Ospina') ? 'selected' : '' ?>>Sede Pedro Nel Ospina</option>
                    <option value="Bachillerato Santa Margarita" <?= ($filters['sede'] == 'Bachillerato Santa Margarita') ? 'selected' : '' ?>>Bachillerato Santa Margarita</option>
                    <option value="General / Otras" <?= ($filters['sede'] == 'General / Otras') ? 'selected' : '' ?>>Otras / General</option>
                </select>
            </div>

            <!-- 2. Filtrar por Sala -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Filtrar por Sala</label>
                <select name="sala" class="block w-full px-3 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Todas las Salas</option>
                    <?php foreach ($salas as $sala): ?>
                        <?php if (($sala['categoria'] ?? '') !== 'Laboratorios' && !str_contains(strtolower($sala['nombre_sala']), 'laboratorio')): ?>
                            <option value="<?= $sala['id_sala'] ?>" <?= ($filters['sala'] == $sala['id_sala']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sala['nombre_sala']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 3. Estado del Activo -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Estado del Activo</label>
                <select name="estado" class="block w-full px-3 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="">Todos los estados</option>
                    <option value="Operativo" <?= ($filters['estado'] == 'Operativo') ? 'selected' : '' ?>>Operativo</option>
                    <option value="En Mantenimiento" <?= ($filters['estado'] == 'En Mantenimiento') ? 'selected' : '' ?>>En Mantenimiento</option>
                    <option value="Obsoleto" <?= ($filters['estado'] == 'Obsoleto') ? 'selected' : '' ?>>Obsoleto</option>
                    <option value="Dado de Baja" <?= ($filters['estado'] == 'Dado de Baja') ? 'selected' : '' ?>>Dado de Baja</option>
                </select>
            </div>

            <!-- 4. Botón Aplicar Filtros -->
            <div>
                <button type="submit" class="w-full py-2.5 px-4 bg-slate-800 hover:bg-emerald-600/30 hover:border-emerald-500/50 border border-white/10 text-white font-semibold rounded-xl text-xs transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-95 btn-interactive">
                    <i class="fa-solid fa-filter text-emerald-400"></i>
                    <span>Aplicar Filtros</span>
                </button>
            </div>
        </form>
    </div>

    <!-- ════════════════════════════════════════════════════════════
         RECUADRO 2: FILTRAR LABORATORIOS
    ════════════════════════════════════════════════════════════ -->
    <div class="glass-panel rounded-2xl p-5 border border-emerald-600/40 bg-emerald-950/30 shadow-xl space-y-3">
        <div class="flex items-center space-x-2 border-b border-emerald-600/20 pb-2.5">
            <i class="fa-solid fa-flask-vial text-emerald-500 text-sm"></i>
            <h3 class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Filtrar Laboratorios Especializados</h3>
        </div>
        <form action="<?= BASE_URL ?>/computadores" method="GET" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
            <!-- 1. Filtrar por Laboratorio -->
            <div>
                <label class="block text-[10px] font-bold text-emerald-500 uppercase tracking-wider mb-2">Seleccionar Laboratorio</label>
                <select name="laboratorio" class="block w-full px-3 py-2.5 bg-black/60 border border-emerald-600/40 rounded-xl text-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600">
                    <option value="">Todos los Laboratorios</option>
                    <?php foreach ($salas as $sala): ?>
                        <?php if (($sala['categoria'] ?? '') === 'Laboratorios' || str_contains(strtolower($sala['nombre_sala']), 'laboratorio')): ?>
                            <option value="<?= $sala['id_sala'] ?>" <?= ($filters['laboratorio'] == $sala['id_sala']) ? 'selected' : '' ?>>
                                🧪 <?= htmlspecialchars($sala['nombre_sala']) ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- 2. Estado del Activo en Laboratorio -->
            <div>
                <label class="block text-[10px] font-bold text-slate-300 uppercase tracking-wider mb-2">Estado del Activo</label>
                <select name="estado" class="block w-full px-3 py-2.5 bg-black/60 border border-white/10 rounded-xl text-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600">
                    <option value="">Todos los estados</option>
                    <option value="Operativo" <?= ($filters['estado'] == 'Operativo') ? 'selected' : '' ?>>Operativo</option>
                    <option value="En Mantenimiento" <?= ($filters['estado'] == 'En Mantenimiento') ? 'selected' : '' ?>>En Mantenimiento</option>
                    <option value="Obsoleto" <?= ($filters['estado'] == 'Obsoleto') ? 'selected' : '' ?>>Obsoleto</option>
                    <option value="Dado de Baja" <?= ($filters['estado'] == 'Dado de Baja') ? 'selected' : '' ?>>Dado de Baja</option>
                </select>
            </div>

            <!-- 3. Botón Aplicar Filtros de Laboratorio -->
            <div>
                <button type="submit" class="w-full py-2.5 px-4 bg-emerald-800/40 hover:bg-emerald-700/60 border border-emerald-600/60 text-emerald-200 font-bold rounded-xl text-xs transition-all flex items-center justify-center space-x-2 cursor-pointer active:scale-95 btn-interactive shadow-lg shadow-emerald-950/50">
                    <i class="fa-solid fa-flask text-emerald-400"></i>
                    <span>Filtrar Laboratorios</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Renderizado por Secciones de Equipos (Salas por Sede + Sección Aparte Abajo para Laboratorios) -->
    <?php 
    $seccionesConfig = [
        'Pedro Nel Ospina' => ['title' => 'Equipos - Sede Pedro Nel Ospina', 'icon' => 'fa-building-columns', 'color' => 'text-emerald-400'],
        'Santa Margarita' => ['title' => 'Equipos - Sede Santa Margarita', 'icon' => 'fa-school-flag', 'color' => 'text-teal-400'],
        'Bachillerato Santa Margarita' => ['title' => 'Equipos - Bachillerato Santa Margarita', 'icon' => 'fa-graduation-cap', 'color' => 'text-cyan-400'],
        'Otras Salas' => ['title' => 'Equipos - Otras Salas y Espacios Digitales', 'icon' => 'fa-network-wired', 'color' => 'text-blue-400'],
        'Laboratorios' => ['title' => 'Equipos en Laboratorios Especializados', 'icon' => 'fa-flask-vial', 'color' => 'text-emerald-500']
    ];
    ?>

    <?php if (empty($computadores)): ?>
        <div class="glass-panel rounded-2xl border border-white/5 p-12 text-center text-slate-500 font-medium shadow-2xl">
            No se encontraron equipos con los filtros seleccionados.
        </div>
    <?php else: ?>
        <?php foreach ($seccionesConfig as $secKey => $secConf): ?>
            <?php if (!empty($equiposSecciones[$secKey])): ?>
                <div class="space-y-3 pt-2">
                    <!-- Titular de la Sección -->
                    <div class="flex items-center space-x-3 border-b border-white/10 pb-2">
                        <i class="fa-solid <?= $secConf['icon'] ?> <?= $secConf['color'] ?> text-base"></i>
                        <h3 class="text-sm font-bold text-white tracking-wide uppercase"><?= $secConf['title'] ?></h3>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-slate-400 font-mono"><?= count($equiposSecciones[$secKey]) ?> equipos</span>
                    </div>

                    <!-- Tabla de la Sección -->
                    <div class="glass-panel rounded-2xl border border-white/5 overflow-hidden shadow-2xl">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs border-collapse">
                                <thead>
                                    <tr class="border-b border-white/10 text-slate-400 font-bold uppercase tracking-wider bg-slate-900/80">
                                        <th class="py-3 px-4 text-center">ID</th>
                                        <th class="py-3 px-4">Placa SED</th>
                                        <th class="py-3 px-4">Serial / Modelo</th>
                                        <th class="py-3 px-4">Sede / Ubicación</th>
                                        <th class="py-3 px-4">Estado</th>
                                        <th class="py-3 px-4">Alertas Técnicas</th>
                                        <th class="py-3 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($equiposSecciones[$secKey] as $comp): ?>
                                        <?php 
                                        $salaEnRiesgo = (!$comp['tiene_polo_a_tierra'] || !$comp['tiene_estabilizador']) && ($comp['estado_activo'] === 'Operativo');
                                        ?>
                                        <tr class="border-b border-white/5 hover:bg-emerald-950/20 transition-colors text-slate-300">
                                            <td class="py-3.5 px-4 text-center text-slate-500 font-mono">#<?= $comp['id_computador'] ?></td>
                                            <td class="py-3.5 px-4">
                                                <span class="font-bold text-white px-2 py-0.5 rounded bg-white/5 border border-white/10"><?= htmlspecialchars($comp['placa_sed'] ?: 'Sin Placa') ?></span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="block font-bold text-white"><?= htmlspecialchars($comp['nombre_marca'] . ' ' . $comp['modelo']) ?></span>
                                                <span class="block text-[10px] text-slate-400 font-mono">S/N: <?= htmlspecialchars($comp['numero_serial']) ?></span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <span class="block text-[11px] font-semibold text-emerald-400"><?= htmlspecialchars($comp['sede'] ?? $comp['sala_sede'] ?? 'Santa Margarita') ?></span>
                                                <span class="flex items-center space-x-1.5 text-slate-400 text-[11px] mt-0.5">
                                                    <i class="fa-solid fa-location-dot text-blue-400"></i>
                                                    <span><?= htmlspecialchars($comp['nombre_sala']) ?></span>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <?php 
                                                $badgeClass = 'bg-slate-800 text-slate-400';
                                                if ($comp['estado_activo'] === 'Operativo') $badgeClass = 'bg-emerald-500/20 border-emerald-500/30 text-emerald-300';
                                                if ($comp['estado_activo'] === 'En Mantenimiento') $badgeClass = 'bg-yellow-500/20 border-yellow-500/30 text-yellow-300';
                                                if ($comp['estado_activo'] === 'Obsoleto') $badgeClass = 'bg-indigo-500/20 border-indigo-500/30 text-indigo-300';
                                                if ($comp['estado_activo'] === 'Dado de Baja') $badgeClass = 'bg-rose-500/20 border-rose-500/30 text-rose-300';
                                                ?>
                                                <span class="px-2.5 py-1 rounded-full border text-[10px] font-bold uppercase tracking-wider <?= $badgeClass ?>">
                                                    <?= $comp['estado_activo'] ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4">
                                                <?php if ($salaEnRiesgo): ?>
                                                    <span class="inline-flex items-center space-x-1.5 text-rose-400 font-semibold text-[10px] animate-pulse bg-rose-500/10 px-2 py-0.5 rounded border border-rose-500/20" title="Sala actual sin polo a tierra o sin estabilizador de voltaje.">
                                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                                        <span>Riesgo Eléctrico</span>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-[10px] text-slate-500">Normal</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="py-3.5 px-4 text-center">
                                                <div class="flex items-center justify-center space-x-2">
                                                    <!-- Ver Ficha / Hoja de Vida -->
                                                    <a href="<?= BASE_URL ?>/computadores/ficha/<?= $comp['id_computador'] ?>" 
                                                       class="p-2 bg-slate-800 hover:bg-emerald-600/30 text-slate-300 hover:text-emerald-300 rounded-xl transition-all border border-white/5 hover:border-emerald-500/40" 
                                                       title="Ver Hoja de Vida y Componentes">
                                                        <i class="fa-solid fa-file-invoice"></i>
                                                    </a>
                                                    
                                                    <?php if ($canEdit && $comp['estado_activo'] !== 'Dado de Baja'): ?>
                                                        <!-- Editar -->
                                                        <a href="<?= BASE_URL ?>/computadores/editar/<?= $comp['id_computador'] ?>" 
                                                           class="p-2 bg-slate-800 hover:bg-blue-600/30 text-slate-300 hover:text-blue-300 rounded-xl transition-all border border-white/5 hover:border-blue-500/40" 
                                                           title="Editar Equipo">
                                                            <i class="fa-solid fa-pen-to-square"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
