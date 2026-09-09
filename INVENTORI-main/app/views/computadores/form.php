<?php
require_once APP_ROOT . '/views/layout/header.php';
$isEdit = $data['isEdit'] ?? false;
$comp = $data['computador'] ?? null;
?>

<div class="max-w-4xl mx-auto space-y-6">

    <!-- Cabecera de Formulario -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/computadores" class="p-2.5 bg-slate-900 hover:bg-emerald-600/30 text-emerald-400 rounded-xl transition-all border border-white/10">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-desktop text-emerald-400"></i>
                <span><?= $isEdit ? 'Editar Equipo' : 'Nuevo Equipo' ?></span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">Ingrese los detalles requeridos para registrar el equipo en el inventario institucional.</p>
        </div>
    </div>

    <!-- Tarjeta de Formulario (Glassmorphism + Dark Blue & Emerald Theme) -->
    <div class="glass-panel rounded-2xl p-8 border border-white/10 bg-slate-900/80 shadow-2xl space-y-6">
        <form action="<?= BASE_URL ?>/computadores/<?= $isEdit ? 'editar/' . $comp['id_computador'] : 'crear' ?>" method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- 1. A qué pertenece / Selecciona Sede -->
                <div>
                    <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2">
                        A qué pertenece (Seleccionar Sede) *
                    </label>
                    <select name="sede" required class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Seleccione Sede...</option>
                        <option value="Santa Margarita" <?= (isset($comp['sede']) && $comp['sede'] === 'Santa Margarita') ? 'selected' : '' ?>>Sede Santa Margarita</option>
                        <option value="Pedro Nel Ospina" <?= (isset($comp['sede']) && $comp['sede'] === 'Pedro Nel Ospina') ? 'selected' : '' ?>>Sede Pedro Nel Ospina</option>
                        <option value="Bachillerato Santa Margarita" <?= (isset($comp['sede']) && $comp['sede'] === 'Bachillerato Santa Margarita') ? 'selected' : '' ?>>Bachillerato Santa Margarita</option>
                        <option value="General / Otras" <?= (isset($comp['sede']) && $comp['sede'] === 'General / Otras') ? 'selected' : '' ?>>General / Otras</option>
                    </select>
                </div>

                <!-- 2. Ubicación Actual (Sala) -->
                <div>
                    <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2">
                        Ubicación Actual (Sala) *
                    </label>
                    <select name="id_sala_actual" required class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Seleccione sala asignada...</option>
                        <?php foreach ($salas as $s): ?>
                            <option value="<?= $s['id_sala'] ?>" <?= (isset($comp['id_sala_actual']) && $comp['id_sala_actual'] == $s['id_sala']) ? 'selected' : '' ?>>
                                [<?= htmlspecialchars($s['sede'] ?? 'Sede') ?>] <?= htmlspecialchars($s['nombre_sala']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 3. Descripción del Equipo / Computador (Modelo) -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Descripción del Equipo Computador (Modelo/Tipo) *
                    </label>
                    <input type="text" name="modelo" required value="<?= htmlspecialchars($comp['modelo'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" 
                        placeholder="Ej. Computador All-in-One HP ProDesk 400 G6">
                </div>

                <!-- 4. Placa SED -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Placa SED (Secretaría de Educación)
                    </label>
                    <input type="text" name="placa_sed" value="<?= htmlspecialchars($comp['placa_sed'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" 
                        placeholder="Ej. SED-98745">
                </div>

                <!-- 5. Número Serial -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">
                        Número de Serial *
                    </label>
                    <input type="text" name="numero_serial" required value="<?= htmlspecialchars($comp['numero_serial'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" 
                        placeholder="Ej. SN-2026-9988">
                </div>

                <!-- Marca -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Marca *</label>
                    <select name="id_marca" required class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="">Seleccione marca...</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?= $m['id_marca'] ?>" <?= (isset($comp['id_marca']) && $comp['id_marca'] == $m['id_marca']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['nombre_marca']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- 6. Estado del Activo -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Estado del Activo *</label>
                    <select name="estado_activo" required class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="Operativo" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'Operativo') ? 'selected' : '' ?>>Operativo</option>
                        <option value="En Mantenimiento" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'En Mantenimiento') ? 'selected' : '' ?>>En Mantenimiento</option>
                        <option value="Obsoleto" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'Obsoleto') ? 'selected' : '' ?>>Obsoleto</option>
                        <option value="Dado de Baja" <?= (isset($comp['estado_activo']) && $comp['estado_activo'] === 'Dado de Baja') ? 'selected' : '' ?>>Dado de Baja</option>
                    </select>
                </div>

                <!-- Fecha Adquisición -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Fecha de Adquisición *</label>
                    <input type="date" name="fecha_adquisicion" required value="<?= htmlspecialchars($comp['fecha_adquisicion'] ?? date('Y-m-d')) ?>" 
                        class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs">
                </div>

                <!-- Procesador -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Procesador</label>
                    <input type="text" name="procesador" value="<?= htmlspecialchars($comp['procesador'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" 
                        placeholder="Ej. Intel Core i5-11400">
                </div>

                <!-- Licenciamiento -->
                <div>
                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Licenciamiento de Sistema Operativo</label>
                    <input type="text" name="licenciamiento" value="<?= htmlspecialchars($comp['licenciamiento'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" 
                        placeholder="Ej. Windows 11 Pro Education">
                </div>
            </div>

            <!-- 7. Descripción Adicional -->
            <div>
                <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2">
                    Descripción Adicional / Observaciones Técnicas
                </label>
                <textarea name="descripcion_adicional" rows="3" 
                    class="block w-full px-4 py-2.5 bg-black/50 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-xs" 
                    placeholder="Ingrese observaciones adicionales del equipo, accesorios incluidos, condición física, o notas de mantenimiento..."><?= htmlspecialchars($comp['descripcion_adicional'] ?? '') ?></textarea>
            </div>

            <!-- Botones de Acción con Hover Animado -->
            <div class="flex items-center justify-end space-x-3 border-t border-white/10 pt-6">
                <a href="<?= BASE_URL ?>/computadores" 
                   class="py-2.5 px-5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold rounded-xl text-xs transition-all border border-white/10 active:scale-95">
                    Cancelar
                </a>
                <button type="submit" 
                        class="py-2.5 px-6 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-500 hover:from-emerald-500 hover:to-teal-400 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/30 hover:shadow-emerald-500/50 hover:scale-105 active:scale-95 transition-all btn-interactive">
                    <?= $isEdit ? 'Guardar Cambios' : 'Registrar Nuevo Equipo' ?>
                </button>
            </div>

        </form>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
