<?php
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Encabezado -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/traslados" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Solicitar Traslado de Hardware</h2>
            <p class="text-xs text-slate-400 mt-1">Registrar movimiento de equipos entre laboratorios o salones de cómputo.</p>
        </div>
    </div>

    <!-- Formulario (Glassmorphism) -->
    <div class="glass-panel rounded-2xl p-8 border border-white/5 bg-black/10">
        <form action="<?= BASE_URL ?>/traslados/crear" method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selección de Computador -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Seleccione Computador a Trasladar *</label>
                    <select name="id_computador" required class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="">Seleccione equipo...</option>
                        <?php if (empty($computadores)): ?>
                            <option value="" disabled>No hay computadores en inventario.</option>
                        <?php else: ?>
                            <?php foreach ($computadores as $c): ?>
                                <option value="<?= $c['id_computador'] ?>">
                                    <?= htmlspecialchars($c['nombre_marca'] . ' ' . $c['modelo']) ?> (S/N: <?= htmlspecialchars($c['numero_serial']) ?>) - Sala actual: <?= htmlspecialchars($c['nombre_sala']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Selección de Sala Destino -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sala de Destino *</label>
                    <select name="id_sala_destino" required class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="">Seleccione sala destino...</option>
                        <?php foreach ($salas as $s): ?>
                            <option value="<?= $s['id_sala'] ?>">
                                <?= htmlspecialchars($s['nombre_sala']) ?> (Tiene Polo a Tierra: <?= $s['tiene_polo_a_tierra'] ? 'SI' : 'NO' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-[10px] text-slate-500 mt-1.5 block">Nota: Si la sala de destino carece de polo a tierra o estabilizador, se disparará automáticamente una alerta de infraestructura preventiva en el inventario.</span>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end space-x-3 border-t border-white/5 pt-6">
                <a href="<?= BASE_URL ?>/traslados" class="py-2.5 px-5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-all">
                    Cancelar
                </a>
                <button type="submit" class="py-2.5 px-6 bg-gradient-to-r from-violet-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs shadow-lg transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    <span>Enviar Solicitud</span>
                </button>
            </div>

        </form>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
