<?php
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Encabezado -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/bajas" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Registrar Baja de Equipo y Certificado RAEE</h2>
            <p class="text-xs text-slate-400 mt-1">Dar de baja ambiental un equipo obsoleto o dañado, asociándole el acta del Consejo Directivo.</p>
        </div>
    </div>

    <!-- Formulario (Glassmorphism) -->
    <div class="glass-panel rounded-2xl p-8 border border-white/5 bg-black/10">
        <form action="<?= BASE_URL ?>/bajas/crear" method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selección de Computador -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Seleccione Computador a Dar de Baja *</label>
                    <select name="id_computador" required class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="">Seleccione equipo...</option>
                        <?php if (empty($computadores)): ?>
                            <option value="" disabled>No hay computadores aptos para baja en inventario.</option>
                        <?php else: ?>
                            <?php foreach ($computadores as $c): ?>
                                <option value="<?= $c['id_computador'] ?>">
                                    <?= htmlspecialchars($c['nombre_marca'] . ' ' . $c['modelo']) ?> (S/N: <?= htmlspecialchars($c['numero_serial']) ?>) - Placa SED: <?= htmlspecialchars($c['placa_sed'] ?: 'N/A') ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Número de Acta del Consejo Directivo -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Número de Acta del Consejo *</label>
                    <input type="text" name="numero_acta_consejo" required 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" placeholder="Ej. ACTA-004-2026">
                </div>

                <!-- Fecha de la Baja -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fecha de Decomiso *</label>
                    <input type="date" name="fecha_baja" required value="<?= date('Y-m-d') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs">
                </div>

                <!-- Entidad de Retoma -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Entidad de Retoma Autorizada *</label>
                    <select name="entidad_retoma" required class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="Computadores para Educar">Computadores para Educar</option>
                        <option value="EPM">EPM (Economía Circular)</option>
                        <option value="Gestor Autorizado">Gestor Autorizado</option>
                    </select>
                </div>

                <!-- Número Certificado RAEE -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Número de Certificado RAEE *</label>
                    <input type="text" name="numero_certificado_raee" required 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" placeholder="Ej. CERT-RAEE-9844">
                </div>
            </div>

            <!-- Observaciones -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Observaciones / Motivos del Decomiso</label>
                <textarea name="observaciones" rows="3" 
                    class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" 
                    placeholder="Ej. Equipo inutilizable por daño severo en tarjeta madre, obsoleto contablemente tras 5 años de vida útil..."></textarea>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end space-x-3 border-t border-white/5 pt-6">
                <a href="<?= BASE_URL ?>/bajas" class="py-2.5 px-5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-all">
                    Cancelar
                </a>
                <button type="submit" class="py-2.5 px-6 bg-gradient-to-r from-violet-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs shadow-lg transition-all flex items-center space-x-2">
                    <i class="fa-solid fa-recycle"></i>
                    <span>Completar Baja RAEE</span>
                </button>
            </div>

        </form>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
