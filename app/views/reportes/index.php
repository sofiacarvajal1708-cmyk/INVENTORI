<?php
require_once APP_ROOT . '/views/layout/header.php';
?>

<div class="space-y-8">
    
    <!-- Encabezado -->
    <div>
        <h2 class="text-xl font-bold text-white">Analítica y Control Contable-Financiero</h2>
        <p class="text-xs text-slate-400 mt-1">Monitoreo de inversiones, TCO, depreciación acumulada e indicadores del Fondo de Servicios Educativos (FSE).</p>
    </div>

    <!-- DIMENSIÓN FINANCIERA & FÓRMULAS DE CÁLCULO -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Fórmulas LaTeX y Explicación -->
        <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 lg:col-span-2 space-y-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-square-root-variable text-violet-400"></i>
                <span>Metodologías de Cálculo Financiero (FSE)</span>
            </h3>

            <!-- Ecuación de Depreciación -->
            <div class="space-y-2">
                <span class="text-xs font-bold text-slate-300 block">1. Fórmula de Depreciación Lineal Anual ($D_a$):</span>
                <div class="p-4 bg-black/35 rounded-xl border border-white/5 font-mono text-center text-sm text-violet-400">
                    Da = (Vh - Vs) / uv
                </div>
                <div class="text-[10px] text-slate-400 space-y-1 mt-1 leading-relaxed pl-2 border-l border-white/10">
                    <p>• **$V_h$ (Valor Histórico):** Costo original de adquisición soportado en factura o acta.</p>
                    <p>• **$V_s$ (Valor de Salvamento):** Valor residual al finalizar su ciclo de uso (Asumido en $0$ en la vista MySQL).</p>
                    <p>• **$u_v$ (Vida Útil):** Años parametrizados de vida útil para equipos de cómputo (Rango técnico: 5 a 15 años).</p>
                </div>
            </div>

            <!-- Ecuación de TCO -->
            <div class="space-y-2 mt-4">
                <span class="text-xs font-bold text-slate-300 block">2. Ecuación del Costo Total de Propiedad (TCO):</span>
                <div class="p-4 bg-black/35 rounded-xl border border-white/5 font-mono text-center text-sm text-cyan-400">
                    TCO = (Costo Compra + Licenciamiento + Mantenimiento) / Total Equipos Activos
                </div>
                <div class="text-[10px] text-slate-400 space-y-1 mt-1 leading-relaxed pl-2 border-l border-white/10">
                    <p>• **Licenciamiento:** Estimado contable del 5% del valor histórico total de software educativo.</p>
                    <p>• **Mantenimiento:** Costos operativos preventivos y de infraestructura estimados en 15% del valor.</p>
                </div>
            </div>
        </div>

        <!-- Resultados Financieros FSE -->
        <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 space-y-6">
            <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
                <i class="fa-solid fa-coins text-cyan-400"></i>
                <span>Indicadores del Balance FSE</span>
            </h3>
            
            <div class="space-y-4 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400">Valor de Activos (PPE):</span>
                    <span class="font-bold text-white">$<?= number_format($stats['valorHistoricoTotal'], 2) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Depreciación Anual ($D_a$):</span>
                    <span class="font-bold text-violet-400">$<?= number_format($stats['depreciacionAnualTotal'], 2) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Valor Neto Actual en Libros:</span>
                    <span class="font-bold text-white">$<?= number_format($stats['valorNetoLibrosTotal'], 2) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Índice de Obsolescencia:</span>
                    <span class="font-bold text-yellow-500"><?= number_format($stats['obsolescenciaFinanciera'], 1) ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Pérdida por Siniestros:</span>
                    <span class="font-bold text-rose-500">$<?= number_format($stats['perdidaSiniestros'], 2) ?></span>
                </div>
                
                <div class="border-t border-white/5 pt-3">
                    <div class="p-3 bg-black/35 rounded-xl border border-white/5 text-[10px] space-y-1">
                        <span class="block text-slate-400 uppercase font-semibold">TCO Promedio por Activo:</span>
                        <span class="text-base font-extrabold text-white">$<?= number_format($stats['tco'], 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- DIMENSIÓN EDUCATIVA & GESTIÓN TIC -->
    <div class="glass-panel rounded-2xl p-6 border border-white/5 bg-black/10 space-y-6">
        <h3 class="text-sm font-bold text-white uppercase tracking-wider border-b border-white/5 pb-3 flex items-center space-x-2">
            <i class="fa-solid fa-display text-cyan-400"></i>
            <span>Indicadores de la Dimensión Educativa</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Tasa de Aprovechamiento TAM -->
            <div class="p-4 bg-black/25 rounded-2xl border border-white/5 space-y-2">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aprovechamiento Aula Móvil (TAM)</span>
                <div class="text-2xl font-extrabold text-white"><?= number_format($stats['tam'], 1) ?>%</div>
                <p class="text-[10px] text-slate-500 leading-relaxed">
                    Mide el uso real semanal del hardware en actividades curriculares. Meta institucional: >75%.
                </p>
                <div class="w-full bg-slate-800 rounded-full h-1.5 mt-2">
                    <div class="bg-violet-500 h-1.5 rounded-full" style="width: <?= $stats['tam'] ?>%"></div>
                </div>
            </div>

            <!-- Tasa de Idoneidad de Hardware -->
            <div class="p-4 bg-black/25 rounded-2xl border border-white/5 space-y-2">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tasa de Idoneidad Docente</span>
                <div class="text-2xl font-extrabold text-cyan-400"><?= number_format($stats['tasaIdoneidadDocente'], 1) ?>%</div>
                <p class="text-[10px] text-slate-500 leading-relaxed">
                    Porcentaje de profesores autorizados y certificados en competencias pedagógicas TIC.
                </p>
                <div class="w-full bg-slate-800 rounded-full h-1.5 mt-2">
                    <div class="bg-cyan-500 h-1.5 rounded-full" style="width: <?= $stats['tasaIdoneidadDocente'] ?>%"></div>
                </div>
            </div>

            <!-- Cobertura de Software Pedagógico -->
            <div class="p-4 bg-black/25 rounded-2xl border border-white/5 space-y-2">
                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Cobertura de Software</span>
                <div class="text-2xl font-extrabold text-emerald-400"><?= number_format($stats['coberturaSoftware'], 1) ?>%</div>
                <p class="text-[10px] text-slate-500 leading-relaxed">
                    Computadores operativos con suite de software educativo oficial instalada y activa.
                </p>
                <div class="w-full bg-slate-800 rounded-full h-1.5 mt-2">
                    <div class="bg-emerald-500 h-1.5 rounded-full" style="width: <?= $stats['coberturaSoftware'] ?>%"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
require_once APP_ROOT . '/views/layout/footer.php';
?>
