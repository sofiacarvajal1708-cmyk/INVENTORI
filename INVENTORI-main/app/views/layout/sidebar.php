<?php
/**
 * Barra de navegación superior horizontal (Top Navbar Adaptativo)
 */
$current_role = Session::get('role_name');
$current_url = $_GET['url'] ?? '';
$url_parts = explode('/', $current_url);
$active_page = !empty($url_parts[0]) ? $url_parts[0] : 'dashboard';
?>

<!-- Top Navbar Horizontal Glassmorphism -->
<nav id="top-navbar" class="w-full bg-slate-900/90 backdrop-blur-xl border-b border-white/10 glass-panel sticky top-0 z-40 shadow-lg">
    <div class="w-full max-w-[98vw] mx-auto px-2 sm:px-4 lg:px-6">
        <div class="flex items-center justify-between h-16 gap-2">
            
            <!-- 1. Logotipo y Nombre Institucional -->
            <div class="flex items-center space-x-2 shrink-0">
                <a href="<?= BASE_URL ?>/dashboard" class="flex items-center space-x-2 group">
                    <img src="<?= BASE_URL ?>/public/img/logo.png?v=<?= time() ?>" alt="I.E. Barrio Santa Margarita" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl object-contain border border-slate-300 bg-white p-0.5 group-hover:scale-105 transition-transform shadow-sm shrink-0">
                    <div class="flex flex-col justify-center leading-none space-y-0.5 min-w-0">
                        <span class="font-extrabold text-white tracking-tight text-xs sm:text-sm group-hover:text-blue-400 transition-colors whitespace-nowrap">INVENTORI BASAMA</span>
                        <span class="hidden xl:block text-[10px] text-blue-400 font-bold tracking-tight uppercase whitespace-nowrap">I.E. Barrio Santa Margarita</span>
                    </div>
                </a>
            </div>

            <!-- 2. Menú de Navegación Horizontal (Espaciado Inteligente & Efecto de Color al Tocar) -->
            <div class="hidden lg:flex items-center space-x-1 xl:space-x-1.5 overflow-x-auto no-scrollbar py-1">
                
                <!-- Dashboard -->
                <a href="<?= BASE_URL ?>/dashboard" 
                   class="nav-link-hover px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all whitespace-nowrap <?= $active_page === 'dashboard' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25 border border-blue-400/40' : 'text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:shadow-md' ?>">
                    <i class="fa-solid fa-gauge-high text-blue-400"></i>
                    <span>Dashboard</span>
                </a>

                <!-- Equipos -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Docente', 'Docente TIC', 'Docente No TIC', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/computadores" 
                   class="nav-link-hover px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all whitespace-nowrap <?= $active_page === 'computadores' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25 border border-blue-400/40' : 'text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:shadow-md' ?>">
                    <i class="fa-solid fa-desktop text-emerald-400"></i>
                    <span>Equipos</span>
                </a>
                <?php endif; ?>

                <!-- Salas y Puestos -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Docente', 'Docente TIC', 'Docente No TIC', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/salas" 
                   class="nav-link-hover px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all whitespace-nowrap <?= $active_page === 'salas' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25 border border-blue-400/40' : 'text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:shadow-md' ?>">
                    <i class="fa-solid fa-school text-sky-400"></i>
                    <span>Salas</span>
                </a>
                <?php endif; ?>

                <!-- Préstamos -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Docente', 'Docente TIC', 'Docente No TIC', 'Contralor', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/prestamos" 
                   class="nav-link-hover px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all whitespace-nowrap <?= $active_page === 'prestamos' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25 border border-blue-400/40' : 'text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:shadow-md' ?>">
                    <i class="fa-solid fa-handshake text-cyan-400"></i>
                    <span>Préstamos</span>
                </a>
                <?php endif; ?>

                <!-- Traslados -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/traslados" 
                   class="nav-link-hover px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all whitespace-nowrap <?= $active_page === 'traslados' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25 border border-blue-400/40' : 'text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:shadow-md' ?>">
                    <i class="fa-solid fa-truck-ramp-box text-purple-400"></i>
                    <span>Traslados</span>
                </a>
                <?php endif; ?>

                <!-- Bajas RAEE -->
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Administrador'])): ?>
                <a href="<?= BASE_URL ?>/bajas" 
                   class="nav-link-hover px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all whitespace-nowrap <?= $active_page === 'bajas' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25 border border-blue-400/40' : 'text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:shadow-md' ?>">
                    <i class="fa-solid fa-recycle text-emerald-400"></i>
                    <span>Bajas RAEE</span>
                </a>
                <?php endif; ?>

                <!-- Gestión de Usuarios -->
                <?php if ($current_role === 'Administrador'): ?>
                <a href="<?= BASE_URL ?>/usuarios" 
                   class="nav-link-hover px-2.5 py-1.5 rounded-xl text-xs font-bold flex items-center space-x-1.5 transition-all whitespace-nowrap <?= $active_page === 'usuarios' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md shadow-blue-500/25 border border-blue-400/40' : 'text-slate-300 hover:text-white hover:bg-gradient-to-r hover:from-blue-600 hover:to-indigo-600 hover:shadow-md' ?>">
                    <i class="fa-solid fa-users-gear text-purple-400"></i>
                    <span>Usuarios</span>
                </a>
                <?php endif; ?>

            </div>

            <!-- 3. Acciones de Usuario (Derecha) -->
            <div class="flex items-center space-x-1.5 sm:space-x-2 shrink-0">
                
                <!-- Botón de Tema (Cambia a Color Ámbar/Azul al Tocar) -->
                <button id="global-theme-toggle" type="button" onclick="toggleAppTheme(event)" 
                    class="btn-theme-hover p-2 rounded-xl bg-slate-900/90 border border-slate-700/80 text-slate-300 cursor-pointer active:scale-95 shadow-md flex items-center justify-center transition-all" 
                    title="Cambiar Tema (Modo Claro / Modo Oscuro)">
                    <i id="global-theme-icon" class="fa-solid fa-sun text-sm text-amber-400"></i>
                </button>

                <!-- Badge Usuario -->
                <div class="hidden xl:flex flex-col text-right leading-none space-y-0.5">
                    <span class="text-xs font-bold text-white whitespace-nowrap"><?= Session::get('user_name') ?></span>
                    <span class="text-[10px] text-blue-400 font-extrabold tracking-wider uppercase whitespace-nowrap"><?= Session::get('role_name') ?></span>
                </div>

                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-gradient-to-tr from-blue-600 to-indigo-600 flex items-center justify-center text-white font-extrabold text-xs shadow-md shadow-blue-500/20 border border-blue-400/30 shrink-0">
                    <?= strtoupper(substr(Session::get('user_name'), 0, 1)) ?>
                </div>

                <!-- Botón Salir / Cerrar Sesión (Cambia a Rojo al Tocar) -->
                <a href="<?= BASE_URL ?>/auth/logout" 
                   class="btn-logout-hover p-2 rounded-xl bg-slate-900/90 border border-slate-700/80 text-slate-400 hover:text-white cursor-pointer active:scale-95 shadow-md flex items-center justify-center transition-all" 
                   title="Cerrar Sesión">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                </a>

                <!-- Botón Menú Móvil -->
                <button onclick="toggleMobileMenu()" class="lg:hidden p-2 text-slate-300 hover:text-white hover:bg-slate-800 rounded-xl focus:outline-none">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>
            </div>

        </div>
    </div>

    <!-- Menú Desplegable Móvil -->
    <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-800 bg-slate-900/95 backdrop-blur-xl px-4 pt-2 pb-4 space-y-1">
        <a href="<?= BASE_URL ?>/dashboard" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-blue-600 hover:text-white">Dashboard</a>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Docente', 'Docente TIC', 'Docente No TIC', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/computadores" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-blue-600 hover:text-white">Equipos</a>
            <a href="<?= BASE_URL ?>/salas" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-blue-600 hover:text-white">Salas</a>
        <?php endif; ?>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Docente', 'Docente TIC', 'Docente No TIC', 'Contralor', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/prestamos" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-blue-600 hover:text-white">Préstamos</a>
        <?php endif; ?>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/traslados" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-blue-600 hover:text-white">Traslados</a>
        <?php endif; ?>
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor', 'Administrador'])): ?>
            <a href="<?= BASE_URL ?>/bajas" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-blue-600 hover:text-white">Bajas RAEE</a>
        <?php endif; ?>
        <?php if ($current_role === 'Administrador'): ?>
            <a href="<?= BASE_URL ?>/usuarios" class="block px-3 py-2 rounded-xl text-xs font-semibold text-slate-200 hover:bg-blue-600 hover:text-white">Gestión de Usuarios</a>
        <?php endif; ?>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) menu.classList.toggle('hidden');
}
</script>
