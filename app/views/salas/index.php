<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
$canEdit = ($role === 'Administrador');
?>

<div class="space-y-8">
    
    <!-- Encabezado de Sección -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Infraestructura de Salas y Puestos</h2>
            <p class="text-xs text-slate-400 mt-1">Cumplimiento técnico según la norma NTC 4595 y control de riesgos de hardware.</p>
        </div>
        
        <?php if ($canEdit): ?>
            <!-- Botón de apertura de formulario de registro -->
            <button onclick="toggleForm('create-sala-form')" class="py-2.5 px-4 bg-gradient-to-r from-violet-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-violet-600/20 active:scale-95 transition-all">
                <i class="fa-solid fa-plus-circle"></i>
                <span>Registrar Nueva Sala</span>
            </button>
        <?php endif; ?>
    </div>

    <!-- Formulario para Crear Sala (Oculto por defecto) -->
    <?php if ($canEdit): ?>
        <div id="create-sala-form" class="hidden glass-panel rounded-2xl p-6 border border-white/5 bg-black/20">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Registrar Nueva Sala de Cómputo</h3>
            <form action="<?= BASE_URL ?>/salas/crear" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nombre de la Sala</label>
                    <input type="text" name="nombre_sala" required class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs">
                </div>
                
                <div class="flex flex-col space-y-2">
                    <label class="flex items-center space-x-2 text-xs text-slate-300">
                        <input type="checkbox" name="tiene_polo_a_tierra" class="rounded bg-black/35 border-white/10 text-violet-600 focus:ring-violet-500">
                        <span>Polo a Tierra</span>
                    </label>
                    <label class="flex items-center space-x-2 text-xs text-slate-300">
                        <input type="checkbox" name="tiene_estabilizador" class="rounded bg-black/35 border-white/10 text-violet-600 focus:ring-violet-500">
                        <span>Estabilizador</span>
                    </label>
                    <label class="flex items-center space-x-2 text-xs text-slate-300">
                        <input type="checkbox" name="tiene_red_structured" class="rounded bg-black/35 border-white/10 text-violet-600 focus:ring-violet-500">
                        <span>Red Estructurada RJ45</span>
                    </label>
                </div>

                <div>
                    <button type="submit" class="w-full py-2.5 px-4 bg-violet-600 hover:bg-violet-500 text-white font-semibold rounded-xl text-xs shadow-lg transition-all">
                        Guardar Sala
                    </button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <!-- Listado de Salas en Tarjetas Premium -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php if (empty($salas)): ?>
            <div class="col-span-3 text-center py-12 text-slate-500 glass-panel rounded-2xl">
                <i class="fa-solid fa-folder-open text-3xl mb-3 text-slate-600"></i>
                <p class="text-sm">No hay salas de cómputo registradas en el sistema.</p>
            </div>
        <?php else: ?>
            <?php foreach ($salas as $sala): ?>
                <?php 
                // Comprobar si cumple con tomas seguras (polo a tierra y estabilizador)
                $cumpleSeguridad = $sala['tiene_polo_a_tierra'] && $sala['tiene_estabilizador'];
                ?>
                <div class="glass-panel rounded-2xl p-6 border <?= $cumpleSeguridad ? 'border-white/5' : 'border-rose-500/20' ?> bg-gradient-to-b from-white/[0.01] to-black/10 flex flex-col justify-between space-y-6">
                    
                    <div>
                        <!-- Cabecera de la Tarjeta -->
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-base font-bold text-white"><?= htmlspecialchars($sala['nombre_sala']) ?></h3>
                                <span class="text-[10px] text-slate-500">ID Sala: #<?= $sala['id_sala'] ?></span>
                            </div>
                            
                            <!-- Indicador de Cumplimiento de Norma -->
                            <?php if ($cumpleSeguridad): ?>
                                <span class="px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[9px] font-bold uppercase">Segura</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-[9px] font-bold uppercase animate-pulse">Riesgo Eléctrico</span>
                            <?php endif; ?>
                        </div>

                        <!-- Estado Técnico de la Infraestructura -->
                        <div class="space-y-2 text-xs">
                            <!-- Polo a tierra -->
                            <div class="flex items-center justify-between text-slate-300">
                                <span>Polo a tierra:</span>
                                <span class="flex items-center space-x-1.5 <?= $sala['tiene_polo_a_tierra'] ? 'text-emerald-400' : 'text-rose-400' ?>">
                                    <i class="fa-solid <?= $sala['tiene_polo_a_tierra'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                    <span><?= $sala['tiene_polo_a_tierra'] ? 'Disponible' : 'Ausente' ?></span>
                                </span>
                            </div>

                            <!-- Estabilizador -->
                            <div class="flex items-center justify-between text-slate-300">
                                <span>Estabilizador:</span>
                                <span class="flex items-center space-x-1.5 <?= $sala['tiene_estabilizador'] ? 'text-emerald-400' : 'text-rose-400' ?>">
                                    <i class="fa-solid <?= $sala['tiene_estabilizador'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                    <span><?= $sala['tiene_estabilizador'] ? 'Disponible' : 'Ausente' ?></span>
                                </span>
                            </div>

                            <!-- Red Estructurada -->
                            <div class="flex items-center justify-between text-slate-300">
                                <span>Red RJ45:</span>
                                <span class="flex items-center space-x-1.5 <?= $sala['tiene_red_structured'] ? 'text-emerald-400' : 'text-rose-400' ?>">
                                    <i class="fa-solid <?= $sala['tiene_red_structured'] ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                    <span><?= $sala['tiene_red_structured'] ? 'Estructurada' : 'Básica/No' ?></span>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Pie de la Tarjeta con Revisión y Edición -->
                    <div class="border-t border-white/5 pt-4">
                        <div class="flex justify-between items-center text-[10px] text-slate-500 mb-3">
                            <span>Última revisión técnica:</span>
                            <span class="font-medium text-slate-400"><?= $sala['ultima_revision_infraestructura'] ?: 'No registrada' ?></span>
                        </div>

                        <?php if ($canEdit): ?>
                            <!-- Botón para Editar Inline -->
                            <button onclick="toggleForm('edit-form-<?= $sala['id_sala'] ?>')" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-lg text-xs transition-all flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-screwdriver-wrench"></i>
                                <span>Actualizar Requerimientos</span>
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Formulario de Edición de Sala (Oculto) -->
                    <?php if ($canEdit): ?>
                        <div id="edit-form-<?= $sala['id_sala'] ?>" class="hidden pt-4 border-t border-white/5 space-y-3 bg-black/10 p-3 rounded-xl mt-2">
                            <form action="<?= BASE_URL ?>/salas/editar/<?= $sala['id_sala'] ?>" method="POST" class="space-y-3">
                                <div>
                                    <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Nombre</label>
                                    <input type="text" name="nombre_sala" value="<?= htmlspecialchars($sala['nombre_sala']) ?>" required class="block w-full px-3 py-1.5 bg-black/45 border border-white/10 rounded-lg text-slate-100 text-xs">
                                </div>

                                <div class="space-y-1.5">
                                    <label class="flex items-center space-x-2 text-xs text-slate-300">
                                        <input type="checkbox" name="tiene_polo_a_tierra" <?= $sala['tiene_polo_a_tierra'] ? 'checked' : '' ?> class="rounded bg-black border-white/10 text-violet-600 focus:ring-violet-500">
                                        <span>Polo a Tierra</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-xs text-slate-300">
                                        <input type="checkbox" name="tiene_estabilizador" <?= $sala['tiene_estabilizador'] ? 'checked' : '' ?> class="rounded bg-black border-white/10 text-violet-600 focus:ring-violet-500">
                                        <span>Estabilizador</span>
                                    </label>
                                    <label class="flex items-center space-x-2 text-xs text-slate-300">
                                        <input type="checkbox" name="tiene_red_structured" <?= $sala['tiene_red_structured'] ? 'checked' : '' ?> class="rounded bg-black border-white/10 text-violet-600 focus:ring-violet-500">
                                        <span>Red RJ45</span>
                                    </label>
                                </div>

                                <button type="submit" class="w-full py-1.5 bg-cyan-600 hover:bg-cyan-500 text-white font-semibold rounded-lg text-[10px] transition-all">
                                    Guardar Cambios
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<!-- Helper script para alternar visibilidad de formularios -->
<script>
    function toggleForm(id) {
        const form = document.getElementById(id);
        if (form) {
            form.classList.toggle('hidden');
        }
    }
</script>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
