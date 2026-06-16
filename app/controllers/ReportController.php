<?php
/**
 * Controlador de Reportes Analíticos y Portal de Transparencia
 */
class ReportController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Reporte Analítico Detallado (Rector y Contralor)
     */
    public function index() {
        Session::requireRole(['Rector', 'Contralor Escolar']);

        $db = Database::connect();

        // 1. Cálculos de la Dimensión Financiera
        $totalComputadores = $db->query("SELECT COUNT(*) FROM computadores")->fetchColumn();
        $valorHistoricoTotal = $db->query("SELECT SUM(valor_historico) FROM computadores")->fetchColumn() ?: 0;

        // Depreciación y Valor Neto en Libros consumiendo la vista `vista_depreciacion_fse`
        $depreciacionAnualTotal = 0;
        $valorNetoLibrosTotal = 0;
        try {
            $deprData = $db->query("SELECT SUM(depreciacion_anual_Da) as total_da, SUM(valor_neto_libros) as total_vnl FROM vista_depreciacion_fse")->fetch();
            $depreciacionAnualTotal = $deprData['total_da'] ?: 0;
            $valorNetoLibrosTotal = $deprData['total_vnl'] ?: 0;
        } catch (Exception $e) {
            // Ignorar si falla la vista
        }

        // TCO Promedio por equipo
        $tco = 0;
        if ($totalComputadores > 0) {
            $costoCompra = $valorHistoricoTotal;
            $costoLicenciamiento = $valorHistoricoTotal * 0.05;
            $costoMantenimiento = $valorHistoricoTotal * 0.15;
            $tco = ($costoCompra + $costoLicenciamiento + $costoMantenimiento) / $totalComputadores;
        }

        // Índice de Obsolescencia Financiera = (Depreciación Acumulada / Valor Adquisición) * 100
        $depreciacionAcumulada = $valorHistoricoTotal - $valorNetoLibrosTotal;
        $obsolescenciaFinanciera = 0;
        if ($valorHistoricoTotal > 0) {
            $obsolescenciaFinanciera = ($depreciacionAcumulada / $valorHistoricoTotal) * 100;
        }

        // Pérdidas Siniestradas
        $perdidaSiniestros = $db->query("
            SELECT SUM(c.valor_historico) 
            FROM computadores c
            JOIN prestamos p ON c.id_computador = p.id_computador
            WHERE p.estado_prestamo = 'Siniestro'
        ")->fetchColumn() ?: 0;

        // Porcentaje de Inversión Tecnológica Excedente
        // Simulación: Inversión en mantenimiento (15% del FSE) / Ingresos Operacionales (FSE) * 100
        // Asumiendo un ingreso operativo base del FSE de $50,000,000 COP para simulación
        $ingresosFSE = 50000000;
        $excedenteInversion = (($valorHistoricoTotal * 0.12) / $ingresosFSE) * 100;

        // 2. Cálculos de la Dimensión Educativa
        $docentesTIC = $db->query("SELECT COUNT(*) FROM usuarios WHERE capacitacion_tic_aprobada = 1 AND id_rol = 3")->fetchColumn() ?: 0;
        $totalDocentes = $db->query("SELECT COUNT(*) FROM usuarios WHERE id_rol = 3")->fetchColumn() ?: 1;
        $tasaIdoneidadDocente = ($docentesTIC / $totalDocentes) * 100;

        $prestamosActivos = $db->query("SELECT COUNT(*) FROM prestamos WHERE estado_prestamo = 'Activo'")->fetchColumn() ?: 0;
        $tam = 65.0 + min(35.0, $prestamosActivos * 5); // TAM de Aula Móvil

        $coberturaSoftware = 0;
        if ($totalComputadores > 0) {
            $compConSoft = $db->query("SELECT COUNT(*) FROM computadores WHERE licenciamiento IS NOT NULL AND licenciamiento != '' AND estado_activo != 'Obsoleto'")->fetchColumn() ?: 0;
            $coberturaSoftware = ($compConSoft / $totalComputadores) * 100;
        }

        // Indice de Incidencias Operativas por Docente
        // Reportes de daño / Clases Programadas
        // Simulación: 2 daños reportados / 40 clases programadas = 0.05 (5%)
        $incidenciasDocente = 5.0;

        $this->view('reportes/index', [
            'title' => 'Reportes y Analítica',
            'stats' => [
                'totalComputadores' => $totalComputadores,
                'valorHistoricoTotal' => $valorHistoricoTotal,
                'depreciacionAnualTotal' => $depreciacionAnualTotal,
                'valorNetoLibrosTotal' => $valorNetoLibrosTotal,
                'tco' => $tco,
                'obsolescenciaFinanciera' => $obsolescenciaFinanciera,
                'perdidaSiniestros' => $perdidaSiniestros,
                'excedenteInversion' => $excedenteInversion,
                'tasaIdoneidadDocente' => $tasaIdoneidadDocente,
                'tam' => $tam,
                'coberturaSoftware' => $coberturaSoftware,
                'incidenciasDocente' => $incidenciasDocente
            ]
        ]);
    }

    /**
     * Portal Público de Transparencia (Veeduría Ciudadana / Contraloría Escolar)
     */
    public function transparencia() {
        $db = Database::connect();

        // Obtener distribución operativa
        $totalEquipos = $db->query("SELECT COUNT(*) FROM computadores")->fetchColumn() ?: 0;
        $operativos = $db->query("SELECT COUNT(*) FROM computadores WHERE estado_activo = 'Operativo'")->fetchColumn() ?: 0;
        $mantenimiento = $db->query("SELECT COUNT(*) FROM computadores WHERE estado_activo = 'En Mantenimiento'")->fetchColumn() ?: 0;
        $obsoletos = $db->query("SELECT COUNT(*) FROM computadores WHERE estado_activo = 'Obsoleto'")->fetchColumn() ?: 0;
        $deBaja = $db->query("SELECT COUNT(*) FROM computadores WHERE estado_activo = 'Dado de Baja'")->fetchColumn() ?: 0;

        // Distribución por Salas
        $salasQuery = $db->query("
            SELECT s.nombre_sala, COUNT(c.id_computador) as cantidad 
            FROM salas s
            LEFT JOIN computadores c ON s.id_sala = c.id_sala_actual
            GROUP BY s.id_sala
        ")->fetchAll();

        // Bajas RAEE
        $bajasQuery = $db->query("
            SELECT b.*, c.modelo, m.nombre_marca
            FROM bajas_raee b
            JOIN computadores c ON b.id_computador = c.id_computador
            JOIN marcas m ON c.id_marca = m.id_marca
            ORDER BY b.fecha_baja DESC
        ")->fetchAll();

        $this->view('reportes/transparencia', [
            'title' => 'Portal de Transparencia Pública',
            'counts' => [
                'total' => $totalEquipos,
                'operativos' => $operativos,
                'mantenimiento' => $mantenimiento,
                'obsoletos' => $obsoletos,
                'deBaja' => $deBaja
            ],
            'salas' => $salasQuery,
            'bajas' => $bajasQuery
        ]);
    }
}
