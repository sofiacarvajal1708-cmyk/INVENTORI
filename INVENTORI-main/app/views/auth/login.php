<?php
// Cargar header sin navegación lateral (login es pantalla completa)
require_once APP_ROOT . '/views/layout/header.php';
?>

<div id="login-page" class="bg-transparent text-slate-100 relative transition-all duration-500" style="height:100vh; overflow:hidden; display:flex; flex-direction:column;">

    <!-- Luces ambientales -->
    <div class="absolute top-1/4 left-1/4 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Barra superior: logo pequeño + botón de tema interactivo con cambio de color al pasar el mouse -->
    <div class="w-full flex items-center justify-between px-6 py-3 relative z-20 shrink-0">
        <div class="flex items-center space-x-2.5">
            <img src="<?= BASE_URL ?>/public/img/logo.png" alt="INVENTORI BASAMA" class="h-9 w-9 rounded-xl object-contain border border-emerald-500/40 bg-slate-950/80 p-0.5 shadow-md">
            <div class="leading-tight">
                <span class="text-sm font-bold text-white tracking-wide">INVENTORI BASAMA</span>
            </div>
        </div>
        <button id="theme-toggle" type="button" onclick="toggleAppTheme(event)"
            class="p-2.5 rounded-xl bg-slate-900/80 border border-emerald-500/40 text-emerald-400 hover:text-white hover:bg-gradient-to-r hover:from-emerald-500 hover:to-blue-600 hover:border-emerald-300 hover:scale-110 active:scale-95 shadow-lg shadow-emerald-500/30 transition-all duration-300 cursor-pointer"
            title="Efecto Neón Tecnológico Verde Esmeralda y Azul Oscuro">
            <i id="theme-icon" class="fa-solid fa-gear text-emerald-400"></i>
        </button>
    </div>

    <!-- Cuerpo principal: 2 columnas que llenan el espacio restante -->
    <div class="flex-1 w-full max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-center px-6 lg:px-10 pb-3 relative z-10 min-h-0">

        <!-- ================================================ -->
        <!-- COLUMNA IZQUIERDA: Info + Imagen                  -->
        <!-- ================================================ -->
        <div class="lg:col-span-7 flex flex-col justify-center space-y-3 lg:pr-4 h-full min-h-0">

            <!-- Subtítulo institucional -->
            <p class="text-xs text-emerald-400 font-extrabold tracking-wider uppercase">IEBSM Barrio Santa Margarita • Plataforma de Gestión Tecnológica</p>

            <!-- Título Principal -->
            <h1 class="text-xl sm:text-2xl xl:text-3xl font-extrabold text-white leading-tight tracking-tight drop-shadow-md">
                La plataforma definitiva de control, trazabilidad e inventario tecnológico en tiempo real.
            </h1>

            <!-- Descripción breve -->
            <p class="text-slate-100 text-sm font-medium leading-relaxed drop-shadow">
                Bienvenido a <strong class="text-emerald-400 font-bold">INVENTORI BASAMA</strong>, un entorno diseñado para optimizar el inventario de equipos, salas, préstamos y traslados con total seguridad y transparencia institucional.
            </p>

            <!-- Imagen Ilustrativa -->
            <div class="w-full rounded-2xl overflow-hidden glass-panel border border-emerald-500/30 shadow-2xl relative group" style="flex:1; min-height:120px; max-height:220px;">
                <img src="<?= BASE_URL ?>/public/img/login_hero.jpg"
                     alt="Inventario Tecnológico Santa Margarita"
                     class="w-full h-full object-cover object-center transform group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
            </div>

            <!-- Instrucciones de Acceso -->
            <div class="space-y-1.5">
                <h3 class="text-xs font-extrabold text-emerald-400 uppercase tracking-widest">Instrucciones de Acceso</h3>
                <div class="space-y-2 text-xs text-white">
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/30 border border-emerald-400/50 text-emerald-300 font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5">1</div>
                        <p class="leading-snug text-slate-100"><strong class="text-emerald-400 font-bold">Credenciales:</strong> Ingrese su correo institucional y contraseña en el panel derecho.</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/30 border border-emerald-400/50 text-emerald-300 font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5">2</div>
                        <p class="leading-snug text-slate-100"><strong class="text-emerald-400 font-bold">Direccionamiento inteligente:</strong> El sistema detectará su rol y lo redirigirá a su panel automáticamente.</p>
                    </div>
                    <div class="flex items-start space-x-2">
                        <div class="w-5 h-5 rounded-full bg-emerald-500/30 border border-emerald-400/50 text-emerald-300 font-extrabold text-xs flex items-center justify-center shrink-0 mt-0.5">3</div>
                        <p class="leading-snug text-slate-100"><strong class="text-emerald-400 font-bold">Auditoría y actas:</strong> Registro con firma digital y verificación en tiempo real.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================================================ -->
        <!-- COLUMNA DERECHA: Formulario de Login              -->
        <!-- ================================================ -->
        <div class="lg:col-span-5 w-full flex flex-col justify-center h-full min-h-0">
            <div id="login-card" class="glass-panel rounded-3xl p-6 sm:p-8 space-y-4 login-card-box">

                <!-- Encabezado de la tarjeta -->
                <div class="text-center space-y-1.5">
                    <img src="<?= BASE_URL ?>/public/img/logo.png" alt="INVENTORI BASAMA"
                         class="mx-auto h-14 w-14 rounded-2xl object-contain shadow-xl shadow-emerald-500/40 border border-emerald-500/40 bg-slate-950 p-1">
                    <h2 class="text-xl font-bold tracking-tight login-title">Acceso al Portal</h2>
                    <p class="text-xs login-subtitle">Ingresa con tus credenciales de usuario</p>
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
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #10b981 !important;">
                            Usuario / Correo Institucional
                        </label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-400 text-sm z-10">
                                <i class="fa-regular fa-user"></i>
                            </div>
                            <input id="email" name="email" type="email" required
                                class="block w-full pl-10 pr-4 py-2.5 rounded-xl text-sm font-medium shadow-inner"
                                style="background: rgba(2, 6, 23, 0.95) !important; color: #ffffff !important; border: 1px solid rgba(16, 185, 129, 0.4) !important;"
                                placeholder="usuario@santa.edu.co">
                        </div>
                    </div>

                    <!-- Campo: Contraseña -->
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider mb-1.5" style="color: #10b981 !important;">
                            Contraseña
                        </label>
                        <div class="relative rounded-xl">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-400 text-sm z-10">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" name="password" type="password" required
                                class="block w-full pl-10 pr-11 py-2.5 rounded-xl text-sm font-medium shadow-inner"
                                style="background: rgba(2, 6, 23, 0.95) !important; color: #ffffff !important; border: 1px solid rgba(16, 185, 129, 0.4) !important;"
                                placeholder="••••••••">
                            <button type="button" id="toggle-password"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-emerald-300 transition-colors text-sm focus:outline-none z-10"
                                title="Mostrar u ocultar contraseña">
                                <i class="fa-regular fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Botón de Ingreso con Efecto Dinámico de Cambio de Color -->
                    <button type="submit"
                        class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 via-teal-600 to-blue-700 hover:from-emerald-500 hover:to-cyan-500 active:from-cyan-400 active:to-emerald-500 text-white font-extrabold rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-400 shadow-lg shadow-emerald-600/40 active:scale-95 transition-all text-sm flex items-center justify-center space-x-2 btn-interactive cursor-pointer">
                        <span>Ingresar al Portal</span>
                        <span>→</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full text-center text-[11px] text-slate-400 py-2 relative z-10 shrink-0">
        Desarrollado para <strong class="text-emerald-400 font-medium">IEBSM Barrio Santa Margarita</strong> © 2023 - 2026
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

    // ---- Tema Oscuro Permanente por Defecto ----
    localStorage.setItem('login_theme', 'dark');
    document.body.classList.remove('light-mode');
});
</script>

<?php
// Cargar footer sin navegación
require_once APP_ROOT . '/views/layout/footer.php';
?>
