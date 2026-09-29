<?php
require_once APP_ROOT . '/views/layout/header.php';
$isEdit = $data['isEdit'] ?? false;
$user = $data['user'] ?? [];
?>

<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header del Formulario -->
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="<?= BASE_URL ?>/usuarios" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-colors border border-white/5">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-white"><?= $isEdit ? 'Editar Usuario' : 'Registrar Nuevo Usuario' ?></h2>
                <p class="text-xs text-slate-400">Complete los datos institucionales para el control de accesos y roles.</p>
            </div>
        </div>
    </div>

    <!-- Mensaje de Error si existe -->
    <?php if (!empty($data['error'])): ?>
        <div class="p-4 rounded-xl border border-rose-500/20 bg-rose-950/30 text-rose-300 flex items-center space-x-2 text-xs">
            <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm"></i>
            <span><?= htmlspecialchars($data['error']) ?></span>
        </div>
    <?php endif; ?>

    <!-- Formulario Principal -->
    <div class="glass-panel p-6 rounded-2xl border border-white/5">
        <form action="<?= BASE_URL ?>/usuarios/<?= $isEdit ? 'edit/' . $user['id_usuario'] : 'create' ?>" method="POST" class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Tipo y Documento de Identidad -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Documento de Identidad *</label>
                    <div class="grid grid-cols-3 gap-2">
                        <div>
                            <select name="tipo_documento" required class="block w-full px-2.5 py-2.5 text-xs font-bold text-emerald-400 bg-slate-900 border border-slate-700/80 rounded-xl focus:ring-2 focus:ring-emerald-500">
                                <option value="C.C." <?= ($user['tipo_documento'] ?? 'C.C.') === 'C.C.' ? 'selected' : '' ?>>C.C.</option>
                                <option value="T.I." <?= ($user['tipo_documento'] ?? '') === 'T.I.' ? 'selected' : '' ?>>T.I. (Tarjeta de Identidad)</option>
                                <option value="C.E." <?= ($user['tipo_documento'] ?? '') === 'C.E.' ? 'selected' : '' ?>>C.E. (Cédula Extranjería)</option>
                                <option value="Pasaporte" <?= ($user['tipo_documento'] ?? '') === 'Pasaporte' ? 'selected' : '' ?>>Pasaporte</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <input type="text" name="documento_identidad" required value="<?= htmlspecialchars($user['documento_identidad'] ?? '') ?>" 
                                class="block w-full px-4 py-2.5 text-xs" placeholder="Número de documento...">
                        </div>
                    </div>
                </div>

                <!-- Rol -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Rol en el Sistema *</label>
                    <select name="id_rol" required class="block w-full px-4 py-2.5 text-xs" id="role-select">
                        <option value="">Seleccione rol institucional...</option>
                        <?php foreach ($data['roles'] as $r): ?>
                            <option value="<?= $r['id_rol'] ?>" <?= ($user['id_rol'] ?? '') == $r['id_rol'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['nombre_rol']) ?> — <?= htmlspecialchars($r['descripcion']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Nombres -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nombres *</label>
                    <input type="text" name="nombres" required value="<?= htmlspecialchars($user['nombres'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 text-xs" placeholder="Ej. Juan Carlos">
                </div>

                <!-- Apellidos -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Apellidos *</label>
                    <input type="text" name="apellidos" required value="<?= htmlspecialchars($user['apellidos'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 text-xs" placeholder="Ej. Pérez Gómez">
                </div>

                <!-- Correo Electrónico Institucional -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Correo Electrónico Institucional *</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($user['email'] ?? '') ?>" 
                        class="block w-full px-4 py-2.5 text-xs" placeholder="Ej. usuario@santa.edu.co">
                </div>

                <?php if (!$isEdit): ?>
                    <!-- Contraseña Inicial -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Contraseña Inicial *</label>
                        <input type="password" name="password" required minlength="6"
                            class="block w-full px-4 py-2.5 text-xs" placeholder="Mínimo 6 caracteres">
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Confirmar Contraseña *</label>
                        <input type="password" name="password_confirm" required minlength="6"
                            class="block w-full px-4 py-2.5 text-xs" placeholder="Repita la contraseña">
                    </div>
                <?php endif; ?>

                <!-- Estado -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Estado de la Cuenta</label>
                    <select name="estado_usuario" class="block w-full px-4 py-2.5 text-xs">
                        <option value="Activo" <?= ($user['estado_usuario'] ?? 'Activo') === 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Inactivo" <?= ($user['estado_usuario'] ?? '') === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <!-- Capacitación TIC (Aplica para docentes) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Horas de Formación TIC</label>
                    <input type="number" name="horas_asistencia_tic" min="0" value="<?= htmlspecialchars($user['horas_asistencia_tic'] ?? '0') ?>" 
                        class="block w-full px-4 py-2.5 text-xs" placeholder="0">
                </div>

                <div class="md:col-span-2 flex items-center space-x-3 p-3 rounded-xl bg-white/5 border border-white/5">
                    <input type="checkbox" name="capacitacion_tic_aprobada" id="tic_check" value="1" <?= !empty($user['capacitacion_tic_aprobada']) ? 'checked' : '' ?> class="rounded text-emerald-600 focus:ring-emerald-500">
                    <label for="tic_check" class="text-xs text-slate-300 select-none cursor-pointer">
                        <strong class="text-white">Certificación TIC Aprobada</strong> — Habilita permisos pedagógicos avanzados para solicitud de terminales en préstamo.
                    </label>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="pt-4 border-t border-white/5 flex items-center justify-end space-x-3">
                <a href="<?= BASE_URL ?>/usuarios" class="px-5 py-2.5 rounded-xl border border-white/10 text-slate-400 hover:text-white hover:bg-white/5 font-semibold text-xs transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-500 hover:to-blue-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-600/25 transition-all btn-interactive">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i>
                    <?= $isEdit ? 'Actualizar Usuario' : 'Registrar Usuario' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
