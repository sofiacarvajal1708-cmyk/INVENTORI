<?php
require_once APP_ROOT . '/views/layout/header.php';
$p = $data['prestamo'];
?>

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Encabezado -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/prestamos" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Recepción y Retorno de Terminal</h2>
            <p class="text-xs text-slate-400 mt-1">Verificación de componentes internos y liberación de responsabilidad legal.</p>
        </div>
    </div>

    <!-- Información General -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
        <div>
            <span class="block text-slate-400 font-semibold mb-1">Computador a retornar:</span>
            <span class="text-sm font-bold text-white"><?= htmlspecialchars($p['nombre_marca'] . ' ' . $p['modelo']) ?></span>
            <span class="block text-[10px] text-slate-500 font-mono">Serial: <?= htmlspecialchars($p['numero_serial']) ?> | Placa SED: <?= htmlspecialchars($p['placa_sed'] ?: 'N/A') ?></span>
        </div>
        <div>
            <span class="block text-slate-400 font-semibold mb-1">Docente que retorna:</span>
            <span class="text-sm font-bold text-white"><?= htmlspecialchars($p['docente_nombres'] . ' ' . $p['docente_apellidos']) ?></span>
            <span class="block text-[10px] text-slate-500">Cédula: <?= htmlspecialchars($p['docente_documento']) ?> | Correo: <?= htmlspecialchars($p['docente_email']) ?></span>
        </div>
    </div>

    <!-- Formulario de Retorno -->
    <div class="glass-panel rounded-2xl p-8 border border-white/5 bg-black/10">
        <form action="<?= BASE_URL ?>/prestamos/devolver/<?= $p['id_prestamo'] ?>" method="POST" class="space-y-6">
            
            <!-- Lista de Chequeo Técnico (Para evitar pérdida hormiga) -->
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4 flex items-center space-x-2">
                    <i class="fa-solid fa-list-check text-emerald-400"></i>
                    <span>Lista de Chequeo de Componentes y Estado</span>
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs bg-black/25 p-4 rounded-xl border border-white/5">
                    <label class="flex items-center space-x-3 text-slate-300">
                        <input type="checkbox" required class="rounded bg-black border-white/10 text-emerald-600 focus:ring-emerald-500">
                        <span>[ ] Memoria RAM verificada e instalada</span>
                    </label>
                    <label class="flex items-center space-x-3 text-slate-300">
                        <input type="checkbox" required class="rounded bg-black border-white/10 text-emerald-600 focus:ring-emerald-500">
                        <span>[ ] Unidad de almacenamiento (SSD/HDD) presente</span>
                    </label>
                    <label class="flex items-center space-x-3 text-slate-300">
                        <input type="checkbox" required class="rounded bg-black border-white/10 text-emerald-600 focus:ring-emerald-500">
                        <span>[ ] Teclado y pantalla sin fisuras ni daños físicos</span>
                    </label>
                    <label class="flex items-center space-x-3 text-slate-300">
                        <input type="checkbox" required class="rounded bg-black border-white/10 text-emerald-600 focus:ring-emerald-500">
                        <span>[ ] Cargador y adaptador eléctrico devueltos</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Estado Final de Transacción -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Estado Final de Retorno *</label>
                    <select name="estado_prestamo" required class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500">
                        <option value="Devuelto">Devuelto (Correctamente recibido)</option>
                        <option value="Siniestro">Siniestro (Pérdida, Robo o Daño Irreparable)</option>
                    </select>
                </div>

                <!-- Detalle Auxiliar -->
                <div class="text-xs text-slate-400 flex flex-col justify-end">
                    <p>Auxiliar Receptor: <span class="font-semibold text-white"><?= Session::get('user_name') ?></span></p>
                </div>
            </div>

            <!-- Observaciones -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Observaciones del Estado de Retorno</label>
                <textarea name="observaciones_retorno" rows="3" 
                    class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500 text-xs" 
                    placeholder="Ej. El computador retorna en perfecto estado con todos sus componentes. Cargador original incluido..."></textarea>
            </div>

            <!-- Botones -->
            <div class="flex items-center justify-end space-x-3 border-t border-white/5 pt-6">
                <a href="<?= BASE_URL ?>/prestamos" class="py-2.5 px-5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-all">
                    Cancelar
                </a>
                <button type="submit" class="py-2.5 px-6 bg-gradient-to-r from-emerald-600 to-cyan-500 hover:from-emerald-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs shadow-lg transition-all flex items-center space-x-2 btn-interactive">
                    <i class="fa-solid fa-clipboard-check"></i>
                    <span>Procesar Retorno</span>
                </button>
            </div>

        </form>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
