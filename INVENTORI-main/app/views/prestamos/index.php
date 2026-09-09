<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
$userId = Session::get('user_id');
$canDevel = in_array($role, ['Rector', 'Almacenista']);
?>

<div class="space-y-6">

    <!-- Encabezado de Sección -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Consola de Préstamos y Retornos</h2>
            <p class="text-xs text-slate-400 mt-1">Control de la tenencia física y custodia legal de terminales por parte de los docentes.</p>
        </div>
        
        <a href="<?= BASE_URL ?>/prestamos/crear" class="py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-cyan-500 hover:from-emerald-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-emerald-600/20 active:scale-95 transition-all btn-interactive">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>Nueva Solicitud</span>
        </a>
    </div>

    <!-- Listado de Préstamos -->
    <div class="glass-panel rounded-2xl border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider bg-black/10">
                        <th class="py-4 px-4 text-center">ID</th>
                        <th class="py-4 px-4">Computador Prestado</th>
                        <th class="py-4 px-4">Docente en Custodia</th>
                        <th class="py-4 px-4">Fecha Préstamo</th>
                        <th class="py-4 px-4">Devolución Estimada</th>
                        <th class="py-4 px-4">Estado</th>
                        <th class="py-4 px-4">Retorno/Auxiliar</th>
                        <?php if ($canDevel): ?>
                            <th class="py-4 px-4 text-center">Acciones</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($prestamos)): ?>
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500 font-medium">No se registran solicitudes de préstamo en la base de datos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($prestamos as $p): ?>
                            <?php 
                            // Ocultar préstamos de otros docentes si el rol actual es Docente
                            if ($role === 'Docente' && $p['id_docente'] != $userId) {
                                continue;
                            }
                            ?>
                            <tr class="border-b border-white/5 hover:bg-white/[0.01] transition-colors text-slate-300">
                                <td class="py-4 px-4 text-center text-slate-500 font-mono">#<?= $p['id_prestamo'] ?></td>
                                <td class="py-4 px-4">
                                    <span class="block font-semibold text-white"><?= htmlspecialchars($p['nombre_marca'] . ' ' . $p['modelo']) ?></span>
                                    <span class="block text-[10px] text-slate-500 font-mono">S/N: <?= htmlspecialchars($p['numero_serial']) ?></span>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="block font-semibold text-white"><?= htmlspecialchars($p['docente_nombres'] . ' ' . $p['docente_apellidos']) ?></span>
                                    <span class="block text-[10px] text-slate-500">Docente Responsable</span>
                                </td>
                                <td class="py-4 px-4 whitespace-nowrap"><?= $p['fecha_prestamo'] ?></td>
                                <td class="py-4 px-4 whitespace-nowrap font-medium text-slate-400"><?= $p['fecha_devolucion_estimada'] ?></td>
                                <td class="py-4 px-4">
                                    <?php 
                                    $badge = 'bg-slate-800 text-slate-400 border-white/5';
                                    if ($p['estado_prestamo'] === 'Activo') $badge = 'bg-yellow-500/10 border-yellow-500/20 text-yellow-400';
                                    if ($p['estado_prestamo'] === 'Devuelto') $badge = 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400';
                                    if ($p['estado_prestamo'] === 'Vencido') $badge = 'bg-rose-500/10 border-rose-500/20 text-rose-400 animate-pulse';
                                    if ($p['estado_prestamo'] === 'Siniestro') $badge = 'bg-red-950/20 border-red-500/20 text-red-500 font-bold';
                                    ?>
                                    <span class="px-2.5 py-0.5 rounded border text-[10px] font-semibold <?= $badge ?>">
                                        <?= $p['estado_prestamo'] ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <?php if ($p['fecha_devolucion_real']): ?>
                                        <span class="block text-slate-300"><?= $p['fecha_devolucion_real'] ?></span>
                                        <span class="block text-[10px] text-slate-500">Por: <?= htmlspecialchars($p['auxiliar_nombres'] . ' ' . $p['auxiliar_apellidos']) ?></span>
                                    <?php else: ?>
                                        <span class="text-slate-500 italic">Pendiente de retorno</span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($canDevel): ?>
                                    <td class="py-4 px-4 text-center whitespace-nowrap">
                                        <?php if ($p['estado_prestamo'] === 'Activo'): ?>
                                            <a href="<?= BASE_URL ?>/prestamos/devolver/<?= $p['id_prestamo'] ?>" class="py-1 px-3 bg-emerald-600 hover:bg-emerald-500 text-white font-semibold rounded-lg text-[10px] transition-all flex items-center justify-center space-x-1.5 shadow-md shadow-emerald-600/10 btn-interactive">
                                                <i class="fa-solid fa-rotate-left"></i>
                                                <span>Retorno</span>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-slate-600 text-xs">-</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
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
