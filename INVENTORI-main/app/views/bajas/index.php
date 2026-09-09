<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
?>

<div class="space-y-6">

    <!-- Encabezado -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-xl font-bold text-white">Disposición Final y Control RAEE (Ley 1672)</h2>
            <p class="text-xs text-slate-400 mt-1">Control fiscal y ecológico de equipos dados de baja por obsolescencia o daño irreparable.</p>
        </div>
        
        <?php if ($role === 'Administrador'): ?>
            <a href="<?= BASE_URL ?>/bajas/crear" class="py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-cyan-500 hover:from-emerald-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs flex items-center space-x-2 shadow-lg shadow-emerald-600/20 active:scale-95 transition-all btn-interactive">
                <i class="fa-solid fa-circle-minus"></i>
                <span>Registrar Baja RAEE</span>
            </a>
        <?php endif; ?>
    </div>

    <!-- Historial de Bajas RAEE -->
    <div class="glass-panel rounded-2xl border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider bg-black/10">
                        <th class="py-4 px-4 text-center">ID Baja</th>
                        <th class="py-4 px-4">Computador Decomisado</th>
                        <th class="py-4 px-4">Acta de Consejo</th>
                        <th class="py-4 px-4">Fecha de Baja</th>
                        <th class="py-4 px-4">Gestor de Retoma</th>
                        <th class="py-4 px-4">Nº Certificado RAEE</th>
                        <th class="py-4 px-4">Observaciones / Motivos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($bajas)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-medium">No se registran actas de devaluación o bajas RAEE en el sistema.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($bajas as $b): ?>
                            <tr class="border-b border-white/5 hover:bg-white/[0.01] transition-colors text-slate-300">
                                <td class="py-4 px-4 text-center text-slate-500 font-mono">#<?= $b['id_baja'] ?></td>
                                <td class="py-4 px-4">
                                    <span class="block font-semibold text-white"><?= htmlspecialchars($b['nombre_marca'] . ' ' . $b['modelo']) ?></span>
                                    <span class="block text-[10px] text-slate-500 font-mono">Serial: <?= htmlspecialchars($b['numero_serial']) ?></span>
                                </td>
                                <td class="py-4 px-4 font-semibold text-white"><?= htmlspecialchars($b['numero_acta_consejo']) ?></td>
                                <td class="py-4 px-4 whitespace-nowrap"><?= $b['fecha_baja'] ?></td>
                                <td class="py-4 px-4">
                                    <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 font-semibold border border-white/5 text-[10px]">
                                        <?= htmlspecialchars($b['entidad_retoma']) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-emerald-400 font-semibold font-mono"><?= htmlspecialchars($b['numero_certificado_raee']) ?></td>
                                <td class="py-4 px-4 text-slate-400 italic">"<?= htmlspecialchars($b['observaciones']) ?>"</td>
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
