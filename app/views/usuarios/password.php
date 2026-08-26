<?php
require_once APP_ROOT . '/views/layout/header.php';
$user = $data['user'] ?? [];
?>

<div class="max-w-xl mx-auto space-y-6">

    <!-- Header del Formulario -->
    <div class="flex items-center space-x-3">
        <a href="<?= BASE_URL ?>/usuarios" class="p-2 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white transition-colors border border-white/5">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-white">Cambiar Contraseña</h2>
            <p class="text-xs text-slate-400">Actualizar credencial de acceso para <?= htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']) ?>.</p>
        </div>
    </div>

    <!-- Mensaje de Error si existe -->
    <?php if (!empty($data['error'])): ?>
        <div class="p-4 rounded-xl border border-rose-500/20 bg-rose-950/30 text-rose-300 flex items-center space-x-2 text-xs">
            <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm"></i>
            <span><?= htmlspecialchars($data['error']) ?></span>
        </div>
    <?php endif; ?>

    <!-- Tarjeta informativa del usuario -->
    <div class="glass-panel p-4 rounded-2xl border border-white/5 flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full bg-violet-600/20 border border-violet-500/30 flex items-center justify-center text-violet-400 font-bold text-sm">
                <?= strtoupper(substr($user['nombres'], 0, 1)) ?>
            </div>
            <div>
                <span class="block text-sm font-semibold text-white"><?= htmlspecialchars($user['nombres'] . ' ' . $user['apellidos']) ?></span>
                <span class="block text-xs text-slate-400"><?= htmlspecialchars($user['email']) ?></span>
            </div>
        </div>
        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-violet-500/10 border border-violet-500/20 text-violet-400">
            <?= htmlspecialchars($user['role_name']) ?>
        </span>
    </div>

    <!-- Formulario de Cambio de Contraseña -->
    <div class="glass-panel p-6 rounded-2xl border border-white/5">
        <form action="<?= BASE_URL ?>/usuarios/password/<?= $user['id_usuario'] ?>" method="POST" class="space-y-5">

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Nueva Contraseña *</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-3.5 top-3 text-slate-500 text-xs"></i>
                    <input type="password" name="new_password" required minlength="6"
                        class="w-full pl-9 pr-3 py-2.5 text-xs" placeholder="Mínimo 6 caracteres">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Confirmar Nueva Contraseña *</label>
                <div class="relative">
                    <i class="fa-solid fa-shield-halved absolute left-3.5 top-3 text-slate-500 text-xs"></i>
                    <input type="password" name="confirm_password" required minlength="6"
                        class="w-full pl-9 pr-3 py-2.5 text-xs" placeholder="Repita la nueva contraseña">
                </div>
            </div>

            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-300 text-xs flex items-start space-x-2">
                <i class="fa-solid fa-circle-info text-sm mt-0.5 shrink-0 text-amber-400"></i>
                <span>Al guardar, la contraseña anterior quedará invalidada de inmediato y el usuario deberá iniciar sesión con la nueva credencial.</span>
            </div>

            <div class="pt-4 border-t border-white/5 flex items-center justify-end space-x-3">
                <a href="<?= BASE_URL ?>/usuarios" class="px-5 py-2.5 rounded-xl border border-white/10 text-slate-400 hover:text-white hover:bg-white/5 font-semibold text-xs transition-all">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-violet-600/25 transition-all">
                    <i class="fa-solid fa-key mr-1.5"></i>
                    Guardar Nueva Contraseña
                </button>
            </div>
        </form>
    </div>
</div>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
