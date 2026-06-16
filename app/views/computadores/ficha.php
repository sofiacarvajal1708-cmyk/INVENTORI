<?php
require_once APP_ROOT . '/views/layout/header.php';
$comp = $data['computador'];
$components = $data['componentes'];
$depr = $data['depreciacion'];
$role = Session::get('role_name');
$canEdit = in_array($role, ['Rector', 'Almacenista']);

// Detección de peligro por infraestructura
$salaEnRiesgo = (!$comp['tiene_polo_a_tierra'] || !$comp['tiene_estabilizador']) && ($comp['estado_activo'] === 'Operativo');
?>

<div class="space-y-8">
    
    <!-- Botón Volver y Título -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/computadores" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Hoja de Vida de Activo Fijo</h2>
            <p class="text-xs text-slate-400 mt-1">Detalle fiscal, técnico e histórico de la terminal.</p>
        </div>
    </div>

    <!-- Alerta de riesgo eléctrico -->
    <?php if ($salaEnRiesgo): ?>
        <div class="px-5 py-4 rounded-2xl border border-rose-500/20 bg-rose-950/20 text-rose-300 flex items-start space-x-3 glass-panel pulse-glow">
            <i class="fa-solid fa-circle-exclamation text-rose-400 text-xl mt-0.5"></i>
            <div>
                <h4 class="font-bold text-sm">Alerta de Seguridad Eléctrica (NTC 4595)</h4>
                <p class="text-xs text-rose-400/90 mt-1">Este computador operativo está conectado en la sala <span class="font-bold text-white">"<?= htmlspecialchars($comp['nombre_sala']) ?>"</span> la cual carece de **Polo a Tierra** y/o **Estabilizador de Voltaje**. Peligro alto de cortocircuito o sobrecarga.</p>
            </div>
        </div>
    <?php endif; ?>

    <!-- Fila de Información General y Lógica Fiscal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Bloque 1: Ficha Técnica (Hardware) -->
        <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 space-y-4 lg:col-span-2">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-microchip text-violet-400"></i>
                <span>Especificaciones de Hardware</span>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Marca y Modelo:</span>
                    <span class="text-white text-sm font-bold"><?= htmlspecialchars($comp['nombre_marca'] . ' ' . $comp['modelo']) ?></span>
                </div>
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Número de Serial:</span>
                    <span class="text-white text-sm font-mono"><?= htmlspecialchars($comp['numero_serial']) ?></span>
                </div>
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Placa SED:</span>
                    <span class="text-white"><?= htmlspecialchars($comp['placa_sed'] ?: 'No asignada') ?></span>
                </div>
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Placa FSE:</span>
                    <span class="text-white"><?= htmlspecialchars($comp['placa_fse'] ?: 'No asignada') ?></span>
                </div>
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Procesador Central (CPU):</span>
                    <span class="text-white"><?= htmlspecialchars($comp['procesador'] ?: 'No especificado') ?></span>
                </div>
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Sistema Operativo (OS):</span>
                    <span class="text-white"><?= htmlspecialchars($comp['licenciamiento'] ?: 'No licenciado / Libre') ?></span>
                </div>
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Sala / Ubicación Actual:</span>
                    <span class="text-white font-semibold flex items-center space-x-1">
                        <i class="fa-solid fa-door-open text-violet-400"></i>
                        <span><?= htmlspecialchars($comp['nombre_sala']) ?></span>
                    </span>
                </div>
                <div class="space-y-1">
                    <span class="block text-slate-400 font-semibold">Estado Activo:</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300">
                        <?= $comp['estado_activo'] ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Bloque 2: Ficha Contable / Control Fiscal (FSE) -->
        <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-calculator text-cyan-400"></i>
                <span>Cálculo Contable y Depreciación</span>
            </h3>
            <div class="space-y-3.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400">Valor de Adquisición:</span>
                    <span class="font-bold text-white">$<?= number_format($comp['valor_historico'], 2) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Vida Útil Parametrizada:</span>
                    <span class="font-bold text-white"><?= $comp['vida_util_anos'] ?> Años</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Fecha Adquisición:</span>
                    <span class="font-semibold text-slate-300"><?= $comp['fecha_adquisicion'] ?></span>
                </div>
                
                <div class="border-t border-white/5 my-2 pt-2"></div>
                
                <!-- Datos calculados por la vista SQL -->
                <?php if ($depr): ?>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Depreciación Anual ($D_a$):</span>
                        <span class="font-bold text-violet-400">$<?= number_format($depr['depreciacion_anual_Da'], 2) ?></span>
                    </div>
                    <div class="flex justify-between p-2 bg-violet-600/10 rounded-lg border border-violet-500/20 mt-1">
                        <span class="text-slate-300 font-semibold">Valor Neto en Libros:</span>
                        <span class="font-extrabold text-white">$<?= number_format($depr['valor_neto_libros'], 2) ?></span>
                    </div>
                <?php else: ?>
                    <!-- Fallback en caso de que la vista no devuelva datos o el computador esté de baja -->
                    <div class="p-3 bg-slate-800/40 rounded-xl text-center text-slate-400 text-xxs">
                        Cálculos no disponibles para equipos inactivos o dados de baja.
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    <!-- GESTIÓN DE COMPONENTES INTERNOS (Para prevenir la pérdida hormiga) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Listado de Piezas/Componentes -->
        <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 lg:col-span-2 space-y-4">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class="fa-solid fa-list-check text-cyan-400"></i>
                    <span>Componentes Internos Registrados</span>
                </div>
                <span class="text-[10px] text-slate-500">Total: <?= count($components) ?> piezas</span>
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider">
                            <th class="py-2.5 px-2">Tipo</th>
                            <th class="py-2.5 px-2">Serial Componente</th>
                            <th class="py-2.5 px-2">Especificaciones</th>
                            <th class="py-2.5 px-2">Estado</th>
                            <?php if ($canEdit && $comp['estado_activo'] !== 'Dado de Baja'): ?>
                                <th class="py-2.5 px-2 text-center">Acciones</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($components)): ?>
                            <tr>
                                <td colspan="5" class="py-6 text-center text-slate-500 italic">No hay componentes internos registrados para este equipo.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($components as $c): ?>
                                <tr class="border-b border-white/5 text-slate-300 hover:bg-white/[0.01]">
                                    <td class="py-3 px-2 font-semibold text-white whitespace-nowrap"><?= $c['tipo_componente'] ?></td>
                                    <td class="py-3 px-2 font-mono whitespace-nowrap"><?= htmlspecialchars($c['serial_componente']) ?></td>
                                    <td class="py-3 px-2 text-slate-400"><?= htmlspecialchars($c['especificaciones_tecnicas']) ?></td>
                                    <td class="py-3 px-2 whitespace-nowrap">
                                        <?php 
                                        $badge = 'bg-slate-800 text-slate-400';
                                        if ($c['estado_componente'] === 'Instalado') $badge = 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400';
                                        if ($c['estado_componente'] === 'Removido') $badge = 'bg-yellow-500/10 border-yellow-500/20 text-yellow-400';
                                        if ($c['estado_componente'] === 'Falla') $badge = 'bg-rose-500/10 border-rose-500/20 text-rose-400';
                                        ?>
                                        <span class="px-2 py-0.5 rounded border text-[10px] font-semibold <?= $badge ?>">
                                            <?= $c['estado_componente'] ?>
                                        </span>
                                    </td>
                                    <?php if ($canEdit && $comp['estado_activo'] !== 'Dado de Baja'): ?>
                                        <td class="py-3 px-2 text-center">
                                            <form action="<?= BASE_URL ?>/computadores/ficha/<?= $comp['id_computador'] ?>" method="POST" class="inline-block">
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="id_componente" value="<?= $c['id_componente'] ?>">
                                                <select name="estado_componente" onchange="this.form.submit()" class="bg-slate-800 text-slate-300 border border-white/10 rounded px-1 py-0.5 text-[10px] focus:outline-none focus:ring-1 focus:ring-violet-500">
                                                    <option value="Instalado" <?= $c['estado_componente'] === 'Instalado' ? 'selected' : '' ?>>Instalado</option>
                                                    <option value="Removido" <?= $c['estado_componente'] === 'Removido' ? 'selected' : '' ?>>Removido</option>
                                                    <option value="Falla" <?= $c['estado_componente'] === 'Falla' ? 'selected' : '' ?>>Falla</option>
                                                </select>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Instalar Nueva Ficha de Componente (Solo Almacenista/Rector) -->
        <?php if ($canEdit && $comp['estado_activo'] !== 'Dado de Baja'): ?>
            <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 space-y-4">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-plus-circle text-violet-400"></i>
                    <span>Instalar Componente Interno</span>
                </h3>
                <form action="<?= BASE_URL ?>/computadores/ficha/<?= $comp['id_computador'] ?>" method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="add_component">

                    <!-- Tipo Componente -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Tipo de Pieza *</label>
                        <select name="tipo_componente" required class="block w-full px-3 py-2 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-1 focus:ring-violet-500">
                            <option value="RAM">Memoria RAM</option>
                            <option value="SSD">Disco de Estado Sólido (SSD)</option>
                            <option value="Disco Duro">Disco Duro Mecánico (HDD)</option>
                            <option value="Procesador">Procesador</option>
                            <option value="Tarjeta de Red">Tarjeta de Red</option>
                        </select>
                    </div>

                    <!-- Serial Componente -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Número de Serial *</label>
                        <input type="text" name="serial_componente" required class="block w-full px-3 py-2 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-violet-500 text-xs" placeholder="Ej. RAM-5526X89L">
                    </div>

                    <!-- Especificaciones técnicas -->
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-2">Especificaciones Técnicas</label>
                        <input type="text" name="especificaciones_tecnicas" class="block w-full px-3 py-2 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-violet-500 text-xs" placeholder="Ej. 16GB DDR4 3200MHz Kingston">
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-gradient-to-r from-violet-600 to-cyan-500 text-white font-semibold rounded-xl text-xs hover:from-violet-500 hover:to-cyan-400 shadow-lg transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-screwdriver"></i>
                        <span>Instalar Pieza</span>
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 text-center flex flex-col justify-center items-center text-slate-500 space-y-2">
                <i class="fa-solid fa-lock text-3xl text-slate-700"></i>
                <p class="text-xs">No se pueden agregar piezas a computadores inactivos o dados de baja.</p>
            </div>
        <?php endif; ?>

    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
