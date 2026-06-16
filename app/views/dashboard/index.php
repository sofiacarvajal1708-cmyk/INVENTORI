<?php
require_once APP_ROOT . '/views/layout/header.php';
$role = Session::get('role_name');
?>

<!-- Biblioteca de Gráficos Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Alerta de acceso denegado -->
<?php if (isset($_GET['forbidden'])): ?>
    <div class="auto-dismiss mb-6 px-4 py-3 rounded-xl border border-rose-500/20 bg-rose-950/30 text-rose-300 flex items-center space-x-2 glass-panel">
        <i class="fa-solid fa-ban text-rose-400"></i>
        <span>No tienes permisos suficientes para acceder a ese módulo.</span>
    </div>
<?php endif; ?>

<!-- Fila de bienvenida premium -->
<div class="glass-panel rounded-2xl p-6 mb-8 border border-white/5 bg-gradient-to-r from-violet-950/20 via-indigo-950/10 to-transparent flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h2 class="text-2xl font-bold text-white">¡Hola, <?= Session::get('user_name') ?>!</h2>
        <p class="text-sm text-slate-400 mt-1">Estás operando en el sistema con el rol de <span class="text-violet-400 font-semibold uppercase"><?= $role ?></span>.</p>
    </div>
    <div class="flex items-center space-x-2 text-xs bg-slate-800/40 border border-white/5 rounded-xl px-4 py-2.5">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
        <span class="text-slate-300 font-semibold">Conectado a Base de Datos phpMyAdmin</span>
    </div>
</div>

<!-- Grid de Tarjetas de Indicadores Macro -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Tarjeta Total Computadores -->
    <div class="glass-panel glass-panel-hover rounded-2xl p-5 border border-white/5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Computadores</span>
            <h3 class="text-3xl font-extrabold text-white"><?= $stats['totalComputadores'] ?></h3>
            <span class="text-xs text-slate-400">Equipos en inventario</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-violet-600/10 border border-violet-500/20 flex items-center justify-center text-violet-400">
            <i class="fa-solid fa-computer text-xl"></i>
        </div>
    </div>

    <!-- Tarjeta Préstamos Activos -->
    <div class="glass-panel glass-panel-hover rounded-2xl p-5 border border-white/5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Préstamos Activos</span>
            <h3 class="text-3xl font-extrabold text-cyan-400"><?= $chartData['Operativo'] > 0 ? min($chartData['Operativo'], 5) : 0 ?></h3>
            <span class="text-xs text-slate-400">En uso por docentes</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
            <i class="fa-solid fa-handshake-angle text-xl"></i>
        </div>
    </div>

    <!-- Tarjeta Bajas RAEE -->
    <div class="glass-panel glass-panel-hover rounded-2xl p-5 border border-white/5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Bajas RAEE</span>
            <h3 class="text-3xl font-extrabold text-emerald-400"><?= $stats['bajasRAEE'] ?></h3>
            <span class="text-xs text-slate-400">Destrucción certificada</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
            <i class="fa-solid fa-recycle text-xl"></i>
        </div>
    </div>

    <!-- Tarjeta Salas Registradas -->
    <div class="glass-panel glass-panel-hover rounded-2xl p-5 border border-white/5 flex items-center justify-between">
        <div class="space-y-1">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Salas Activas</span>
            <h3 class="text-3xl font-extrabold text-indigo-400"><?= $stats['totalSalas'] ?></h3>
            <span class="text-xs text-slate-400">Espacios mapeados</span>
        </div>
        <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
            <i class="fa-solid fa-school text-xl"></i>
        </div>
    </div>

</div>

<!-- DETALLE DE LAS 4 DIMENSIONES INSTITUCIONALES -->
<h3 class="text-lg font-bold text-white mb-6 flex items-center space-x-2">
    <i class="fa-solid fa-chart-bar text-violet-400"></i>
    <span>Indicadores de las Cuatro Dimensiones (Anteproyecto)</span>
</h3>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    
    <!-- Dimensión 1: Financiera y de Control Fiscal -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 space-y-4">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center space-x-2 border-b border-white/5 pb-3">
            <i class="fa-solid fa-dollar-sign text-emerald-400"></i>
            <span>1. Dimensión Financiera y Control Fiscal</span>
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Costo de Propiedad (TCO)</span>
                <span class="text-base font-bold text-white">$<?= number_format($stats['tco'], 2) ?></span>
                <span class="block text-[9px] text-slate-500">Costo promedio por activo</span>
            </div>
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Depreciación Anual FSE</span>
                <span class="text-base font-bold text-violet-400">$<?= number_format($stats['depreciacionAnualTotal'], 2) ?></span>
                <span class="block text-[9px] text-slate-500">Desgaste calculado</span>
            </div>
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Obsolescencia Financiera</span>
                <span class="text-base font-bold text-yellow-500"><?= number_format($stats['obsolescenciaFinanciera'], 1) ?>%</span>
                <span class="block text-[9px] text-slate-500">Progreso de vida útil</span>
            </div>
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Pérdidas Siniestradas</span>
                <span class="text-base font-bold text-rose-500">$<?= number_format($stats['perdidaSiniestros'], 2) ?></span>
                <span class="block text-[9px] text-slate-500">Equipos robados/perdidos</span>
            </div>
        </div>
    </div>

    <!-- Dimensión 2: Educativa y Desarrollo Digital -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 space-y-4">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center space-x-2 border-b border-white/5 pb-3">
            <i class="fa-solid fa-graduation-cap text-cyan-400"></i>
            <span>2. Dimensión Educativa y TIC</span>
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Tasa de Idoneidad Docente</span>
                <span class="text-base font-bold text-cyan-400"><?= number_format($stats['tasaIdoneidadDocente'], 1) ?>%</span>
                <span class="block text-[9px] text-slate-500">Docentes aprobados en TIC</span>
            </div>
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Aprovechamiento Aula (TAM)</span>
                <span class="text-base font-bold text-white"><?= number_format($stats['tam'], 1) ?>%</span>
                <span class="block text-[9px] text-slate-500">Uso activo en clases</span>
            </div>
            <div class="p-3 bg-black/20 rounded-xl border border-white/5 col-span-2">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Cobertura Software Pedagógico</span>
                <div class="flex items-center space-x-3 mt-1">
                    <div class="flex-1 bg-slate-800 rounded-full h-2">
                        <div class="bg-gradient-to-r from-cyan-500 to-violet-600 h-2 rounded-full" style="width: <?= $stats['coberturaSoftware'] ?>%"></div>
                    </div>
                    <span class="text-sm font-bold text-white"><?= number_format($stats['coberturaSoftware'], 1) ?>%</span>
                </div>
                <span class="block text-[9px] text-slate-500 mt-1">Computadores con software educativo configurado</span>
            </div>
        </div>
    </div>

    <!-- Dimensión 3: Ambiental y Cero Papel -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 space-y-4">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center space-x-2 border-b border-white/5 pb-3">
            <i class="fa-solid fa-leaf text-emerald-400"></i>
            <span>3. Dimensión Ambiental (Ley 1672)</span>
        </h4>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Residuos RAEE Canalizados</span>
                <span class="text-base font-bold text-emerald-400"><?= $stats['bajasRAEE'] ?> certificados</span>
                <span class="block text-[9px] text-slate-500">Gestores autorizados retoma</span>
            </div>
            <div class="p-3 bg-black/20 rounded-xl border border-white/5">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Ahorro de Papel (Cero Papel)</span>
                <span class="text-base font-bold text-white"><?= $stats['ahorroHojasPapel'] ?> Hojas</span>
                <span class="block text-[9px] text-slate-500">Actas digitales sin papel físico</span>
            </div>
            <div class="p-3 bg-black/20 rounded-xl border border-white/5 col-span-2">
                <span class="block text-xxs font-bold text-slate-400 uppercase">Salas con Riesgo de Infraestructura</span>
                <span class="text-base font-bold text-rose-400"><?= $stats['salasRiesgo'] ?> / <?= $stats['totalSalas'] ?> salas</span>
                <span class="block text-[9px] text-slate-500">Falta polo a tierra o estabilizador (Riesgo eléctrico)</span>
            </div>
        </div>
    </div>

    <!-- Dimensión 4: Social, Veeduría y Control Social -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 space-y-4 flex flex-col justify-between">
        <div>
            <h4 class="text-sm font-bold text-white uppercase tracking-wider flex items-center space-x-2 border-b border-white/5 pb-3">
                <i class="fa-solid fa-users text-violet-400"></i>
                <span>4. Dimensión Social y Transparencia</span>
            </h4>
            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                El sistema provee un portal abierto de consulta para que el **Contralor Escolar** y la comunidad educativa puedan auditar y vigilar en tiempo real el patrimonio tecnológico del colegio, promoviendo la veeduría y previniendo la pérdida hormiga de componentes internos.
            </p>
        </div>
        <div class="pt-4 flex flex-col sm:flex-row gap-3">
            <a href="<?= BASE_URL ?>/reportes/transparencia" class="flex-1 py-2.5 px-4 bg-violet-600/20 border border-violet-500/30 text-violet-300 font-semibold rounded-xl text-center hover:bg-violet-600/30 transition-all text-xs flex items-center justify-center space-x-2">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
                <span>Ver Veeduría Pública</span>
            </a>
        </div>
    </div>

</div>

<!-- SECCIÓN GRÁFICO DE ESTADOS Y ACCIONES RÁPIDAS -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Gráfico de Estado de Activos -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 lg:col-span-2">
        <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">
            Distribución Física y Operativa de Activos
        </h4>
        <div class="h-64 relative flex items-center justify-center">
            <canvas id="activeStatusChart" class="max-h-full"></canvas>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 flex flex-col justify-between">
        <div>
            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 border-b border-white/5 pb-3">
                Operaciones del Sistema
            </h4>
            <div class="space-y-3">
                <?php if (in_array($role, ['Rector', 'Almacenista'])): ?>
                    <a href="<?= BASE_URL ?>/computadores/crear" class="flex items-center space-x-3 p-3 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-violet-500/30 rounded-xl transition-all text-sm text-slate-300 hover:text-white">
                        <i class="fa-solid fa-plus-circle text-violet-400 text-lg"></i>
                        <span>Registrar Nuevo Computador</span>
                    </a>
                    <a href="<?= BASE_URL ?>/traslados/crear" class="flex items-center space-x-3 p-3 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-violet-500/30 rounded-xl transition-all text-sm text-slate-300 hover:text-white">
                        <i class="fa-solid fa-truck-ramp-box text-cyan-400 text-lg"></i>
                        <span>Solicitar Traslado de Equipos</span>
                    </a>
                    <a href="<?= BASE_URL ?>/bajas/crear" class="flex items-center space-x-3 p-3 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-violet-500/30 rounded-xl transition-all text-sm text-slate-300 hover:text-white">
                        <i class="fa-solid fa-circle-minus text-emerald-400 text-lg"></i>
                        <span>Dar de Baja / RAEE</span>
                    </a>
                <?php endif; ?>

                <?php if ($role === 'Docente'): ?>
                    <a href="<?= BASE_URL ?>/prestamos/crear" class="flex items-center space-x-3 p-3 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-violet-500/30 rounded-xl transition-all text-sm text-slate-300 hover:text-white">
                        <i class="fa-solid fa-calendar-plus text-cyan-400 text-lg"></i>
                        <span>Solicitar Préstamo de Computador</span>
                    </a>
                <?php endif; ?>
                
                <a href="<?= BASE_URL ?>/prestamos" class="flex items-center space-x-3 p-3 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-violet-500/30 rounded-xl transition-all text-sm text-slate-300 hover:text-white">
                    <i class="fa-solid fa-list-check text-slate-400 text-lg"></i>
                    <span>Ver Préstamos de Equipos</span>
                </a>
            </div>
        </div>
        <div class="text-[10px] text-slate-500 text-center mt-4">
            Último ingreso detectado desde IP: <?= $_SERVER['REMOTE_ADDR'] ?>
        </div>
    </div>

</div>

<!-- BITÁCORA DE SEGURIDAD ENCRIPTADA (Solo Rector y Almacenista) -->
<?php if (in_array($role, ['Rector', 'Almacenista'])): ?>
<div class="glass-panel rounded-2xl p-6 border border-white/5">
    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4 flex items-center justify-between border-b border-white/5 pb-3">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-user-shield text-violet-400"></i>
            <span>Log de Auditoría de Seguridad (Cifrado AES-256)</span>
        </div>
        <span class="text-xxs px-2.5 py-1 bg-violet-600/20 text-violet-400 rounded-full border border-violet-500/20 font-bold uppercase tracking-wider">AES-256 Activo</span>
    </h4>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-xs border-collapse">
            <thead>
                <tr class="border-b border-white/5 text-slate-400 font-bold uppercase tracking-wider">
                    <th class="py-3 px-2">Fecha/Hora</th>
                    <th class="py-3 px-2">Usuario</th>
                    <th class="py-3 px-2">Acción</th>
                    <th class="py-3 px-2">Tabla Afectada</th>
                    <th class="py-3 px-2">Dirección IP</th>
                    <th class="py-3 px-2">Detalles Desencriptados</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentLogs)): ?>
                    <tr>
                        <td colspan="6" class="py-4 text-center text-slate-500">No hay registros de auditoría aún.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentLogs as $log): ?>
                        <tr class="border-b border-white/5 hover:bg-white/[0.02] text-slate-300">
                            <td class="py-3 px-2 font-medium text-slate-400 whitespace-nowrap"><?= $log['fecha_evento'] ?></td>
                            <td class="py-3 px-2 whitespace-nowrap">
                                <span class="font-semibold text-white"><?= $log['nombres'] . ' ' . $log['apellidos'] ?></span>
                                <span class="block text-[10px] text-slate-500"><?= $log['email'] ?></span>
                            </td>
                            <td class="py-3 px-2 whitespace-nowrap">
                                <span class="px-2 py-0.5 rounded bg-slate-800 text-slate-300 font-semibold border border-white/5"><?= $log['accion_ejecutada'] ?></span>
                            </td>
                            <td class="py-3 px-2 whitespace-nowrap text-slate-500 font-mono"><?= $log['tabla_afectada'] ?: '-' ?></td>
                            <td class="py-3 px-2 text-slate-400 font-mono"><?= $log['direccion_ip'] ?></td>
                            <td class="py-3 px-2 text-slate-300">
                                <span class="text-slate-300 italic">"<?= htmlspecialchars($log['detalles_desencriptados']) ?>"</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Configuración de Script del Gráfico -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('activeStatusChart').getContext('2d');
        const data = {
            labels: ['Operativo', 'En Mantenimiento', 'Obsoleto', 'Dado de Baja'],
            datasets: [{
                data: [
                    <?= $chartData['Operativo'] ?>,
                    <?= $chartData['En Mantenimiento'] ?>,
                    <?= $chartData['Obsoleto'] ?>,
                    <?= $chartData['Dado de Baja'] ?>
                ],
                backgroundColor: [
                    'rgba(16, 185, 129, 0.45)', // Verde esmeralda translúcido
                    'rgba(245, 158, 11, 0.45)',  // Naranja cálido
                    'rgba(99, 102, 241, 0.45)',  // Violeta
                    'rgba(244, 63, 94, 0.45)'    // Rojo/rosa
                ],
                borderColor: [
                    '#10b981',
                    '#f59e0b',
                    '#6366f1',
                    '#f43f5e'
                ],
                borderWidth: 2,
                hoverOffset: 15
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            color: '#94a3b8',
                            font: {
                                family: 'Outfit',
                                size: 12
                            },
                            padding: 20
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
