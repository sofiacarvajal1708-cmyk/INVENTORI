<?php
/**
 * Controlador para la Consola de Préstamos y Devoluciones
 */
class PrestamoController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
    }

    /**
     * Listado e historial de préstamos
     */
    public function index() {
        $prestamoModel = $this->model('Prestamo');
        $prestamos = $prestamoModel->getAll();

        $this->view('prestamos/index', [
            'title' => 'Préstamos y Devoluciones',
            'prestamos' => $prestamos
        ]);
    }

    /**
     * Crear solicitud de préstamo
     */
    public function crear() {
        $prestamoModel = $this->model('Prestamo');
        $compModel = $this->model('Computador');
        $userModel = $this->model('User');

        $role = Session::get('role_name');
        $userId = Session::get('user_id');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);

            // Determinar ID del docente a prestar
            $docenteId = ($role === 'Docente') ? $userId : (int)$data['id_docente'];

            // 1. Validar Requisitos TIC del Docente (Educativo)
            if (!$prestamoModel->checkTeacherRequisites($docenteId)) {
                $docenteObj = $userModel->getById($docenteId);
                $_SESSION['flash_error'] = "El docente {$docenteObj['nombres']} no cumple con los requisitos del Programa Nacional TIC (Requiere Capacitación Aprobada y mínimo 40 horas de asistencia).";
                $this->redirect('prestamos/crear');
            }

            // 2. Validar que tenga plan pedagógico escrito
            if (empty($data['plan_pedagogico']) || strlen($data['plan_pedagogico']) < 15) {
                $_SESSION['flash_error'] = 'Debe detallar un Plan o Experiencia Pedagógica válida para el aula (mínimo 15 caracteres).';
                $this->redirect('prestamos/crear');
            }

            // Registrar Préstamo
            $data['id_docente'] = $docenteId;
            if ($prestamoModel->create($data)) {
                // Log de auditoría encriptado
                $logModel = $this->model('Log');
                $logModel->write(
                    $userId,
                    'Registrar Préstamo',
                    'prestamos',
                    $data['id_computador'],
                    "Se autorizó el préstamo del computador ID {$data['id_computador']} al docente ID {$docenteId}. Plan pedagógico registrado: {$data['plan_pedagogico']}."
                );

                $_SESSION['flash_success'] = 'Préstamo registrado exitosamente y firmado digitalmente bajo sesión segura.';
                $this->redirect('prestamos');
            } else {
                $_SESSION['flash_error'] = 'Error al registrar el préstamo.';
            }
        }

        // Obtener computadores operativos
        $computadoresAll = $compModel->getAll(['estado' => 'Operativo']);
        
        // Filtrar computadores que no estén prestados actualmente
        $db = Database::connect();
        $prestados = $db->query("SELECT id_computador FROM prestamos WHERE estado_prestamo = 'Activo'")->fetchAll(PDO::FETCH_COLUMN) ?: [];
        
        $computadores = [];
        foreach ($computadoresAll as $c) {
            if (!in_array($c['id_computador'], $prestados)) {
                $computadores[] = $c;
            }
        }

        // Listado de docentes para el Almacenista/Rector
        $docentes = $userModel->getDocentes();

        $this->view('prestamos/create', [
            'title' => 'Solicitud de Préstamo',
            'computadores' => $computadores,
            'docentes' => $docentes,
            'role' => $role
        ]);
    }

    /**
     * Procesar Devolución (Retorno)
     */
    public function devolver($id) {
        // Retorno solo realizado por Almacenista o Rector (auxiliar de retorno)
        Session::requireRole(['Rector', 'Almacenista']);
        $id = (int)$id;

        $prestamoModel = $this->model('Prestamo');
        $prestamo = $prestamoModel->getById($id);

        if (!$prestamo) {
            $_SESSION['flash_error'] = 'Registro de préstamo no encontrado.';
            $this->redirect('prestamos');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);
            $data['id_auxiliar_retorno'] = Session::get('user_id');

            // Lógica para registrar devolución o siniestro
            if ($prestamoModel->returnLoan($id, $data)) {
                // Log de auditoría
                $logModel = $this->model('Log');
                $logModel->write(
                    Session::get('user_id'),
                    'Registrar Retorno',
                    'prestamos',
                    $id,
                    "Se procesó el retorno del préstamo ID {$id}. Estado reportado: {$data['estado_prestamo']}. Observaciones: {$data['observaciones_retorno']}."
                );

                $_SESSION['flash_success'] = 'Devolución de equipo procesada correctamente.';
                $this->redirect('prestamos');
            } else {
                $_SESSION['flash_error'] = 'Error al registrar el retorno.';
            }
        }

        $this->view('prestamos/retorno', [
            'title' => 'Procesar Retorno de Equipo',
            'prestamo' => $prestamo
        ]);
    }
}
