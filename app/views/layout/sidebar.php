<?php
/**
 * Menú lateral de navegación - Sidebar adaptativo
 */
$current_role = Session::get('role_name');
$current_url = $_GET['url'] ?? '';
// Extraer la primera parte de la URL para resaltar la opción activa
$url_parts = explode('/', $current_url);
$active_page = !empty($url_parts[0]) ? $url_parts[0] : 'dashboard';
?>
<!-- Sidebar -->
<aside id="main-sidebar" class="glass-panel border-r border-white/5 w-64 h-screen fixed md:static inset-y-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-20 flex flex-col justify-between">
    <!-- Logotipo y Nombre -->
    <div class="p-6 border-b border-white/5">
        <a href="<?= BASE_URL ?>/dashboard" class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-violet-600 to-cyan-500 flex items-center justify-center shadow-lg shadow-violet-500/20">
                <i class="fa-solid fa-cubes text-white text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-white tracking-wider text-sm">INVENTARIO</span>
                <span class="text-[10px] text-violet-400 font-semibold tracking-widest uppercase">IE Barrio Santa Margarita</span>
            </div>
        </a>
    </div>

    <!-- Menú de Enlaces -->
    <div class="flex-1 py-6 overflow-y-auto px-4 space-y-6">
        
        <!-- Sección de Inicio -->
        <div>
            <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Inicio</span>
            <ul class="mt-2 space-y-1">
                <li>
                    <a href="<?= BASE_URL ?>/dashboard" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?= $active_page === 'dashboard' ? 'bg-gradient-to-r from-violet-600/20 to-cyan-500/10 border-l-4 border-violet-500 text-white pl-4' : 'text-slate-400 hover:text-white hover:bg-white/5' ?>">
                        <i class="fa-solid fa-gauge-high text-base w-5 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sección de Activos -->
        <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Contralor Escolar'])): ?>
        <div>
            <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Activos</span>
            <ul class="mt-2 space-y-1">
                <li>
                    <a href="<?= BASE_URL ?>/computadores" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?= $active_page === 'computadores' ? 'bg-gradient-to-r from-violet-600/20 to-cyan-500/10 border-l-4 border-violet-500 text-white pl-4' : 'text-slate-400 hover:text-white hover:bg-white/5' ?>">
                        <i class="fa-solid fa-laptop text-base w-5 text-center"></i>
                        <span>Computadores</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/salas" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?= $active_page === 'salas' ? 'bg-gradient-to-r from-violet-600/20 to-cyan-500/10 border-l-4 border-violet-500 text-white pl-4' : 'text-slate-400 hover:text-white hover:bg-white/5' ?>">
                        <i class="fa-solid fa-school text-base w-5 text-center"></i>
                        <span>Salas y Puestos</span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

        <!-- Sección Operativa -->
        <div>
            <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Operaciones</span>
            <ul class="mt-2 space-y-1">
                <?php if (in_array($current_role, ['Rector', 'Almacenista', 'Docente', 'Contralor Escolar'])): ?>
                <li>
                    <a href="<?= BASE_URL ?>/prestamos" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?= $active_page === 'prestamos' ? 'bg-gradient-to-r from-violet-600/20 to-cyan-500/10 border-l-4 border-violet-500 text-white pl-4' : 'text-slate-400 hover:text-white hover:bg-white/5' ?>">
                        <i class="fa-solid fa-handshake text-base w-5 text-center"></i>
                        <span>Préstamos</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (in_array($current_role, ['Rector', 'Almacenista'])): ?>
                <li>
                    <a href="<?= BASE_URL ?>/traslados" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?= $active_page === 'traslados' ? 'bg-gradient-to-r from-violet-600/20 to-cyan-500/10 border-l-4 border-violet-500 text-white pl-4' : 'text-slate-400 hover:text-white hover:bg-white/5' ?>">
                        <i class="fa-solid fa-truck-ramp-box text-base w-5 text-center"></i>
                        <span>Traslados</span>
                    </a>
                </li>
                <li>
                    <a href="<?= BASE_URL ?>/bajas" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?= $active_page === 'bajas' ? 'bg-gradient-to-r from-violet-600/20 to-cyan-500/10 border-l-4 border-violet-500 text-white pl-4' : 'text-slate-400 hover:text-white hover:bg-white/5' ?>">
                        <i class="fa-solid fa-recycle text-base w-5 text-center"></i>
                        <span>Bajas RAEE</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Sección de Control Social & Reportes -->
        <?php if (in_array($current_role, ['Rector', 'Contralor Escolar'])): ?>
        <div>
            <span class="px-3 text-[10px] font-bold text-slate-500 uppercase tracking-widest">Veeduría</span>
            <ul class="mt-2 space-y-1">
                <li>
                    <a href="<?= BASE_URL ?>/reportes" class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 <?= $active_page === 'reportes' ? 'bg-gradient-to-r from-violet-600/20 to-cyan-500/10 border-l-4 border-violet-500 text-white pl-4' : 'text-slate-400 hover:text-white hover:bg-white/5' ?>">
                        <i class="fa-solid fa-chart-pie text-base w-5 text-center"></i>
                        <span>Analítica Contable</span>
                    </a>
                </li>
            </ul>
        </div>
        <?php endif; ?>

    </div>

    <!-- Sección de Información de Sesión -->
    <div class="p-4 border-t border-white/5 bg-black/10">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-shield-halved text-xs"></i>
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-xs font-semibold text-slate-300 truncate"><?= Session::get('user_email') ?></span>
                <span class="text-[9px] text-slate-500">IP: <?= $_SERVER['REMOTE_ADDR'] ?></span>
            </div>
        </div>
    </div>
</aside>
