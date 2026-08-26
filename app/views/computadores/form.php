<?php
require_once APP_ROOT . '/views/layout/header.php';
$isEdit = $data['isEdit'] ?? false;
$comp = $data['computador'] ?? null;
?>

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Cabecera de Formulario -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/computadores" class="p-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-xl transition-all">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white"><?= $isEdit ? 'Editar Computador' : 'Registrar Computador en Inventario' ?></h2>
            <p class="text-xs text-slate-400 mt-1">Formulario de registro oficial de activos fijos devolutivos.</p>
        </div>
    </div>

    <!-- Tarjeta de Formulario (Glassmorphism) -->
    <div class="glass-panel rounded-2xl p-8 border border-white/5 bg-black/10">
        <form action="<?= BASE_URL ?>/computadores/<?= $isEdit ? 'editar/' . $comp['id_computador'] : 'crear' ?>" method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Número Serial -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Número de Serial *</label>
                    <input type="text" name="numero_serial" required value="<?= htmlspecialchars($comp['numero_serial'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs">
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Marca *</label>
                    <select name="id_marca" required class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="">Seleccione marca...</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?= $m['id_marca'] ?>" <?= (isset($comp['id_marca']) && $comp['id_marca'] == $m['id_marca']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nombre_marca']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Placa SED -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Placa SED (Secretaría de Educación)</label>
                    <input type="text" name="placa_sed" value="<?= htmlspecialchars($comp['placa_sed'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" placeholder="Ej. SED-98745">
                </div>


                <!-- Modelo -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Modelo *</label>
                    <input type="text" name="modelo" required value="<?= htmlspecialchars($comp['modelo'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" placeholder="Ej. ThinkPad L14">
                </div>

                <!-- Procesador -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Procesador</label>
                    <input type="text" name="procesador" value="<?= htmlspecialchars($comp['procesador'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" placeholder="Ej. Intel Core i5-1135G7">
                </div>

                <!-- Licenciamiento -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Licenciamiento de OS</label>
                    <input type="text" name="licenciamiento" value="<?= htmlspecialchars($comp['licenciamiento'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs" placeholder="Ej. Windows 11 Pro Education">
                </div>

                <!-- Sala Destino -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Sala Asignada *</label>
                    <select name="id_sala_actual" required class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="">Seleccione sala...</option>
                        <?php foreach ($salas as $s): ?>
                            <option value="<?= $s['id_sala'] ?>" <?= (isset($comp['id_sala_actual']) && $comp['id_sala_actual'] == $s['id_sala']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['nombre_sala']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>


                <!-- Fecha Adquisición -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Fecha de Adquisición *</label>
                    <input type="date" name="fecha_adquisicion" required value="<?= htmlspecialchars($comp['fecha_adquisicion'] ?? date('Y-m-d')) ?>" 
                        class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-100 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 text-xs">
                </div>

                <!-- Estado Activo -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Estado del Activo *</label>
                    <select name="estado_activo" required class="block w-full px-4 py-2.5 bg-black/35 border border-white/10 rounded-xl text-slate-300 text-xs focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500">
                        <option value="Operativo" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'Operativo') ? 'selected' : '' ?>>Operativo</option>
                        <option value="En Mantenimiento" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'En Mantenimiento') ? 'selected' : '' ?>>En Mantenimiento</option>
                        <option value="Obsoleto" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'Obsoleto') ? 'selected' : '' ?>>Obsoleto</option>
                        <option value="Dado de Baja" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'Dado de Baja') ? 'selected' : '' ?>>Dado de Baja</option>
                    </select>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="flex items-center justify-end space-x-3 border-t border-white/5 pt-6">
                <a href="<?= BASE_URL ?>/computadores" class="py-2.5 px-5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-all">
                    Cancelar
                </a>
                <button type="submit" class="py-2.5 px-6 bg-gradient-to-r from-violet-600 to-cyan-500 hover:from-violet-500 hover:to-cyan-400 text-white font-semibold rounded-xl text-xs shadow-lg transition-all">
                    <?= $isEdit ? 'Guardar Cambios' : 'Registrar Equipo' ?>
                </button>
            </div>

        </form>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
