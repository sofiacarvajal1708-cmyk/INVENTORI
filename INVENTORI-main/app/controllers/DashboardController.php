<?php
/**
 * Controlador del Panel de Control (Dashboard)
 */
class DashboardController extends Controller {

    /**
     * Muestra el panel principal con los indicadores clave
     */
    public function index() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }

        $db = Database::connect();

        // --- 1. INDICADORES GENERALES DE EQUIPOS ---
        $totalComputadores = $db->query("SELECT COUNT(*) FROM computadores")->fetchColumn() ?: 0;
        $totalSalas = $db->query("SELECT COUNT(*) FROM salas")->fetchColumn() ?: 0;
        $totalPrestamos = $db->query("SELECT COUNT(*) FROM prestamos")->fetchColumn() ?: 0;
        $totalTraslados = $db->query("SELECT COUNT(*) FROM traslados")->fetchColumn() ?: 0;
        $prestamosActivos = $db->query("SELECT COUNT(*) FROM prestamos WHERE estado_prestamo = 'Activo'")->fetchColumn() ?: 0;
        $totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn() ?: 0;

        // --- 2. DIMENSIÓN EDUCATIVA Y TIC ---
        $docentesTIC = $db->query("SELECT COUNT(*) FROM usuarios WHERE capacitacion_tic_aprobada = 1 AND id_rol = 3")->fetchColumn() ?: 0;
        $totalDocentes = $db->query("SELECT COUNT(*) FROM usuarios WHERE id_rol = 3")->fetchColumn() ?: 1;
        $tasaIdoneidadDocente = ($docentesTIC / $totalDocentes) * 100;

        // TAM (Tasa de Aprovechamiento del Aula)
        $tam = 65.0 + min(35.0, $prestamosActivos * 5);

        // Cobertura de Software Pedagógico Instalado
        $coberturaSoftware = 0;
        if ($totalComputadores > 0) {
            $compConSoft = $db->query("SELECT COUNT(*) FROM computadores WHERE licenciamiento IS NOT NULL AND licenciamiento != '' AND estado_activo != 'Obsoleto'")->fetchColumn() ?: 0;
            $coberturaSoftware = ($compConSoft / $totalComputadores) * 100;
        }

        // Tasa de Operatividad Técnica = (Equipos Operativos / Total) * 100
        $tasaOperatividad = 0;
        if ($totalComputadores > 0) {
            $compOperativos = $db->query("SELECT COUNT(*) FROM computadores WHERE estado_activo = 'Operativo'")->fetchColumn() ?: 0;
            $tasaOperatividad = ($compOperativos / $totalComputadores) * 100;
        }

        // --- 3. DIMENSIÓN AMBIENTAL Y CERO PAPEL ---
        $bajasRAEE = $db->query("SELECT COUNT(*) FROM bajas_raee")->fetchColumn() ?: 0;
        $salasRiesgo = $db->query("SELECT COUNT(*) FROM salas WHERE tiene_polo_a_tierra = 0 OR tiene_estabilizador = 0")->fetchColumn() ?: 0;
        $ahorroHojasPapel = ($totalPrestamos + $totalTraslados) * 2;

        // --- 4. DATOS PARA GRÁFICOS (ESTADO DE ACTIVOS) ---
        $statusCounts = $db->query("
            SELECT estado_activo, COUNT(*) as qty 
            FROM computadores 
            GROUP BY estado_activo
        ")->fetchAll();

        $chartData = [
            'Operativo' => 0,
            'En Mantenimiento' => 0,
            'Obsoleto' => 0,
            'Dado de Baja' => 0
        ];
        foreach ($statusCounts as $sc) {
            $chartData[$sc['estado_activo']] = (int)$sc['qty'];
        }

        // --- 5. LOGS DE SEGURIDAD RECIENTES (Auditoría Encriptada) ---
        $logModel = $this->model('Log');
        $recentLogs = $logModel->getRecientes(6);

        // Renderizar vista del Dashboard
        $this->view('dashboard/index', [
            'title' => 'Panel de Control',
            'stats' => [
                'totalComputadores'    => $totalComputadores,
                'prestamosActivos'     => $prestamosActivos,
                'totalPrestamos'       => $totalPrestamos,
                'totalTraslados'       => $totalTraslados,
                'tasaOperatividad'     => $tasaOperatividad,
                'tasaIdoneidadDocente' => $tasaIdoneidadDocente,
                'tam'                  => $tam,
                'coberturaSoftware'    => $coberturaSoftware,
                'bajasRAEE'            => $bajasRAEE,
                'salasRiesgo'          => $salasRiesgo,
                'totalSalas'           => $totalSalas,
                'totalUsuarios'        => $totalUsuarios,
                'ahorroHojasPapel'     => $ahorroHojasPapel
            ],
            'chartData' => $chartData,
            'recentLogs' => $recentLogs
        ]);
    }
}
