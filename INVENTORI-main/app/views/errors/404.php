<?php
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-[70vh] flex flex-col items-center justify-center text-center space-y-6 px-4">
    <!-- Icono de Advertencia de Glosa / Error -->
    <div class="w-20 h-20 bg-rose-500/10 border border-rose-500/25 rounded-2xl flex items-center justify-center text-rose-400 text-3xl shadow-lg shadow-rose-500/10 pulse-glow">
        <i class="fa-solid fa-triangle-exclamation"></i>
    </div>

    <!-- Título de Error -->
    <div class="space-y-2">
        <h2 class="text-4xl font-extrabold text-white tracking-tight">404 - Página No Encontrada</h2>
        <p class="text-sm text-slate-400 max-w-md mx-auto leading-relaxed">
            La transacción solicitada no existe o no cuentas con los privilegios de acceso correspondientes en tu sesión activa.
        </p>
    </div>

    <!-- Acciones -->
    <div class="pt-4">
        <a href="<?= BASE_URL ?>/dashboard" class="py-2.5 px-6 bg-gradient-to-r from-emerald-600 to-cyan-500 text-white font-semibold rounded-xl text-xs hover:from-emerald-500 hover:to-cyan-400 shadow-lg shadow-emerald-600/20 active:scale-95 transition-all flex items-center space-x-2 btn-interactive">
            <i class="fa-solid fa-house-chimney"></i>
            <span>Volver al Inicio</span>
        </a>
    </div>
</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
