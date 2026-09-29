<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');

// ── Configuración visual POR ROL (claves deben coincidir con nombre_rol en BD) ───────────────────
$roleConfig = [
    'Administrador' => [
        'color'        => '#a855f7',
        'colorDim'     => 'rgba(168,85,247,0.15)',
        'colorBorder'  => 'rgba(168,85,247,0.35)',
        'colorGlow'    => 'rgba(168,85,247,0.08)',
        'bannerGrad'   => 'linear-gradient(135deg, rgba(88,28,135,0.55) 0%, rgba(126,34,206,0.2) 60%, transparent 100%)',
        'icon'         => 'fa-solid fa-crown',
        'label'        => 'ADMINISTRADOR',
        'subtitle'     => 'Control maestro del sistema, gestión de usuarios, seguridad de contraseñas y administración de inventario.',
    ],
    'Rector' => [
        'color'        => '#8b5cf6',
        'colorDim'     => 'rgba(139,92,246,0.15)',
        'colorBorder'  => 'rgba(139,92,246,0.35)',
        'colorGlow'    => 'rgba(139,92,246,0.08)',
        'bannerGrad'   => 'linear-gradient(135deg, rgba(76,29,149,0.45) 0%, rgba(49,46,129,0.2) 60%, transparent 100%)',
        'icon'         => 'fa-solid fa-user-tie',
        'label'        => 'RECTOR',
        'subtitle'     => 'Supervisión integral, control visual del inventario y veeduría de préstamos docentes.',
    ],
    'Almacenista' => [
        'color'        => '#06b6d4',
        'colorDim'     => 'rgba(6,182,212,0.15)',
        'colorBorder'  => 'rgba(6,182,212,0.35)',
        'colorGlow'    => 'rgba(6,182,212,0.08)',
        'bannerGrad'   => 'linear-gradient(135deg, rgba(8,51,68,0.55) 0%, rgba(7,89,133,0.2) 60%, transparent 100%)',
        'icon'         => 'fa-solid fa-boxes-stacked',
        'label'        => 'ALMACENISTA',
        'subtitle'     => 'Consulta y supervisión física del inventario de equipos y salas de la institución.',
    ],
    'Docente' => [
        'color'        => '#3b82f6',
        'colorDim'     => 'rgba(59,130,246,0.15)',
        'colorBorder'  => 'rgba(59,130,246,0.35)',
        'colorGlow'    => 'rgba(59,130,246,0.08)',
        'bannerGrad'   => 'linear-gradient(135deg, rgba(23,37,84,0.55) 0%, rgba(30,58,138,0.2) 60%, transparent 100%)',
        'icon'         => 'fa-solid fa-chalkboard-user',
        'label'        => 'DOCENTE',
        'subtitle'     => 'Solicitud de préstamos de terminales y planeación de experiencias pedagógicas.',
    ],
    'Docente TIC' => [
        'color'        => '#06b6d4',
        'colorDim'     => 'rgba(6,182,212,0.15)',
        'colorBorder'  => 'rgba(6,182,212,0.35)',
        'colorGlow'    => 'rgba(6,182,212,0.08)',
        'bannerGrad'   => 'linear-gradient(135deg, rgba(8,51,68,0.55) 0%, rgba(7,89,133,0.2) 60%, transparent 100%)',
        'icon'         => 'fa-solid fa-laptop-code',
        'label'        => 'DOCENTE TIC',
        'subtitle'     => 'Certificación TIC Aprobada. Solicitud prioritaria de préstamos y experiencias pedagógicas avanzadas.',
    ],
    'Docente No TIC' => [
        'color'        => '#64748b',
        'colorDim'     => 'rgba(100,116,139,0.15)',
        'colorBorder'  => 'rgba(100,116,139,0.35)',
        'colorGlow'    => 'rgba(100,116,139,0.08)',
        'bannerGrad'   => 'linear-gradient(135deg, rgba(30,41,59,0.55) 0%, rgba(51,65,85,0.2) 60%, transparent 100%)',
        'icon'         => 'fa-solid fa-book-open-reader',
        'label'        => 'DOCENTE NO TIC',
        'subtitle'     => 'Docente institucional. Consulta de inventario, disponibilidad de salas y capacitación TIC.',
    ],
    'Contralor' => [
        'color'        => '#f59e0b',
        'colorDim'     => 'rgba(245,158,11,0.15)',
        'colorBorder'  => 'rgba(245,158,11,0.35)',
        'colorGlow'    => 'rgba(245,158,11,0.08)',
        'bannerGrad'   => 'linear-gradient(135deg, rgba(69,26,3,0.55) 0%, rgba(120,53,15,0.2) 60%, transparent 100%)',
        'icon'         => 'fa-solid fa-scale-balanced',
        'label'        => 'CONTRALOR',
        'subtitle'     => 'Control social, auditoría del patrimonio tecnológico y transparencia institucional.',
    ],
];

// Si el rol no está en el mapa, usar un perfil genérico con el nombre real del rol
$rc = $roleConfig[$role] ?? [
    'color'       => '#64748b',
    'colorDim'    => 'rgba(100,116,139,0.15)',
    'colorBorder' => 'rgba(100,116,139,0.35)',
    'colorGlow'   => 'rgba(100,116,139,0.08)',
    'bannerGrad'  => 'linear-gradient(135deg, rgba(15,23,42,0.55) 0%, rgba(30,41,59,0.2) 60%, transparent 100%)',
    'icon'        => 'fa-solid fa-user',
    'label'       => strtoupper($role),
    'subtitle'    => 'Bienvenido al sistema de inventario tecnológico institucional.',
];
?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Estilos del dashboard por rol -->
<style>
:root {
    --role-color: <?= $rc['color'] ?>;
    --role-dim:   <?= $rc['colorDim'] ?>;
    --role-border:<?= $rc['colorBorder'] ?>;
    --role-glow:  <?= $rc['colorGlow'] ?>;
}
.role-icon-box {
    background: var(--role-dim);
    border: 1.5px solid var(--role-border);
    color: var(--role-color);
}
.role-badge {
    background: var(--role-dim);
    border: 1px solid var(--role-border);
    color: var(--role-color);
}
.role-banner {
    background: <?= $rc['bannerGrad'] ?>;
    border-left: 4px solid var(--role-color);
}
.role-btn-primary {
    background: var(--role-dim);
    border: 1px solid var(--role-border);
    color: #e2e8f0;
}
.role-btn-primary:hover {
    background: rgba(255,255,255,0.06);
    border-color: var(--role-color);
    color: #fff;
}
.role-btn-secondary:hover {
    background: var(--role-glow);
    border-color: var(--role-border);
    color: #fff;
}
.role-stat-block {
    background: var(--role-glow);
    border: 1px solid var(--role-border);
}
.kpi-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 1rem;
    padding: 1.1rem 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: border-color .2s, transform .2s;
}
.kpi-card:hover { border-color: rgba(255,255,255,0.14); transform: translateY(-2px); }
.kpi-icon {
    width: 2.75rem; height: 2.75rem;
    border-radius: .75rem;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem;
    transition: transform .2s;
}
.kpi-card:hover .kpi-icon { transform: scale(1.12); }
.section-card {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 1rem;
    padding: 1.25rem;
}
.action-btn {
    display: flex; align-items: center; gap: .75rem;
    padding: .7rem 1rem;
    border-radius: .75rem;
    font-size: .8rem;
    color: #cbd5e1;
    border: 1px solid rgba(255,255,255,0.07);
    background: rgba(255,255,255,0.03);
    transition: all .2s;
    text-decoration: none;
}
.action-btn:hover { background: var(--role-glow); border-color: var(--role-border); color:#fff; }
.action-btn.primary { background: var(--role-dim); border-color: var(--role-border); }
.action-btn .btn-icon { font-size: .95rem; color: var(--role-color); transition: transform .2s; }
.action-btn:hover .btn-icon { transform: scale(1.15); }
.stat-mini {
    background: rgba(0,0,0,0.25);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: .75rem;
    padding: .85rem 1rem;
}

/* Modo Claro en Dashboard */
body.light-mode .role-banner,
html.light-mode .role-banner {
    background: #ffffff !important;
    border-left: 4px solid var(--role-color) !important;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06) !important;
}
body.light-mode .kpi-card,
html.light-mode .kpi-card {
    background: #ffffff !important;
    border-color: rgba(0,0,0,0.08) !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04) !important;
}
body.light-mode .section-card,
html.light-mode .section-card {
    background: #ffffff !important;
    border-color: rgba(0,0,0,0.08) !important;
    box-shadow: 0 2px 10px rgba(0,0,0,0.04) !important;
}
body.light-mode .stat-mini,
html.light-mode .stat-mini {
    background: #f8fafc !important;
    border-color: rgba(0,0,0,0.08) !important;
}
body.light-mode .action-btn,
html.light-mode .action-btn {
    background: #f8fafc !important;
    border-color: rgba(0,0,0,0.1) !important;
    color: #1e293b !important;
}
body.light-mode .action-btn:hover,
html.light-mode .action-btn:hover {
    background: #ffffff !important;
    border-color: var(--role-color) !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06) !important;
}

</style>

<!-- Alerta de acceso denegado -->
<?php if (isset($_GET['forbidden'])): ?>
    <div class="auto-dismiss mb-4 px-4 py-3 rounded-xl border glass-panel text-rose-300 flex items-center gap-2 text-sm" style="border-color:rgba(244,63,94,.3);background:rgba(136,19,55,.18)">
        <i class="fa-solid fa-ban text-rose-400"></i>
        <span>No tienes permisos suficientes para acceder a ese módulo.</span>
    </div>
<?php endif; ?>

<!-- ════════════════════════════════════════════════════════════
     BANNER DE BIENVENIDA CON IDENTIDAD DE ROL
════════════════════════════════════════════════════════════ -->
<div class="glass-panel rounded-2xl p-5 mb-6 role-banner flex flex-col md:flex-row justify-between items-start md:items-center gap-4" style="border:1px solid rgba(255,255,255,.06)">
    <div class="flex items-center gap-4">
        <div class="role-icon-box w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg shrink-0" style="font-size:1.5rem">
            <i class="<?= $rc['icon'] ?>"></i>
        </div>
        <div>
            <div class="flex items-center gap-2 flex-wrap">
                <h2 class="text-xl font-bold text-white">¡Hola, <?= Session::get('user_name') ?>!</h2>
                <span class="role-badge text-xs font-bold px-2.5 py-0.5 rounded-full uppercase tracking-widest"><?= $rc['label'] ?></span>
            </div>
            <p class="text-xs text-slate-400 mt-0.5"><?= $rc['subtitle'] ?></p>
        </div>
    </div>
    <div class="flex items-center gap-3 shrink-0 flex-wrap">
        <div class="flex items-center gap-2 text-xs rounded-xl px-3 py-2" style="background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.25)">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse" style="display:inline-block"></span>
            <span class="text-emerald-300 font-medium">Sistema Activo</span>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════
     KPI CARDS
════════════════════════════════════════════════════════════ -->
<!-- ════════════════════════════════════════════════════════════
     KPI CARDS INTERACTIVAS CON HOVER
════════════════════════════════════════════════════════════ -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <!-- Card 1: Equipos -->
    <a href="<?= BASE_URL ?>/computadores" class="kpi-card kpi-card-hover block group">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-emerald-400 group-hover:text-emerald-300 uppercase tracking-wider mb-0.5">Equipos</div>
                <div class="text-2xl font-extrabold text-white group-hover:scale-105 transition-transform"><?= $stats['totalComputadores'] ?></div>
                <div class="text-xs text-slate-400">En inventario</div>
            </div>
            <div class="kpi-icon shadow-lg" style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);color:#10b981">
                <i class="fa-solid fa-desktop"></i>
            </div>
        </div>
    </a>

    <!-- Card 2: Préstamos -->
    <a href="<?= BASE_URL ?>/prestamos" class="kpi-card kpi-card-hover block group">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-cyan-400 group-hover:text-cyan-300 uppercase tracking-wider mb-0.5">Préstamos</div>
                <div class="text-2xl font-extrabold text-cyan-400 group-hover:scale-105 transition-transform"><?= $chartData['Operativo'] > 0 ? min($chartData['Operativo'], 5) : 0 ?></div>
                <div class="text-xs text-slate-400">Activos hoy</div>
            </div>
            <div class="kpi-icon shadow-lg" style="background:rgba(6,182,212,.15);border:1px solid rgba(6,182,212,.3);color:#06b6d4">
                <i class="fa-solid fa-handshake-angle"></i>
            </div>
        </div>
    </a>

    <!-- Card 3: Bajas RAEE -->
    <a href="<?= BASE_URL ?>/bajas" class="kpi-card kpi-card-hover block group">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-emerald-400 group-hover:text-emerald-300 uppercase tracking-wider mb-0.5">Bajas RAEE</div>
                <div class="text-2xl font-extrabold text-emerald-400 group-hover:scale-105 transition-transform"><?= $stats['bajasRAEE'] ?></div>
                <div class="text-xs text-slate-400">Certificadas</div>
            </div>
            <div class="kpi-icon shadow-lg" style="background:rgba(16,185,129,.15);border:1px solid rgba(16,185,129,.3);color:#10b981">
                <i class="fa-solid fa-recycle"></i>
            </div>
        </div>
    </a>

    <!-- Card 4: Salas Activas -->
    <a href="<?= BASE_URL ?>/salas" class="kpi-card kpi-card-hover block group">
        <div class="flex items-center justify-between">
            <div>
                <div class="text-xs font-bold text-blue-400 group-hover:text-blue-300 uppercase tracking-wider mb-0.5">Salas Activas</div>
                <div class="text-2xl font-extrabold text-blue-400 group-hover:scale-105 transition-transform"><?= $stats['totalSalas'] ?></div>
                <div class="text-xs text-slate-400">Espacios mapeados</div>
            </div>
            <div class="kpi-icon shadow-lg" style="background:rgba(59,130,246,.15);border:1px solid rgba(59,130,246,.3);color:#3b82f6">
                <i class="fa-solid fa-school"></i>
            </div>
        </div>
    </a>
</div>

<!-- ════════════════════════════════════════════════════════════
     GRÁFICO + ACCIONES RÁPIDAS
════════════════════════════════════════════════════════════ -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <!-- Gráfico -->
    <div class="section-card lg:col-span-2">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
            <i class="fa-solid fa-chart-pie" style="color:var(--role-color)"></i>
            Distribución de Activos Tecnológicos
        </h4>
        <div style="height:13rem;display:flex;align-items:center;justify-content:center">
            <canvas id="activeStatusChart" class="max-h-full"></canvas>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="section-card flex flex-col">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider pb-3 mb-4 flex items-center gap-2" style="border-bottom:1px solid rgba(255,255,255,.07)">
            <i class="fa-solid fa-bolt" style="color:var(--role-color)"></i>
            Acciones Rápidas
        </h4>
        <div class="space-y-2.5 flex-1">
            <?php if ($role === 'Administrador'): ?>
                <a href="<?= BASE_URL ?>/usuarios"              class="action-btn primary"><i class="btn-icon fa-solid fa-users-gear" style="color:#c084fc"></i><span>Gestión de Usuarios</span></a>
                <a href="<?= BASE_URL ?>/computadores/crear"    class="action-btn"><i class="btn-icon fa-solid fa-laptop-medical"></i><span>Registrar Computador</span></a>
                <a href="<?= BASE_URL ?>/salas"                 class="action-btn"><i class="btn-icon fa-solid fa-school"></i><span>Salas y Puestos</span></a>
                <a href="<?= BASE_URL ?>/traslados/crear"       class="action-btn"><i class="btn-icon fa-solid fa-truck-ramp-box"></i><span>Solicitar Traslado</span></a>
                <a href="<?= BASE_URL ?>/bajas/crear"           class="action-btn"><i class="btn-icon fa-solid fa-recycle" style="color:#10b981"></i><span>Registrar Baja RAEE</span></a>
            <?php elseif ($role === 'Rector'): ?>
                <a href="<?= BASE_URL ?>/computadores"          class="action-btn primary"><i class="btn-icon fa-solid fa-computer"></i><span>Ver todo el inventario</span></a>
                <a href="<?= BASE_URL ?>/prestamos"             class="action-btn"><i class="btn-icon fa-solid fa-handshake"></i><span>Supervisar préstamos</span></a>
                <a href="<?= BASE_URL ?>/salas"                 class="action-btn"><i class="btn-icon fa-solid fa-school"></i><span>Salas y espacios</span></a>
                <a href="<?= BASE_URL ?>/bajas"                 class="action-btn"><i class="btn-icon fa-solid fa-recycle" style="color:#10b981"></i><span>Historial bajas RAEE</span></a>
            <?php elseif ($role === 'Almacenista'): ?>
                <a href="<?= BASE_URL ?>/computadores"          class="action-btn primary"><i class="btn-icon fa-solid fa-computer"></i><span>Consultar inventario</span></a>
                <a href="<?= BASE_URL ?>/salas"                 class="action-btn"><i class="btn-icon fa-solid fa-school"></i><span>Salas y espacios</span></a>
                <a href="<?= BASE_URL ?>/traslados"             class="action-btn"><i class="btn-icon fa-solid fa-truck-ramp-box"></i><span>Historial traslados</span></a>
                <a href="<?= BASE_URL ?>/bajas"                 class="action-btn"><i class="btn-icon fa-solid fa-recycle" style="color:#10b981"></i><span>Bajas RAEE</span></a>
            <?php elseif (in_array($role, ['Docente', 'Docente TIC', 'Docente No TIC'])): ?>
                <a href="<?= BASE_URL ?>/prestamos/crear"       class="action-btn primary"><i class="btn-icon fa-solid fa-calendar-plus"></i><span>Solicitar préstamo</span></a>
                <a href="<?= BASE_URL ?>/prestamos"             class="action-btn"><i class="btn-icon fa-solid fa-clock-rotate-left"></i><span>Mis préstamos / Historial</span></a>
                <a href="<?= BASE_URL ?>/computadores"          class="action-btn"><i class="btn-icon fa-solid fa-computer"></i><span>Equipos disponibles</span></a>
                <a href="<?= BASE_URL ?>/salas"                 class="action-btn"><i class="btn-icon fa-solid fa-school"></i><span>Salas y espacios</span></a>
            <?php elseif ($role === 'Contralor'): ?>
                <a href="<?= BASE_URL ?>/computadores"          class="action-btn primary"><i class="btn-icon fa-solid fa-eye"></i><span>Auditar inventario</span></a>
                <a href="<?= BASE_URL ?>/salas"                 class="action-btn"><i class="btn-icon fa-solid fa-school"></i><span>Salas y puestos</span></a>
                <a href="<?= BASE_URL ?>/bajas"                 class="action-btn"><i class="btn-icon fa-solid fa-recycle" style="color:#10b981"></i><span>Verificar bajas RAEE</span></a>
                <a href="<?= BASE_URL ?>/prestamos"             class="action-btn"><i class="btn-icon fa-solid fa-handshake"></i><span>Préstamos registrados</span></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════════════════════════
     SECCIONES ESPECÍFICAS POR ROL
════════════════════════════════════════════════════════════ -->
<?php if ($role === 'Administrador'): ?>
<!-- ADMINISTRADOR: Consola de Administración Centralizada -->
<h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2 uppercase tracking-wider">
    <i class="fa-solid fa-screwdriver-wrench" style="color:var(--role-color)"></i>
    Consola de Administración Centralizada
</h3>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">
    <!-- Gestión de Usuarios y Accesos -->
    <div class="section-card flex flex-col justify-between" style="border-color:rgba(168,85,247,.25)">
        <div>
            <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2.5 mb-3" style="border-bottom:1px solid rgba(255,255,255,.06)">
                <i class="fa-solid fa-users-gear" style="color:#c084fc"></i> Usuarios del Sistema
            </h4>
            <p class="text-xs text-slate-400 mb-3">Total de cuentas institucionales configuradas con roles y contraseñas.</p>
            <div class="stat-mini mb-3">
                <span class="block text-xs font-bold text-slate-400 uppercase">Cuentas Registradas</span>
                <span class="text-2xl font-extrabold text-white"><?= $stats['totalUsuarios'] ?? 6 ?></span>
                <span class="block text-xs text-slate-500 mt-0.5">Control de acceso exclusivo</span>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/usuarios" class="action-btn primary justify-center">
            <i class="btn-icon fa-solid fa-users-gear"></i><span>Abrir Módulo de Usuarios</span>
        </a>
    </div>

    <!-- Gestión Integral de Inventario Fijo -->
    <div class="section-card flex flex-col justify-between" style="border-color:rgba(6,182,212,.25)">
        <div>
            <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2.5 mb-3" style="border-bottom:1px solid rgba(255,255,255,.06)">
                <i class="fa-solid fa-laptop-medical" style="color:#06b6d4"></i> Inventario & CRUD
            </h4>
            <p class="text-xs text-slate-400 mb-3">Registro de terminales, asignación de placas SED y gestión de piezas.</p>
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="stat-mini"><span class="block text-[10px] text-slate-400 uppercase">Terminales</span><span class="text-base font-bold text-white"><?= $stats['totalComputadores'] ?></span></div>
                <div class="stat-mini"><span class="block text-[10px] text-slate-400 uppercase">Salas</span><span class="text-base font-bold" style="color:#06b6d4"><?= $stats['totalSalas'] ?></span></div>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/computadores/crear" class="action-btn justify-center">
            <i class="btn-icon fa-solid fa-plus-circle"></i><span>Registrar Nuevo Equipo</span>
        </a>
    </div>

    <!-- Transacciones y Decomisos -->
    <div class="section-card flex flex-col justify-between" style="border-color:rgba(16,185,129,.25)">
        <div>
            <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2.5 mb-3" style="border-bottom:1px solid rgba(255,255,255,.06)">
                <i class="fa-solid fa-arrows-rotate" style="color:#10b981"></i> Operaciones & Bajas
            </h4>
            <p class="text-xs text-slate-400 mb-3">Aprobación de traslados con QR y registro de actas de disposición RAEE.</p>
            <div class="grid grid-cols-2 gap-2 mb-3">
                <div class="stat-mini"><span class="block text-[10px] text-slate-400 uppercase">Traslados</span><span class="text-base font-bold text-white"><?= $stats['totalTraslados'] ?></span></div>
                <div class="stat-mini"><span class="block text-[10px] text-slate-400 uppercase">Bajas</span><span class="text-base font-bold" style="color:#10b981"><?= $stats['bajasRAEE'] ?></span></div>
            </div>
        </div>
        <a href="<?= BASE_URL ?>/traslados" class="action-btn justify-center">
            <i class="btn-icon fa-solid fa-truck-ramp-box"></i><span>Revisar Traslados</span>
        </a>
    </div>
</div>
<?php endif; ?>

<?php if ($role === 'Rector'): ?>
<!-- RECTOR: Dimensiones de Gestión Institucional -->
<h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2 uppercase tracking-wider">
    <i class="fa-solid fa-chart-bar" style="color:var(--role-color)"></i>
    Indicadores de Rendimiento y Gestión Institucional
</h3>
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    <!-- Salud Operativa de Hardware -->
    <div class="section-card" style="border-color:rgba(139,92,246,.2)">
        <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2.5 mb-3" style="border-bottom:1px solid rgba(255,255,255,.06)">
            <i class="fa-solid fa-microchip" style="color:#8b5cf6"></i> 1. Operatividad y Rendimiento de Hardware
        </h4>
        <div class="grid grid-cols-2 gap-3">
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">Tasa Operatividad</span><span class="text-sm font-bold" style="color:#10b981"><?= number_format($stats['tasaOperatividad'], 1) ?>%</span><span class="block text-xs text-slate-500">Equipos listos</span></div>
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">Préstamos Totales</span><span class="text-sm font-bold text-white"><?= $stats['totalPrestamos'] ?></span><span class="block text-xs text-slate-500">Histórico digital</span></div>
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">Traslados Realizados</span><span class="text-sm font-bold" style="color:#06b6d4"><?= $stats['totalTraslados'] ?></span><span class="block text-xs text-slate-500">Con código QR</span></div>
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">Bajas Ecológicas</span><span class="text-sm font-bold" style="color:#f43f5e"><?= $stats['bajasRAEE'] ?></span><span class="block text-xs text-slate-500">Certificadas RAEE</span></div>
        </div>
    </div>
    <!-- Educativa -->
    <div class="section-card" style="border-color:rgba(6,182,212,.2)">
        <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2.5 mb-3" style="border-bottom:1px solid rgba(255,255,255,.06)">
            <i class="fa-solid fa-graduation-cap" style="color:#06b6d4"></i> 2. Educativa y TIC
        </h4>
        <div class="grid grid-cols-2 gap-3">
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">Idoneidad Docente</span><span class="text-sm font-bold" style="color:#06b6d4"><?= number_format($stats['tasaIdoneidadDocente'], 1) ?>%</span><span class="block text-xs text-slate-500">Aprobados TIC</span></div>
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">TAM (Aula)</span><span class="text-sm font-bold text-white"><?= number_format($stats['tam'], 1) ?>%</span><span class="block text-xs text-slate-500">Uso en clases</span></div>
            <div class="stat-mini col-span-2">
                <span class="block text-xs font-bold text-slate-400 uppercase mb-2">Cobertura Software Pedagógico</span>
                <div class="flex items-center gap-3">
                    <div class="flex-1 rounded-full h-2" style="background:rgba(255,255,255,.08)">
                        <div class="h-2 rounded-full" style="width:<?= $stats['coberturaSoftware'] ?>%;background:linear-gradient(90deg,#06b6d4,#8b5cf6)"></div>
                    </div>
                    <span class="text-sm font-bold text-white"><?= number_format($stats['coberturaSoftware'], 1) ?>%</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Ambiental -->
    <div class="section-card" style="border-color:rgba(16,185,129,.2)">
        <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2.5 mb-3" style="border-bottom:1px solid rgba(255,255,255,.06)">
            <i class="fa-solid fa-leaf" style="color:#10b981"></i> 3. Ambiental (Ley 1672)
        </h4>
        <div class="grid grid-cols-2 gap-3">
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">RAEE Canalizados</span><span class="text-sm font-bold" style="color:#10b981"><?= $stats['bajasRAEE'] ?> cert.</span><span class="block text-xs text-slate-500">Gestores autorizados</span></div>
            <div class="stat-mini"><span class="block text-xs font-bold text-slate-400 uppercase">Ahorro Papel</span><span class="text-sm font-bold text-white"><?= $stats['ahorroHojasPapel'] ?> hojas</span><span class="block text-xs text-slate-500">Actas digitales</span></div>
            <div class="stat-mini col-span-2"><span class="block text-xs font-bold text-slate-400 uppercase">Salas con Riesgo Eléctrico</span><span class="text-sm font-bold" style="color:#f43f5e"><?= $stats['salasRiesgo'] ?> / <?= $stats['totalSalas'] ?> salas</span><span class="block text-xs text-slate-500">Sin polo a tierra o estabilizador</span></div>
        </div>
    </div>
    <!-- Social -->
    <div class="section-card flex flex-col" style="border-color:rgba(139,92,246,.2)">
        <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2 pb-2.5 mb-3" style="border-bottom:1px solid rgba(255,255,255,.06)">
            <i class="fa-solid fa-users" style="color:#8b5cf6"></i> 4. Social y Transparencia
        </h4>
        <p class="text-xs text-slate-400 leading-relaxed flex-1">
            Portal abierto de consulta para el <strong class="text-white">Contralor</strong> y la comunidad educativa. Auditoría y veeduría en tiempo real del patrimonio tecnológico.
        </p>
        <a href="<?= BASE_URL ?>/computadores" class="action-btn primary mt-3 justify-center">
            <i class="btn-icon fa-solid fa-laptop"></i><span>Consultar Inventario General</span>
        </a>
    </div>
</div>

<?php elseif ($role === 'Almacenista'): ?>
<!-- ALMACENISTA: Estado del inventario -->
<div class="section-card mb-6" style="border-color:rgba(6,182,212,.2)">
    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
        <i class="fa-solid fa-warehouse" style="color:#06b6d4"></i> Estado del Inventario Físico
    </h4>
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="text-center p-4 rounded-xl" style="background:rgba(16,185,129,.08);border:1px solid rgba(16,185,129,.25)">
            <div class="text-2xl font-extrabold" style="color:#10b981"><?= $chartData['Operativo'] ?></div>
            <div class="text-xs text-slate-400 uppercase font-bold mt-1">Operativos</div>
        </div>
        <div class="text-center p-4 rounded-xl" style="background:rgba(245,158,11,.08);border:1px solid rgba(245,158,11,.25)">
            <div class="text-2xl font-extrabold" style="color:#f59e0b"><?= $chartData['En Mantenimiento'] ?></div>
            <div class="text-xs text-slate-400 uppercase font-bold mt-1">Mantenimiento</div>
        </div>
        <div class="text-center p-4 rounded-xl" style="background:rgba(99,102,241,.08);border:1px solid rgba(99,102,241,.25)">
            <div class="text-2xl font-extrabold" style="color:#6366f1"><?= $chartData['Obsoleto'] ?></div>
            <div class="text-xs text-slate-400 uppercase font-bold mt-1">Obsoletos</div>
        </div>
        <div class="text-center p-4 rounded-xl" style="background:rgba(244,63,94,.08);border:1px solid rgba(244,63,94,.25)">
            <div class="text-2xl font-extrabold" style="color:#f43f5e"><?= $chartData['Dado de Baja'] ?></div>
            <div class="text-xs text-slate-400 uppercase font-bold mt-1">Dados de Baja</div>
        </div>
    </div>
</div>

<?php elseif (in_array($role, ['Docente', 'Docente TIC', 'Docente No TIC'])): ?>
<!-- DOCENTES: Guía de uso -->
<div class="section-card mb-6" style="border-color:<?= $rc['colorBorder'] ?>">
    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
        <i class="fa-solid fa-circle-info" style="color:var(--role-color)"></i> Guía de Uso del Sistema
    </h4>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs text-slate-300">
        <?php $steps = [
            ['Solicitar Préstamo','fa-solid fa-calendar-plus','Ingresa a "Acciones Rápidas" y selecciona "Solicitar préstamo" para reservar un equipo disponible.'],
            ['Consultar Equipos','fa-solid fa-computer','Revisa el catálogo de computadores con sus especificaciones, estado y disponibilidad actual.'],
            ['Ver Historial','fa-solid fa-clock-rotate-left','Accede al historial de tus préstamos anteriores con fechas y detalles de cada equipo asignado.'],
        ]; ?>
        <?php foreach ($steps as $i => $s): ?>
        <div class="p-4 rounded-xl space-y-2" style="background:rgba(0,0,0,.2);border:1px solid rgba(255,255,255,.06)">
            <div class="w-9 h-9 rounded-lg flex items-center justify-center text-base mb-2" style="background:var(--role-dim);border:1px solid var(--role-border);color:var(--role-color)">
                <i class="<?= $s[1] ?>"></i>
            </div>
            <p class="font-semibold text-white"><?= $s[0] ?></p>
            <p class="text-slate-400 leading-snug"><?= $s[2] ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php elseif ($role === 'Contralor'): ?>
<!-- CONTRALOR: Indicadores de auditoría -->
<div class="section-card mb-6" style="border-color:rgba(245,158,11,.25)">
    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center gap-2">
        <i class="fa-solid fa-scale-balanced" style="color:#f59e0b"></i> Indicadores de Transparencia y Control
    </h4>
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-4 rounded-xl" style="background:rgba(139,92,246,.07);border:1px solid rgba(139,92,246,.25)">
            <span class="block text-xs font-bold text-slate-400 uppercase">Equipos en Veeduría</span>
            <span class="text-xl font-extrabold text-white"><?= $stats['totalComputadores'] ?></span>
            <span class="block text-xs text-slate-500 mt-0.5">Total inventariados</span>
        </div>
        <div class="p-4 rounded-xl" style="background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.25)">
            <span class="block text-xs font-bold text-slate-400 uppercase">RAEE Certificadas</span>
            <span class="text-xl font-extrabold" style="color:#10b981"><?= $stats['bajasRAEE'] ?></span>
            <span class="block text-xs text-slate-500 mt-0.5">Con acta de destrucción</span>
        </div>
        <div class="p-4 rounded-xl" style="background:rgba(245,158,11,.07);border:1px solid rgba(245,158,11,.25)">
            <span class="block text-xs font-bold text-slate-400 uppercase">Ahorro Papel</span>
            <span class="text-xl font-extrabold" style="color:#f59e0b"><?= $stats['ahorroHojasPapel'] ?> hojas</span>
            <span class="block text-xs text-slate-500 mt-0.5">Actas digitalizadas</span>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ════════════════════════════════════════════════════════════
     LOG DE AUDITORÍA (Rector y Almacenista)
════════════════════════════════════════════════════════════ -->
<?php if (in_array($role, ['Rector', 'Almacenista'])): ?>
<div class="section-card">
    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 pb-3 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,.07)">
        <div class="flex items-center gap-2">
            <i class="fa-solid fa-user-shield" style="color:#8b5cf6"></i>
            <span>Log de Auditoría de Seguridad</span>
        </div>
        <span class="text-xs px-2.5 py-1 rounded-full font-bold uppercase tracking-wider" style="background:rgba(139,92,246,.15);color:#a78bfa;border:1px solid rgba(139,92,246,.3)">AES-256 Activo</span>
    </h4>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs" style="border-collapse:collapse">
            <thead>
                <tr class="text-slate-400 font-bold uppercase tracking-wider" style="border-bottom:1px solid rgba(255,255,255,.07)">
                    <th class="py-2.5 px-2">Fecha/Hora</th>
                    <th class="py-2.5 px-2">Usuario</th>
                    <th class="py-2.5 px-2">Acción</th>
                    <th class="py-2.5 px-2">Tabla</th>
                    <th class="py-2.5 px-2">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentLogs)): ?>
                    <tr><td colspan="5" class="py-4 text-center text-slate-500">No hay registros de auditoría aún.</td></tr>
                <?php else: ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr class="text-slate-300 transition-colors" style="border-bottom:1px solid rgba(255,255,255,.04)" onmouseover="this.style.background='rgba(255,255,255,.02)'" onmouseout="this.style.background=''">
                            <td class="py-2.5 px-2 text-slate-400 whitespace-nowrap"><?= $log['fecha_evento'] ?></td>
                            <td class="py-2.5 px-2 whitespace-nowrap">
                                <span class="font-semibold text-white"><?= $log['nombres'] . ' ' . $log['apellidos'] ?></span>
                                <span class="block text-slate-500" style="font-size:.65rem"><?= $log['email'] ?></span>
                            </td>
                            <td class="py-2.5 px-2 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded font-semibold" style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.08)"><?= $log['accion_ejecutada'] ?></span>
                            </td>
                            <td class="py-2.5 px-2 text-slate-500 font-mono whitespace-nowrap"><?= $log['tabla_afectada'] ?: '-' ?></td>
                            <td class="py-2.5 px-2 text-slate-300 italic">"<?= htmlspecialchars($log['detalles_desencriptados']) ?>"</td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const ctx = document.getElementById('activeStatusChart');
    if (!ctx) return;
    new Chart(ctx.getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: ['Operativo', 'En Mantenimiento', 'Obsoleto', 'Dado de Baja'],
            datasets: [{
                data: [
                    <?= $chartData['Operativo'] ?>,
                    <?= $chartData['En Mantenimiento'] ?>,
                    <?= $chartData['Obsoleto'] ?>,
                    <?= $chartData['Dado de Baja'] ?>
                ],
                backgroundColor: ['rgba(16,185,129,.45)','rgba(245,158,11,.45)','rgba(99,102,241,.45)','rgba(244,63,94,.45)'],
                borderColor:     ['#10b981','#f59e0b','#6366f1','#f43f5e'],
                borderWidth: 2,
                hoverOffset: 12
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: { color: '#94a3b8', font: { family: 'Outfit', size: 11 }, padding: 14 }
                }
            },
            cutout: '68%'
        }
    });
});
</script>

<?php require_once APP_ROOT . '/views/layout/footer.php'; ?>
