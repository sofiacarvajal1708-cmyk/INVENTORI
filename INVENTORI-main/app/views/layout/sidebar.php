<?php
/**
 * Barra de navegación superior horizontal (Top Navbar Adaptativo)
 */
$current_role = Session::get('role_name');
$current_url = $_GET['url'] ?? '';
$url_parts = explode('/', $current_url);
$active_page = !empty($url_parts[0]) ? $url_parts[0] : 'dashboard';
?>

<!-- Top Navbar Horizontal -->
<nav id="top-navbar" class="w-full bg-slate-900/90 backdrop-blur-md border-b border-white/10 glass-panel sticky top-0 z-40 shadow-2xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- 1. Logotipo y Nombre Institucional -->
            <div class="flex items-center space-x-3 shrink-0">
                <a href="<?= BASE_URL ?>/dashboard" class="flex items-center space-x-3 group">
                    <img src="<?= BASE_URL ?>/public/img/logo.png" alt="INVENTORI BASAMA" class="w-10 h-10 rounded-xl object-contain shadow-lg shadow-emerald-500/30 border border-emerald-500/40 bg-slate-950/80 p-0.5 group-hover:scale-105 transition-transform">
                    <div class="flex flex-col leading-tight">
                        <span class="font-extrabold text-white tracking-wider text-sm group-hover:text-emerald-400 transition-colors">INVENTORI BASAMA</span>
                        <span class="text-[9px] text-emerald-400 font-bold tracking-widest uppercase">IE Barrio Santa Margarita</span>
                    </div>
                </a>
            </div>

            <!-- 2. Menú de Navegación Horizontal (Pantallas Medianas y Grandes) -->
            <div class="hidden lg:flex items-center space-x-1 xl:space-x-2">
                
                <!-- Dashboard -->
                <a href="<?= BASE_URL ?>/dashboard" 
                   class="nav-link-hover px-3 py-2 rounded-xl text-xs font-semibold flex items-center space-x-2 transition-all <?= $active_page === 'dashboard' ? 'bg-gradient-to-r from-emerald-600/40 to-blue-900/50 border border-emerald-400 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-emerald-600/30 hover:border hover:border-emerald-400' ?>">
                    <i class="fa-solid fa-gauge-high text-emerald-400"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Equipos -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Docente', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/computadores" 
                   class="nav-link-hover px-3 py-2 rounded-xl text-xs font-semibold flex items-center space-x-2 transition-all <?= $active_page === 'computadores' ? 'bg-gradient-to-r from-emerald-600/40 to-blue-900/50 border border-emerald-400 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-emerald-600/30 hover:border hover:border-emerald-400' ?>">
                    <i class="fa-solid fa-desktop text-emerald-400"></i>
                    <span class="font-bold text-emerald-300">Equipos</span>
                </a>
                <?php endif; ?>

                <!-- Salas y Puestos -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Docente', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/salas" 
                   class="nav-link-hover px-3 py-2 rounded-xl text-xs font-semibold flex items-center space-x-2 transition-all <?= $active_page === 'salas' ? 'bg-gradient-to-r from-emerald-600/40 to-blue-900/50 border border-emerald-400 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-emerald-600/30 hover:border hover:border-emerald-400' ?>">
                    <i class="fa-solid fa-school text-blue-400"></i>
                    <span>Salas y Puestos</span>
                </a>
                <?php endif; ?>

                <!-- Préstamos -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Docente', 'Contralor', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/prestamos" 
                   class="nav-link-hover px-3 py-2 rounded-xl text-xs font-semibold flex items-center space-x-2 transition-all <?= $active_page === 'prestamos' ? 'bg-gradient-to-r from-emerald-600/40 to-blue-900/50 border border-emerald-400 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-emerald-600/30 hover:border hover:border-emerald-400' ?>">
                    <i class="fa-solid fa-handshake text-cyan-400"></i>
                    <span>Préstamos</span>
                </a>
                <?php endif; ?>

                <!-- Traslados -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/traslados" 
                   class="nav-link-hover px-3 py-2 rounded-xl text-xs font-semibold flex items-center space-x-2 transition-all <?= $active_page === 'traslados' ? 'bg-gradient-to-r from-emerald-600/40 to-blue-900/50 border border-emerald-400 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-emerald-600/30 hover:border hover:border-emerald-400' ?>">
                    <i class="fa-solid fa-truck-ramp-box text-indigo-400"></i>
                    <span>Traslados</span>
                </a>
                <?php endif; ?>

                <!-- Bajas RAEE -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/bajas" 
                   class="nav-link-hover px-3 py-2 rounded-xl text-xs font-semibold flex items-center space-x-2 transition-all <?= $active_page === 'bajas' ? 'bg-gradient-to-r from-emerald-600/40 to-blue-900/50 border border-emerald-400 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-emerald-600/30 hover:border hover:border-emerald-400' ?>">
                    <i class="fa-solid fa-recycle text-emerald-400"></i>
                    <span>Bajas RAEE</span>
                </a>
                <?php endif; ?>

                <!-- Gestión de Usuarios (Exclusivo Administrador) -->
                <?php if ($current_role === 'Administrador'): ?>
                <a href="<?= BASE_URL ?>/usuarios" 
                   class="nav-link-hover px-3 py-2 rounded-xl text-xs font-semibold flex items-center space-x-2 transition-all <?= $active_page === 'usuarios' ? 'bg-gradient-to-r from-emerald-600/40 to-blue-900/50 border border-emerald-400 text-white shadow-md' : 'text-slate-300 hover:text-white hover:bg-emerald-600/30 hover:border hover:border-emerald-400' ?>">
                    <i class="fa-solid fa-users-gear text-emerald-400"></i>
                    <span>Usuarios</span>
                </a>
                <?php endif; ?>

            </div>

            <!-- 3. Acciones de Usuario (Derecha) -->
            <div class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                
                <!-- Botón de Efectos Neón Tecnológicos (Elevación, Giro y Resplandor Verde/Azul) -->
                <button id="global-theme-toggle" type="button" onclick="toggleAppTheme(event)" 
                    class="btn-theme-hover p-2.5 rounded-xl bg-slate-900 border border-emerald-500/40 text-emerald-400 cursor-pointer active:scale-95 shadow-md shadow-emerald-500/20 flex items-center justify-center transition-all duration-300" 
                    title="Efecto Neón Tecnológico (Verde Esmeralda y Azul Oscuro)">
                    <i id="global-theme-icon" class="fa-solid fa-gear text-base text-emerald-400"></i>
                </button>

                <!-- Badge Usuario -->
                <div class="hidden sm:flex flex-col text-right leading-tight">
                    <span class="text-xs font-bold text-white"><?= Session::get('user_name') ?></span>
                    <span class="text-[9px] text-emerald-400 font-semibold tracking-wider uppercase"><?= Session::get('role_name') ?></span>
                </div>
                <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-600 to-blue-700 flex items-center justify-center text-white font-bold text-xs shadow-md shadow-emerald-600/30 border border-emerald-400/30">
                    <?= strtoupper(substr(Session::get('user_name'), 0, 1)) ?>
                </div>

                <!-- Botón Salir / Cerrar Sesión (Elevación y Resplandor Rojo en Hover) -->
                <a href="<?= BASE_URL ?>/auth/logout" 
                   class="btn-logout-hover p-2.5 rounded-xl bg-slate-900 border border-rose-500/30 text-rose-400 cursor-pointer active:scale-95 shadow-md flex items-center justify-center" 
                   title="Cerrar Sesión">
                    <i class="fa-solid fa-right-from-bracket text-base"></i>
                </a>

                <!-- Botón Menú Móvil -->
                <button onclick="toggleMobileMenu()" class="lg:hidden p-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-xl focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Menú Desplegable Móvil -->
    <div id="mobile-menu" class="hidden lg:hidden border-t border-white/10 bg-slate-900/95 px-4 pt-2 pb-4 space-y-1">
        <a href="<?= BASE_URL ?>/dashboard" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-emerald-600/20">Dashboard</a>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Docente', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/computadores" class="block px-3 py-2 rounded-xl text-xs font-semibold text-emerald-300 hover:bg-emerald-600/20">Equipos</a>
            <a href="<?= BASE_URL ?>/salas" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-emerald-600/20">Salas y Puestos</a>
        <?php endif; ?>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Docente', 'Contralor', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/prestamos" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-emerald-600/20">Préstamos</a>
        <?php endif; ?>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/traslados" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-emerald-600/20">Traslados</a>
        <?php endif; ?>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/bajas" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-emerald-600/20">Bajas RAEE</a>
        <?php endif; ?>
        <?php if ($current_role === 'Administrador'): ?>
            <a href="<?= BASE_URL ?>/usuarios" class="block px-3 py-2 rounded-xl text-xs font-semibold text-emerald-300 hover:bg-emerald-600/20">Gestión de Usuarios</a>
        <?php endif; ?>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) menu.classList.toggle('hidden');
}
</script>
