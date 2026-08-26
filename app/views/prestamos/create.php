<?php
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Encabezado -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/prestamos" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Solicitud de Préstamo de Terminales</h2>
            <p class="text-xs text-slate-400 mt-1">Llenar el formulario para autorizar la asignación física de equipos.</p>
        </div>
    </div>

    <!-- Advertencia Jurídico-Legal (Premium Detail) -->
    <div class="glass-panel rounded-2xl p-5 border border-amber-500/20 bg-amber-950/20 text-amber-300 text-xs space-y-2">
        <h4 class="font-bold text-white uppercase tracking-wider flex items-center space-x-2">
            <i class="fa-solid fa-gavel text-amber-400"></i>
            <span>Responsabilidad Legal y Custodia de Bienes Públicos</span>
        </h4>
        <p class="leading-relaxed">
            De acuerdo con el **Artículo 137 del Código Penal Colombiano** y la **Ley 1952 de 2019 (Código General Disciplinario)**, los bienes tecnológicos asignados son propiedad del ente territorial y se entregan a título de préstamo. El docente receptor asume la custodia legal del hardware. Ante pérdidas o daños injustificados por negligencia, la Contraloría General de la Nación puede exigir responsabilidad fiscal y penal.
        </p>
    </div>

    <!-- Formulario (Glassmorphism) -->
    <div class="glass-panel rounded-2xl p-8 border border-white/5 bg-black/10">
        <form action="<?= BASE_URL ?>/prestamos/crear" method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Selección de Computador -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Seleccione Computador Disponible *</label>
                    <select name="id_computador" required class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="">Seleccione equipo operativo...</option>
                        <?php if (empty($computadores)): ?>
                            <option value="" disabled>No hay computadores disponibles en este momento.</option>
                        <?php else: ?>
                            <?php foreach ($computadores as $c): ?>
                                <option value="<?= $c['id_computador'] ?>">
                                    [SED: <?= htmlspecialchars($c['placa_sed'] ?: 'N/A') ?>] <?= htmlspecialchars($c['nombre_marca'] . ' ' . $c['modelo']) ?> (S/N: <?= htmlspecialchars($c['numero_serial']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Selección de Docente (Si es Almacenista o Rector) -->
                <?php if ($role !== 'Docente'): ?>
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Docente Solicitante *</label>
                        <select name="id_docente" required class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                            <option value="">Seleccione docente...</option>
                            <?php foreach ($docentes as $d): ?>
                                <?php 
                                $cumpleTIC = ($d['capacitacion_tic_aprobada'] == 1 && $d['horas_asistencia_tic'] >= 40);
                                ?>
                                <option value="<?= $d['id_usuario'] ?>">
                                    <?= htmlspecialchars($d['nombres'] . ' ' . $d['apellidos']) ?> (Horas TIC: <?= $d['horas_asistencia_tic'] ?> - <?= $cumpleTIC ? 'APTO' : 'NO APTO' ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="text-[10px] text-slate-500 mt-1 block">Solo docentes aprobados por el Programa Nacional TIC pueden custodiar equipos.</span>
                    </div>
                <?php endif; ?>

                <!-- Fecha Devolución Estimada -->
                <div class="<?= ($role === 'Docente') ? 'md:col-span-2' : '' ?>">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fecha Estimada de Retorno *</label>
                    <input type="datetime-local" name="fecha_devolucion_estimada" required value="<?= date('Y-m-d\HT:i', strtotime('+2 hours')) ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs">
                </div>
            </div>

            <!-- Módulo Educativo: Diseño de Experiencia Pedagógica -->
            <div>
                <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-3 flex items-center space-x-1.5">
                    <i class="fa-solid fa-lightbulb text-violet-400"></i>
                    <span>Planificación y Diseño Pedagógico Integrado (Requisito TIC) *</span>
                </h4>
                <label class="block text-xs text-slate-400 mb-2">Describe brevemente la actividad de clase o intención pedagógica estructurada en la que se usarán las terminales móviles:</label>
                <textarea name="plan_pedagogico" required minlength="15" rows="4" 
                    class="block w-full px-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" 
                    placeholder="Ej. Uso de herramientas de programación en bloques para el desarrollo del pensamiento computacional en estudiantes de 6to grado..."></textarea>
            </div>

            <!-- Confirmación y Firma Digital del Proceso -->
            <div class="border-t border-white/5 pt-6 space-y-4">
                <div class="flex items-start space-x-3">
                    <input type="checkbox" required id="firma_digital_check" class="rounded bg-black border-white/10 text-violet-600 focus:ring-violet-500 mt-1">
                    <label for="firma_digital_check" class="text-xs text-slate-300 leading-relaxed cursor-pointer select-none">
                        Acepto los términos de responsabilidad civil y disciplinaria descritos anteriormente. Al dar clic en "Confirmar Préstamo", esta transacción queda sellada digitalmente a mi nombre utilizando mi documento de identidad bajo sesión institucional autenticada.
                    </label>
                </div>

                <div class="flex items-center justify-end space-x-3">
                    <a href="<?= BASE_URL ?>/prestamos" class="py-2.5 px-5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-all">
                        Cancelar
                    </a>
                    <button type="submit" class="py-2.5 px-6 bg-gradient-to-r from-violet-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs shadow-lg transition-all flex items-center space-x-2">
                        <i class="fa-solid fa-file-signature"></i>
                        <span>Confirmar Préstamo</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
