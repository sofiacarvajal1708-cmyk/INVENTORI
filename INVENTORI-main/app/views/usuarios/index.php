<?php
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="space-y-6">

    <!-- Header y Acciones Principales -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-white tracking-tight flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-emerald-600/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-lg">
                    <i class="fa-solid fa-users-gear"></i>
                </span>
                <span>Gestión de Usuarios y Accesos</span>
            </h2>
            <p class="text-xs text-slate-400 mt-1">Control centralizado de cuentas institucionales, asignación de roles y seguridad de contraseñas.</p>
        </div>

        <a href="<?= BASE_URL ?>/usuarios/create" class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-blue-600 hover:from-emerald-500 hover:to-blue-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-600/25 transition-all btn-interactive">
            <i class="fa-solid fa-user-plus"></i>
            <span>Nuevo Usuario</span>
        </a>
    </div>

    <!-- Filtros de Búsqueda -->
    <div class="glass-panel p-4 rounded-2xl border border-white/5">
        <form method="GET" action="<?= BASE_URL ?>/usuarios" class="grid grid-cols-1 sm:grid-cols-4 gap-3 items-end">
            <!-- Buscar -->
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Buscar Usuario</label>
                <div class="relative">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-500 text-xs"></i>
                    <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" placeholder="Nombre, apellido, cédula o email..." class="w-full pl-9 pr-3 py-2 text-xs">
                </div>
            </div>

            <!-- Rol -->
            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Rol</label>
                <select name="rol" class="w-full py-2 text-xs">
                    <option value="">Todos los roles</option>
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id_rol'] ?>" <?= ($filters['rol'] ?? '') == $r['id_rol'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($r['nombre_rol']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Estado y Botón -->
            <div class="flex gap-2">
                <div class="flex-1">
                    <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Estado</label>
                    <select name="estado" class="w-full py-2 text-xs">
                        <option value="">Todos</option>
                        <option value="Activo" <?= ($filters['estado'] ?? '') === 'Activo' ? 'selected' : '' ?>>Activo</option>
                        <option value="Inactivo" <?= ($filters['estado'] ?? '') === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
                <button type="submit" class="self-end px-4 py-2 bg-white/10 hover:bg-white/15 text-white text-xs font-semibold rounded-lg transition-all" title="Filtrar">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="glass-panel rounded-2xl border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider bg-black/10">
                        <th class="py-3.5 px-4 text-center">ID</th>
                        <th class="py-3.5 px-4">Documento</th>
                        <th class="py-3.5 px-4">Usuario / Nombre</th>
                        <th class="py-3.5 px-4">Email</th>
                        <th class="py-3.5 px-4">Rol Asignado</th>
                        <th class="py-3.5 px-4 text-center">Estado</th>
                        <th class="py-3.5 px-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-medium">No se encontraron usuarios registrados con los criterios seleccionados.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                            <tr class="border-b border-white/5 hover:bg-white/[0.01] transition-colors text-slate-300">
                                <td class="py-3.5 px-4 text-center text-slate-500 font-mono">#<?= $u['id_usuario'] ?></td>
                                <td class="py-3.5 px-4 font-mono text-slate-300"><?= htmlspecialchars($u['documento_identidad']) ?></td>
                                <td class="py-3.5 px-4">
                                    <span class="block font-semibold text-white"><?= htmlspecialchars($u['nombres'] . ' ' . $u['apellidos']) ?></span>
                                    <?php if ($u['id_rol'] == 3): ?>
                                        <span class="block text-[10px] <?= $u['capacitacion_tic_aprobada'] ? 'text-emerald-400' : 'text-amber-400' ?>">
                                            <i class="fa-solid fa-certificate"></i> <?= $u['capacitacion_tic_aprobada'] ? 'Certificado TIC (' . $u['horas_asistencia_tic'] . ' hrs)' : 'Sin cert. TIC' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-slate-300"><?= htmlspecialchars($u['email']) ?></td>
                                <td class="py-3.5 px-4">
                                    <?php
                                    $roleBadge = 'bg-slate-500/10 border-slate-500/20 text-slate-300';
                                    if ($u['role_name'] === 'Administrador') $roleBadge = 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400';
                                    if ($u['role_name'] === 'Rector') $roleBadge = 'bg-blue-500/10 border-blue-500/30 text-blue-400';
                                    if ($u['role_name'] === 'Almacenista') $roleBadge = 'bg-cyan-500/10 border-cyan-500/30 text-cyan-400';
                                    if ($u['role_name'] === 'Docente') $roleBadge = 'bg-blue-500/10 border-blue-500/30 text-blue-400';
                                    if ($u['role_name'] === 'Contralor') $roleBadge = 'bg-amber-500/10 border-amber-500/30 text-amber-400';
                                    ?>
                                    <span class="px-2.5 py-1 rounded-full border text-[11px] font-bold uppercase tracking-wider <?= $roleBadge ?>">
                                        <?= htmlspecialchars($u['role_name']) ?>
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <?php if ($u['estado_usuario'] === 'Activo'): ?>
                                        <span class="px-2 py-0.5 rounded border text-[10px] font-semibold bg-emerald-500/10 border-emerald-500/20 text-emerald-400">
                                            Activo
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 rounded border text-[10px] font-semibold bg-rose-500/10 border-rose-500/20 text-rose-400">
                                            Inactivo
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-1.5">
                                        <!-- Editar datos -->
                                        <a href="<?= BASE_URL ?>/usuarios/edit/<?= $u['id_usuario'] ?>" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors" title="Editar usuario">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <!-- Cambiar Contraseña -->
                                        <a href="<?= BASE_URL ?>/usuarios/password/<?= $u['id_usuario'] ?>" class="p-1.5 rounded-lg text-amber-400 hover:text-amber-300 hover:bg-amber-500/10 transition-colors" title="Cambiar contraseña">
                                            <i class="fa-solid fa-key"></i>
                                        </a>

                                        <!-- Activar / Desactivar -->
                                        <?php if ($u['id_usuario'] != Session::get('user_id')): ?>
                                            <a href="<?= BASE_URL ?>/usuarios/toggleStatus/<?= $u['id_usuario'] ?>" class="p-1.5 rounded-lg text-slate-400 hover:text-cyan-400 hover:bg-cyan-500/10 transition-colors" title="Alternar Activo/Inactivo" onclick="return confirm('¿Desea cambiar el estado de este usuario?');">
                                                <i class="fa-solid fa-power-off"></i>
                                            </a>

                                            <!-- Eliminar -->
                                            <a href="<?= BASE_URL ?>/usuarios/delete/<?= $u['id_usuario'] ?>" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Eliminar usuario" onclick="return confirm('¿Está seguro de eliminar a este usuario permanentemente?');">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
