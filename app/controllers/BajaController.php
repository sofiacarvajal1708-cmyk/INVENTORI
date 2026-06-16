<?php
/**
 * Controlador de Bajas Ambientales RAEE
 */
class BajaController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
        Session::requireRole(['Rector', 'Almacenista']);
    }

    /**
     * Listado de decommissions y bajas
     */
    public function index() {
        $bajaModel = $this->model('Baja');
        $bajas = $bajaModel->getAll();

        $this->view('bajas/index', [
            'title' => 'Gestión de Bajas RAEE',
            'bajas' => $bajas
        ]);
    }

    /**
     * Crear una baja RAEE
     */
    public function crear() {
        $bajaModel = $this->model('Baja');
        $compModel = $this->model('Computador');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);

            if (empty($data['id_computador']) || empty($data['numero_acta_consejo']) || empty($data['numero_certificado_raee'])) {
                $_SESSION['flash_error'] = 'Por favor complete todos los campos obligatorios.';
            } else {
                if ($bajaModel->create($data)) {
                    // Log de auditoría
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Registrar Baja RAEE',
                        'bajas_raee',
                        $data['id_computador'],
                        "Se dio de baja ambiental el computador ID {$data['id_computador']} mediante Acta {$data['numero_acta_consejo']} y Certificado RAEE {$data['numero_certificado_raee']}."
                    );
                    $_SESSION['flash_success'] = 'Baja ambiental y acta del Consejo registradas con éxito.';
                    $this->redirect('bajas');
                } else {
                    $_SESSION['flash_error'] = 'Error al registrar la baja RAEE.';
                }
            }
        }

        // Obtener computadores que no estén ya dados de baja
        $computadoresAll = $compModel->getAll();
        $computadores = [];
        foreach ($computadoresAll as $c) {
            if ($c['estado_activo'] !== 'Dado de Baja') {
                $computadores[] = $c;
            }
        }

        $this->view('bajas/create', [
            'title' => 'Registrar Baja RAEE',
            'computadores' => $computadores
        ]);
    }
}
