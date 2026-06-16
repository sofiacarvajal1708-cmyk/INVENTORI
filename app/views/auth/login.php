<?php
// Cargar header sin navegación lateral (login es pantalla completa)
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="min-h-screen flex items-center justify-center animated-bg px-4 py-12 sm:px-6 lg:px-8">
    <!-- Círculos decorativos de fondo con brillo -->
    <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-violet-600/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-cyan-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo e Identificación -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-tr from-violet-600 to-cyan-500 flex items-center justify-center shadow-2xl shadow-violet-500/30 mb-4 pulse-glow">
                <i class="fa-solid fa-cubes text-white text-3xl"></i>
            </div>
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                IEBSM Barrio Santa Margarita
            </h2>
            <p class="mt-2 text-sm text-slate-400 font-medium">
                Sistema Automatizado de Gestión de Activos Tecnológicos
            </p>
        </div>

        <!-- Tarjeta de Login (Glassmorphism) -->
        <div class="glass-panel rounded-2xl p-8 border border-white/5 shadow-2xl">
            <h3 class="text-lg font-semibold text-white mb-6 flex items-center justify-center space-x-2">
                <i class="fa-solid fa-shield-halved text-violet-400"></i>
                <span>Acceso Seguro</span>
            </h3>

            <!-- Alertas de Error/Expiración -->
            <?php if (isset($error) && !empty($error)): ?>
                <div class="mb-4 p-3.5 rounded-xl border border-rose-500/20 bg-rose-950/30 text-rose-300 text-sm flex items-start space-x-2.5">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 mt-0.5"></i>
                    <span><?= $error ?></span>
                </div>
            <?php endif; ?>

            <form class="space-y-5" action="<?= BASE_URL ?>/auth/login" method="POST">
                <!-- Input de Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        Correo Electrónico
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input id="email" name="email" type="email" required 
                            class="block w-full pl-10 pr-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-all text-sm" 
                            placeholder="nombre@santa.edu.co">
                    </div>
                </div>

                <!-- Input de Contraseña -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        Contraseña
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-10 pr-4 py-3 bg-black/35 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-violet-500/50 focus:border-violet-500 transition-all text-sm" 
                            placeholder="••••••••">
                    </div>
                </div>

                <!-- Botón de Ingreso -->
                <button type="submit" 
                    class="w-full py-3.5 px-4 bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-semibold rounded-xl hover:from-violet-500 hover:to-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 shadow-lg shadow-violet-600/20 active:scale-95 transition-all text-sm flex items-center justify-center space-x-2">
                    <span>Ingresar al Sistema</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>

        <!-- Ayuda rápida (WOW: Cuentas de prueba preconfiguradas) -->
        <div class="glass-panel rounded-2xl p-5 border border-white/5 text-xs text-slate-400 space-y-2">
            <h4 class="font-bold text-white uppercase tracking-wider text-[10px] text-center mb-2">
                Acceso de Prueba (Auto-Sembrado)
            </h4>
            <div class="grid grid-cols-2 gap-2 text-[10px]">
                <div class="p-2 bg-black/25 rounded-lg border border-white/5">
                    <p class="font-semibold text-violet-300">Rector</p>
                    <p class="truncate">rector@santa.edu.co</p>
                    <p class="text-slate-500">Clave: rector123</p>
                </div>
                <div class="p-2 bg-black/25 rounded-lg border border-white/5">
                    <p class="font-semibold text-cyan-300">Almacenista</p>
                    <p class="truncate">almacenista@santa.edu.co</p>
                    <p class="text-slate-500">Clave: almacenista123</p>
                </div>
                <div class="p-2 bg-black/25 rounded-lg border border-white/5">
                    <p class="font-semibold text-emerald-300">Docente TIC (Aprobado)</p>
                    <p class="truncate">docente_tic@santa.edu.co</p>
                    <p class="text-slate-500">Clave: docente123</p>
                </div>
                <div class="p-2 bg-black/25 rounded-lg border border-white/5">
                    <p class="font-semibold text-yellow-500">Docente No TIC</p>
                    <p class="truncate">docente_notic@santa.edu.co</p>
                    <p class="text-slate-500">Clave: docente123</p>
                </div>
            </div>
            <div class="p-2 bg-black/25 rounded-lg border border-white/5 text-center text-[10px]">
                <p class="font-semibold text-rose-300">Contralor Escolar (Veeduría)</p>
                <p>contralor@santa.edu.co | Clave: contralor123</p>
            </div>
        </div>
    </div>
</div>

<?php
// Cargar footer sin navegación
require_once APP_ROOT . '/views/layout/footer.php';
?>
