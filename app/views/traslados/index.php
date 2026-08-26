<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
?>

<div class="space-y-6">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Protocolo Digitalizado de Traslados</h2>
            <p class="text-xs text-slate-400 mt-1">Consola transaccional de reasignación física de computadores para control de inventarios.</p>
        </div>
        
        <?php if ($role === 'Administrador'): ?>
            <a href="<?= BASE_URL ?>/traslados/crear" class="py-2.5 px-4 bg-gradient-to-r from-violet-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-violet-600/20 active:scale-95 transition-all">
                <i class="fa-solid fa-truck-ramp-box"></i>
                <span>Solicitar Traslado</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- Historial de Traslados -->
    <div class="glass-panel rounded-2xl border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider bg-black/10">
                        <th class="py-4 px-4 text-center">ID</th>
                        <th class="py-4 px-4">Computador</th>
                        <th class="py-4 px-4">Movimiento</th>
                        <th class="py-4 px-4">Solicitante</th>
                        <th class="py-4 px-4">Fecha/Solicitud</th>
                        <th class="py-4 px-4">Etiqueta QR Ubicación</th>
                        <th class="py-4 px-4">Estado</th>
                        <th class="py-4 px-4 text-center">Acciones / Aprobación</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($traslados)): ?>
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500 font-medium">No se registran solicitudes de traslado de hardware.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($traslados as $t): ?>
                            <tr class="border-b border-white/5 hover:bg-white/[0.01] transition-colors text-slate-300">
                                <td class="py-4 px-4 text-center text-slate-500 font-mono">#<?= $t['id_traslado'] ?></td>
                                <td class="py-4 px-4">
                                    <span class="block font-semibold text-white"><?= htmlspecialchars($t['nombre_marca'] . ' ' . $t['modelo']) ?></span>
                                    <span class="block text-[10px] text-slate-500 font-mono">Placa SED: <?= htmlspecialchars($t['placa_sed'] ?: 'N/A') ?></span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-slate-400"><?= htmlspecialchars($t['sala_origen_nombre']) ?></span>
                                        <i class="fa-solid fa-arrow-right text-violet-400 text-[10px]"></i>
                                        <span class="font-semibold text-white"><?= htmlspecialchars($t['sala_destino_nombre']) ?></span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="block font-semibold text-white"><?= htmlspecialchars($t['solicita_nombres'] . ' ' . $t['solicita_apellidos']) ?></span>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap"><?= $t['fecha_solicitud'] ?></td>
                                <td class="py-4 px-4">
                                    <?php if ($t['estado_traslado'] === 'Aprobado'): ?>
                                        <div class="flex items-center space-x-2 bg-slate-800/40 border border-white/5 px-2 py-1 rounded-lg w-fit">
                                            <i class="fa-solid fa-qrcode text-violet-400 text-sm"></i>
                                            <span class="text-[9px] font-mono text-slate-300"><?= $t['codigo_qr_generado'] ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-500 italic">Generado al aprobar</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-4">
                                    <?php 
                                    $badge = 'bg-slate-800 text-slate-400 border-white/5';
                                    if ($t['estado_traslado'] === 'Pendiente') $badge = 'bg-yellow-500/10 border-yellow-500/20 text-yellow-400';
                                    if ($t['estado_traslado'] === 'Aprobado') $badge = 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400';
                                    if ($t['estado_traslado'] === 'Rechazado') $badge = 'bg-rose-500/10 border-rose-500/20 text-rose-400';
                                    ?>
                                    <span class="px-2.5 py-0.5 rounded border text-[10px] font-semibold <?= $badge ?>">
                                        <?= $t['estado_traslado'] ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center whitespace-nowrap">
                                    <?php if ($t['estado_traslado'] === 'Pendiente'): ?>
                                        <?php if ($role === 'Administrador'): ?>
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="<?= BASE_URL ?>/traslados/autorizar/<?= $t['id_traslado'] ?>/aprobar" class="px-2 py-1 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-lg text-[10px] transition-all flex items-center space-x-1 shadow-md shadow-emerald-600/10">
                                                    <i class="fa-solid fa-check"></i>
                                                    <span>Aprobar</span>
                                                </a>
                                                <a href="<?= BASE_URL ?>/traslados/autorizar/<?= $t['id_traslado'] ?>/rechazar" class="px-2 py-1 bg-rose-600 hover:bg-rose-500 text-white font-semibold rounded-lg text-[10px] transition-all flex items-center space-x-1 shadow-md shadow-rose-600/10">
                                                    <i class="fa-solid fa-xmark"></i>
                                                    <span>Rechazar</span>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[10px] text-amber-400 font-semibold italic">Pendiente de Aprobación</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-[10px] text-slate-500">Autorizado por: <?= htmlspecialchars($t['autoriza_nombres'] . ' ' . $t['autoriza_apellidos']) ?></span>
                                    <?php endif; ?>
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
