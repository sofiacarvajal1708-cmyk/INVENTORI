<?php
// Cargar header sin navegación lateral (login es pantalla completa)
require_once APP_ROOT . '/views/layout/header.php';
?>

<div id="login-page" class="animated-bg text-slate-100 relative transition-all duration-500" style="height:100vh; overflow:hidden; display:flex; flex-direction:column;">

    <!-- Luces ambientales -->
    <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-indigo-500/10 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Barra superior: logo pequeño + botón de tema -->
    <div class="w-full flex items-center justify-between px-6 py-3 relative z-20 shrink-0">
        <div class="flex items-center space-x-2.5">
            <img src="<?= BASE_URL ?>/public/img/logo.png" alt="INVENTORI BASAMA" class="h-8 w-8 rounded-xl object-cover border border-blue-500/30 shadow-md">
            <div class="leading-tight">
                <span class="text-sm font-bold text-white tracking-wide">INVENTORI BASAMA</span>
                <span class="ml-2 text-[10px] px-1.5 py-0.5 rounded bg-blue-500/20 text-blue-400 border border-blue-500/30 align-middle">2.0</span>
            </div>
        </div>
        <button id="theme-toggle" type="button"
            class="p-2 rounded-xl bg-black/40 border border-white/10 text-slate-400 hover:text-white hover:border-white/20 transition-all shadow text-sm"
            title="Cambiar a modo claro">
            <i id="theme-icon" class="fa-regular fa-sun"></i>
        </button>
    </div>

    <!-- Cuerpo principal: 2 columnas que llenan el espacio restante -->
    <div class="flex-1 w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-center px-6 lg:px-10 pb-3 relative z-10 min-h-0">

        <!-- ================================================ -->
        <!-- COLUMNA IZQUIERDA: Info + Imagen                  -->
        <!-- ================================================ -->
        <div class="lg:col-span-7 flex flex-col justify-center space-y-3 lg:pr-4 h-full min-h-0">

            <!-- Subtítulo institucional -->
            <p class="text-xs text-blue-300 font-medium">IEBSM Barrio Santa Margarita • Plataforma de Gestión Tecnológica</p>

            <!-- Título Principal -->
            <h1 class="text-xl sm:text-2xl xl:text-3xl font-extrabold text-white leading-tight tracking-tight">
                La plataforma definitiva de control, trazabilidad e inventario tecnológico en tiempo real.
            </h1>

            <!-- Descripción breve -->
            <p class="text-slate-300 text-xs leading-relaxed">
                Bienvenido a <strong class="text-white font-semibold">INVENTORI BASAMA</strong>, un entorno diseñado para optimizar el inventario de equipos, salas, préstamos y traslados con total seguridad y transparencia institucional.
            </p>

            <!-- Imagen Ilustrativa -->
            <div class="w-full rounded-2xl overflow-hidden glass-panel border border-white/10 shadow-2xl relative group" style="flex:1; min-height:120px; max-height:220px;">
                <img src="<?= BASE_URL ?>/public/img/login_hero.jpg"
                     alt="Inventario Tecnológico Santa Margarita"
                     class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
            </div>

            <!-- Instrucciones de Acceso -->
            <div class="space-y-1.5">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Instrucciones de Acceso</h3>
                <div class="space-y-1.5 text-[11px] text-slate-300">
                    <div class="flex items-start space-x-2">
                        <div class="w-4 h-4 rounded-full bg-blue-600/30 border border-blue-500/40 text-blue-300 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">1</div>
                        <p class="leading-snug"><strong class="text-white">Credenciales:</strong> Ingrese su correo institucional y contraseña en el panel derecho.</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-4 h-4 rounded-full bg-indigo-600/30 border border-indigo-500/40 text-indigo-300 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">2</div>
                        <p class="leading-snug"><strong class="text-white">Direccionamiento inteligente:</strong> El sistema detectará su rol y lo redirigirá a su panel automáticamente.</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-4 h-4 rounded-full bg-emerald-600/30 border border-emerald-500/40 text-emerald-300 font-bold text-[10px] flex items-center justify-center shrink-0 mt-0.5">3</div>
                        <p class="leading-snug"><strong class="text-white">Auditoría y actas:</strong> Registro con firma digital y verificación en tiempo real.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================ -->
        <!-- COLUMNA DERECHA: Formulario de Login              -->
        <!-- ================================================ -->
        <div class="lg:col-span-5 w-full flex flex-col justify-center h-full min-h-0">
            <div class="glass-panel rounded-3xl p-6 sm:p-8 border border-white/10 shadow-2xl space-y-4">

                <!-- Encabezado de la tarjeta -->
                <div class="text-center space-y-1.5">
                    <img src="<?= BASE_URL ?>/public/img/logo.png" alt="INVENTORI BASAMA"
                         class="mx-auto h-14 w-14 rounded-2xl object-cover shadow-xl shadow-blue-500/40 border border-blue-500/30">
                    <h2 class="text-xl font-bold text-white tracking-tight">Acceso al Portal</h2>
                    <p class="text-xs text-slate-400">Ingresa con tus credenciales de usuario</p>
                </div>

                <!-- Alertas de Error -->
                <?php if (isset($error) && !empty($error)): ?>
                    <div class="p-3 rounded-xl border border-rose-500/30 bg-rose-950/50 text-rose-300 text-xs flex items-start space-x-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 mt-0.5"></i>
                        <span><?= $error ?></span>
                    </div>
                <?php endif; ?>

                <!-- Formulario -->
                <form class="space-y-3.5" action="<?= BASE_URL ?>/auth/login" method="POST">

                    <!-- Campo: Usuario / Correo -->
                    <div>
                        <label for="email" class="block text-[11px] font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Usuario / Correo Institucional
                        </label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm z-10">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <input id="email" name="email" type="email" required
                                class="block w-full pl-10 pr-4 py-2.5 bg-black/40 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                                placeholder="usuario@santa.edu.co">
                        </div>
                    </div>

                    <!-- Campo: Contraseña -->
                    <div>
                        <label for="password" class="block text-[11px] font-semibold text-slate-300 uppercase tracking-wider mb-1.5">
                            Contraseña
                        </label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-sm z-10">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-10 pr-11 py-2.5 bg-black/40 border border-white/10 rounded-xl text-slate-100 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all text-sm"
                                placeholder="••••••••">
                            <button type="button" id="toggle-password"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-white transition-colors text-sm focus:outline-none z-10"
                                title="Mostrar u ocultar contraseña">
                                <i class="fa-regular fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Botón de Ingreso -->
                    <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 hover:from-blue-500 hover:to-indigo-500 text-white font-semibold rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-lg shadow-blue-600/30 active:scale-[0.98] transition-all text-sm flex items-center justify-center space-x-2">
                        <span>Ingresar al Portal</span>
                        <span>→</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full text-center text-[11px] text-slate-500 py-2 relative z-10 shrink-0">
        Desarrollado para <strong class="text-slate-400 font-medium">IEBSM Barrio Santa Margarita</strong> © 2023 - 2026
    </footer>
</div>

<style>
/* Quitar padding de body/html para que no haya scroll */
html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
}

/* Modo Claro */
body.light-mode #login-page {
    background: linear-gradient(-45deg, #e8edf5, #dce6f5, #e2ebf0, #edf2fa) !important;
    animation: none !important;
    color: #1e293b;
}
body.light-mode #login-page .glass-panel {
    background: rgba(255, 255, 255, 0.80);
    border-color: rgba(0, 0, 0, 0.08);
    box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
}
body.light-mode #login-page .text-slate-100,
body.light-mode #login-page .text-white { color: #1e293b; }
body.light-mode #login-page .text-slate-400 { color: #64748b; }
body.light-mode #login-page .text-slate-300 { color: #475569; }
body.light-mode #login-page .text-slate-500 { color: #94a3b8; }
body.light-mode #login-page input {
    background: rgba(255,255,255,0.85);
    border-color: rgba(0,0,0,0.12);
    color: #1e293b;
}
body.light-mode #login-page input::placeholder { color: #94a3b8; }
body.light-mode #login-page .bg-black\/40 { background: rgba(255,255,255,0.7); }
body.light-mode #login-page .border-white\/10 { border-color: rgba(0,0,0,0.1); }
body.light-mode #login-page #theme-toggle {
    background: rgba(0,0,0,0.07);
    border-color: rgba(0,0,0,0.1);
    color: #64748b;
}
body.light-mode #login-page footer { color: #94a3b8; }
body.light-mode #login-page footer strong { color: #475569; }
body.light-mode #login-page .bg-black\/60 { background: rgba(0,0,0,0.45); }
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {

    // ---- Toggle Mostrar/Ocultar Contraseña ----
    const toggleBtn = document.getElementById('toggle-password');
    const passInput = document.getElementById('password');
    const eyeIcon  = document.getElementById('eye-icon');

    if (toggleBtn && passInput && eyeIcon) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = passInput.type === 'password';
            passInput.type   = isPassword ? 'text' : 'password';
            eyeIcon.className = isPassword ? 'fa-regular fa-eye-slash' : 'fa-regular fa-eye';
        });
    }

    // ---- Toggle Modo Oscuro / Claro ----
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon   = document.getElementById('theme-icon');

    const savedTheme = localStorage.getItem('login_theme') || 'dark';
    applyTheme(savedTheme);

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isLight = document.body.classList.contains('light-mode');
            const next = isLight ? 'dark' : 'light';
            applyTheme(next);
            localStorage.setItem('login_theme', next);
        });
    }

    function applyTheme(mode) {
        if (mode === 'light') {
            document.body.classList.add('light-mode');
            if (themeIcon)   themeIcon.className = 'fa-regular fa-moon';
            if (themeToggle) themeToggle.title   = 'Cambiar a modo oscuro';
        } else {
            document.body.classList.remove('light-mode');
            if (themeIcon)   themeIcon.className = 'fa-regular fa-sun';
            if (themeToggle) themeToggle.title   = 'Cambiar a modo claro';
        }
    }
});
</script>

<?php
// Cargar footer sin navegación
require_once APP_ROOT . '/views/layout/footer.php';
?>
