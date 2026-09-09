<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
$canEdit = in_array($role, ['Rector', 'Almacenista', 'Administrador']);

// Group rooms by category and sede for requested layout sections
$sections = [
    'Sede Pedro Nel Ospina' => [],
    'Sede Santa Margarita' => [],
    'Bachillerato Santa Margarita' => [],
    'Otras Salas' => [],
    'Laboratorios' => []
];

foreach ($salas as $s) {
    $cat = $s['categoria'] ?? 'Salas de Sistemas';
    $sede = $s['sede'] ?? 'Santa Margarita';

    if ($cat === 'Laboratorios' || str_contains(strtolower($s['nombre_sala']), 'laboratorio')) {
        $sections['Laboratorios'][] = $s;
    } elseif ($sede === 'Pedro Nel Ospina' || str_contains(strtolower($s['nombre_sala']), 'pedro nel')) {
        $sections['Sede Pedro Nel Ospina'][] = $s;
    } elseif ($sede === 'Santa Margarita' && str_contains(strtolower($s['nombre_sala']), 'santa margarita')) {
        $sections['Sede Santa Margarita'][] = $s;
    } elseif ($sede === 'Bachillerato Santa Margarita' || str_contains(strtolower($s['nombre_sala']), 'bachillerato')) {
        $sections['Bachillerato Santa Margarita'][] = $s;
    } else {
        $sections['Otras Salas'][] = $s;
    }
}
?>

<div class="space-y-8">
    
    <!-- Encabezado de Sección -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-school text-emerald-400"></i>
                <span>Infraestructura de Salas y Puestos</span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">Cumplimiento técnico según la norma NTC 4595 y control de riesgos de hardware por Sedes y Laboratorios.</p>
        </div>
        
        <?php if ($canEdit): ?>
            <!-- Botón de apertura de formulario de registro con Hover -->
            <button onclick="toggleForm('create-sala-form')" 
                    class="py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-semibold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-emerald-600/30 hover:scale-105 active:scale-95 transition-all btn-interactive">
                <i class="fa-solid fa-plus-circle text-sm"></i>
                <span>Agregar Nueva Sala</span>
            </button>
        <?php endif; ?>
    </div>

    <!-- Formulario para Crear Nueva Sala (Oculto por defecto) -->
    <?php if ($canEdit): ?>
        <div id="create-sala-form" class="hidden glass-panel rounded-2xl p-6 border border-emerald-500/30 bg-slate-900/90 shadow-2xl space-y-4">
            <div class="flex justify-between items-center border-b border-white/10 pb-3">
                <h3 class="text-sm font-bold text-emerald-400 uppercase tracking-wider flex items-center space-x-2">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>Registrar Nueva Sala o Laboratorio</span>
                </h3>
                <button type="button" onclick="toggleForm('create-sala-form')" class="text-slate-400 hover:text-white text-xs">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <form action="<?= BASE_URL ?>/salas/crear" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                
                <!-- Nombre de la Sala -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Nombre de la Sala / Espacio *</label>
                    <input type="text" name="nombre_sala" required placeholder="Ej. Sala EPM de Bachillerato" class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                </div>

                <!-- Sede -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Sede *</label>
                    <select name="sede" required class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Santa Margarita">Sede Santa Margarita</option>
                        <option value="Pedro Nel Ospina">Sede Pedro Nel Ospina</option>
                        <option value="Bachillerato Santa Margarita">Bachillerato Santa Margarita</option>
                        <option value="General / Otras">General / Otras</option>
                    </select>
                </div>

                <!-- Categoría / Tipo -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Tipo de Espacio *</label>
                    <select name="categoria" required class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Salas de Sistemas">Sala de Sistemas</option>
                        <option value="Salas Digitales">Sala Digital / Medellín Digital</option>
                        <option value="Salas General">Sala EPM / General</option>
                        <option value="Laboratorios">Laboratorio</option>
                    </select>
                </div>
                
                <!-- Opciones de Infraestructura -->
                <div class="md:col-span-3 flex flex-wrap gap-6 py-2">
                    <label class="flex items-center space-x-2 text-xs text-slate-300 cursor-pointer">
                        <input type="checkbox" name="tiene_polo_a_tierra" class="rounded bg-black border-white/10 text-emerald-500 focus:ring-emerald-500 w-4 h-4">
                        <span>Polo a Tierra</span>
                    </label>
                    <label class="flex items-center space-x-2 text-xs text-slate-300 cursor-pointer">
                        <input type="checkbox" name="tiene_estabilizador" class="rounded bg-black border-white/10 text-emerald-500 focus:ring-emerald-500 w-4 h-4">
                        <span>Estabilizador de Voltaje</span>
                    </label>
                    <label class="flex items-center space-x-2 text-xs text-slate-300 cursor-pointer">
                        <input type="checkbox" name="tiene_red_structured" class="rounded bg-black border-white/10 text-emerald-500 focus:ring-emerald-500 w-4 h-4">
                        <span>Red Estructurada RJ45</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white font-bold rounded-xl text-xs shadow-lg transition-all btn-interactive">
                        Guardar Sala
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Renderizado por Secciones Organizadas -->
    <?php 
    $sectionLabels = [
        'Sede Pedro Nel Ospina' => ['title' => 'Sede Pedro Nel Ospina', 'icon' => 'fa-building-columns', 'color' => 'text-emerald-400'],
        'Sede Santa Margarita' => ['title' => 'Sede Santa Margarita', 'icon' => 'fa-school-flag', 'color' => 'text-teal-400'],
        'Bachillerato Santa Margarita' => ['title' => 'Bachillerato Santa Margarita', 'icon' => 'fa-graduation-cap', 'color' => 'text-cyan-400'],
        'Otras Salas' => ['title' => 'Otras Salas y Espacios Digitales', 'icon' => 'fa-network-wired', 'color' => 'text-blue-400'],
        'Laboratorios' => ['title' => 'Laboratorios Especializados', 'icon' => 'fa-flask-vial', 'color' => 'text-emerald-500']
    ];
    ?>

    <?php foreach ($sectionLabels as $key => $secInfo): ?>
        <?php if (!empty($sections[$key])): ?>
            <div class="space-y-4 pt-2">
                <!-- Título de Sección -->
                <div class="flex items-center space-x-3 border-b border-white/10 pb-2">
                    <i class="fa-solid <?= $secInfo['icon'] ?> <?= $secInfo['color'] ?> text-lg"></i>
                    <h3 class="text-base font-bold text-white tracking-wide"><?= $secInfo['title'] ?></h3>
                    <span class="text-xs px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-slate-400 font-mono"><?= count($sections[$key]) ?> espacios</span>
                </div>

                <!-- Tarjetas de la Sección -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <?php foreach ($sections[$key] as $sala): ?>
                        <?php 
                        $cumpleSeguridad = $sala['tiene_polo_a_tierra'] && $sala['tiene_estabilizador'];
                        ?>
                        <div onclick="openSalaModal(<?= htmlspecialchars(json_encode($sala)) ?>)" 
                             class="sala-card-hover glass-panel rounded-2xl p-6 border <?= $cumpleSeguridad ? 'border-white/10' : 'border-rose-500/30' ?> bg-slate-900/70 flex flex-col justify-between space-y-6 relative group shadow-xl">
                            
                            <div>
                                <!-- Cabecera de Tarjeta -->
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h4 class="text-base font-bold text-white group-hover:text-emerald-300 transition-colors flex items-center space-x-2">
                                            <span><?= htmlspecialchars($sala['nombre_sala']) ?></span>
                                        </h4>
                                        <span class="text-[10px] text-emerald-400 font-semibold block mt-0.5">Sede: <?= htmlspecialchars($sala['sede'] ?? 'Santa Margarita') ?></span>
                                    </div>
                                    
                                    <!-- Indicador de Cumplimiento -->
                                    <?php if ($cumpleSeguridad): ?>
                                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[9px] font-bold uppercase tracking-wider">Segura</span>
                                    <?php else: ?>
                                        <span class="px-2.5 py-0.5 rounded-full bg-rose-500/20 border border-rose-500/30 text-rose-300 text-[9px] font-bold uppercase tracking-wider animate-pulse">Riesgo Eléctrico</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Detalles de Infraestructura -->
                                <div class="space-y-2.5 text-xs bg-black/30 p-3 rounded-xl border border-white/5">
                                    <div class="flex items-center justify-between text-slate-300">
                                        <span>Polo a Tierra:</span>
                                        <span class="flex items-center space-x-1.5 <?= $sala['tiene_polo_a_tierra'] ? 'text-emerald-400 font-semibold' : 'text-rose-400 font-semibold' ?>">
                                            <i class="fa-solid <?= $sala['tiene_polo_a_tierra'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                            <span><?= $sala['tiene_polo_a_tierra'] ? 'Disponible' : 'Ausente' ?></span>
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-slate-300">
                                        <span>Estabilizador:</span>
                                        <span class="flex items-center space-x-1.5 <?= $sala['tiene_estabilizador'] ? 'text-emerald-400 font-semibold' : 'text-rose-400 font-semibold' ?>">
                                            <i class="fa-solid <?= $sala['tiene_estabilizador'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                            <span><?= $sala['tiene_estabilizador'] ? 'Disponible' : 'Ausente' ?></span>
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-slate-300">
                                        <span>Red RJ45:</span>
                                        <span class="flex items-center space-x-1.5 <?= $sala['tiene_red_structured'] ? 'text-emerald-400 font-semibold' : 'text-rose-400 font-semibold' ?>">
                                            <i class="fa-solid <?= $sala['tiene_red_structured'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                            <span><?= $sala['tiene_red_structured'] ? 'Estructurada' : 'No instalada' ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de Acción de Tarjeta -->
                            <div class="border-t border-white/10 pt-4 flex flex-col space-y-2">
                                <div class="flex justify-between items-center text-[10px] text-slate-400">
                                    <span>Última revisión:</span>
                                    <span class="font-mono text-slate-300"><?= $sala['ultima_revision_infraestructura'] ?: 'Sin registro' ?></span>
                                </div>

                                <button type="button" 
                                        class="w-full py-2 bg-slate-800 hover:bg-emerald-600/30 hover:border-emerald-500/50 border border-white/10 text-emerald-300 font-semibold rounded-xl text-xs transition-all flex items-center justify-center space-x-2 shadow-md">
                                    <i class="fa-solid fa-circle-info text-emerald-400"></i>
                                    <span>Ver Información Completa</span>
                                </button>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

</div>

<!-- Modal para Mostrar la Información Completa al Hacer Clic -->
<div id="sala-modal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="glass-panel rounded-3xl p-6 md:p-8 max-w-xl w-full border border-emerald-500/40 bg-slate-900/95 shadow-2xl space-y-6 animate-in fade-in zoom-in duration-200">
        
        <div class="flex justify-between items-start border-b border-white/10 pb-4">
            <div>
                <span id="modal-sede" class="text-xs text-emerald-400 font-bold uppercase tracking-wider block"></span>
                <h3 id="modal-title" class="text-xl font-extrabold text-white mt-1"></h3>
                <span id="modal-id" class="text-[10px] text-slate-400 font-mono"></span>
            </div>
            <button onclick="closeSalaModal()" class="p-2 rounded-xl bg-slate-800 text-slate-400 hover:text-white hover:bg-slate-700 text-sm">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <!-- Estado Completo y Ficha Técnica -->
        <div class="space-y-4">
            <h4 class="text-xs font-bold text-slate-300 uppercase tracking-wider">Estado Técnico e Infraestructura</h4>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="p-3 bg-black/40 rounded-xl border border-white/5 text-center">
                    <span class="block text-[10px] text-slate-400 uppercase mb-1">Polo a Tierra</span>
                    <span id="modal-polo" class="text-xs font-bold"></span>
                </div>
                <div class="p-3 bg-black/40 rounded-xl border border-white/5 text-center">
                    <span class="block text-[10px] text-slate-400 uppercase mb-1">Estabilizador</span>
                    <span id="modal-estabilizador" class="text-xs font-bold"></span>
                </div>
                <div class="p-3 bg-black/40 rounded-xl border border-white/5 text-center">
                    <span class="block text-[10px] text-slate-400 uppercase mb-1">Red RJ45</span>
                    <span id="modal-red" class="text-xs font-bold"></span>
                </div>
            </div>

            <div class="p-4 bg-emerald-950/20 border border-emerald-500/20 rounded-2xl text-xs space-y-2">
                <div class="flex justify-between text-slate-300">
                    <span>Norma de Cumplimiento Técnico:</span>
                    <span class="font-bold text-white">NTC 4595 Institucional</span>
                </div>
                <div class="flex justify-between text-slate-300">
                    <span>Última Inspección Técnica:</span>
                    <span id="modal-revision" class="font-mono text-emerald-300"></span>
                </div>
                <div class="flex justify-between text-slate-300">
                    <span>Diagnóstico de Riesgo Hardware:</span>
                    <span id="modal-diagnostico" class="font-bold"></span>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3 pt-2 border-t border-white/10">
            <button onclick="closeSalaModal()" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 font-semibold rounded-xl text-xs">
                Cerrar
            </button>
            <a id="modal-filter-link" href="#" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg transition-all">
                Ver Equipos en esta Sala →
            </a>
        </div>

    </div>
</div>

<script>
    function toggleForm(id) {
        const form = document.getElementById(id);
        if (form) form.classList.toggle('hidden');
    }

    function openSalaModal(sala) {
        document.getElementById('modal-title').textContent = sala.nombre_sala;
        document.getElementById('modal-sede').textContent = 'Sede: ' + (sala.sede || 'Santa Margarita');
        document.getElementById('modal-id').textContent = 'ID Sala: #' + sala.id_sala + ' • Categoria: ' + (sala.categoria || 'Salas');
        document.getElementById('modal-revision').textContent = sala.ultima_revision_infraestructura || 'Sin registro';

        const poloEl = document.getElementById('modal-polo');
        poloEl.textContent = sala.tiene_polo_a_tierra ? '✓ Disponible' : '✕ Ausente';
        poloEl.className = 'text-xs font-bold ' + (sala.tiene_polo_a_tierra ? 'text-emerald-400' : 'text-rose-400');

        const estEl = document.getElementById('modal-estabilizador');
        estEl.textContent = sala.tiene_estabilizador ? '✓ Disponible' : '✕ Ausente';
        estEl.className = 'text-xs font-bold ' + (sala.tiene_estabilizador ? 'text-emerald-400' : 'text-rose-400');

        const redEl = document.getElementById('modal-red');
        redEl.textContent = sala.tiene_red_structured ? '✓ Estructurada' : '✕ No instalada';
        redEl.className = 'text-xs font-bold ' + (sala.tiene_red_structured ? 'text-emerald-400' : 'text-rose-400');

        const diagEl = document.getElementById('modal-diagnostico');
        if (sala.tiene_polo_a_tierra && sala.tiene_estabilizador) {
            diagEl.textContent = 'Segura / Protegida';
            diagEl.className = 'font-bold text-emerald-400';
        } else {
            diagEl.textContent = 'Riesgo Eléctrico por Ausencia de Protección';
            diagEl.className = 'font-bold text-rose-400';
        }

        document.getElementById('modal-filter-link').href = '<?= BASE_URL ?>/computadores?sala=' + sala.id_sala;
        document.getElementById('sala-modal').classList.remove('hidden');
    }

    function closeSalaModal() {
        document.getElementById('sala-modal').classList.add('hidden');
    }
</script>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
