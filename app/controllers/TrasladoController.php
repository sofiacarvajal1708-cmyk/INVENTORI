<?php
/**
 * Controlador para la Consola Transaccional de Traslados de Equipos
 */
class TrasladoController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
        // Consulta para Rector, Almacenista y Administrador
        Session::requireRole(['Rector', 'Almacenista', 'Administrador']);
    }

    /**
     * Listado principal e historial de traslados
     */
    public function index() {
        $trasladoModel = $this->model('Traslado');
        $traslados = $trasladoModel->getAll();

        $this->view('traslados/index', [
            'title' => 'Consola de Traslados',
            'traslados' => $traslados
        ]);
    }

    /**
     * Solicitar un traslado de sala (SOLO ADMINISTRADOR)
     */
    public function crear() {
        Session::requireRole(['Administrador']);

        $trasladoModel = $this->model('Traslado');
        $compModel = $this->model('Computador');
        $salaModel = $this->model('Sala');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);
            $data['id_usuario_solicita'] = Session::get('user_id');

            if (empty($data['id_computador']) || empty($data['id_sala_destino'])) {
                $_SESSION['flash_error'] = 'Por favor complete todos los campos obligatorios.';
            } else {
                if ($trasladoModel->create($data)) {
                    // Log de auditoría
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Solicitar Traslado',
                        'traslados',
                        $data['id_computador'],
                        "El administrador solicitó el traslado del computador ID {$data['id_computador']} hacia la sala ID {$data['id_sala_destino']}."
                    );
                    $_SESSION['flash_success'] = 'Solicitud de traslado registrada. Pendiente de aprobación.';
                    $this->redirect('traslados');
                } else {
                    $_SESSION['flash_error'] = 'Error al registrar la solicitud de traslado.';
                }
            }
        }

        // Obtener computadores operativos
        $computadores = $compModel->getAll(['estado' => 'Operativo']);
        $salas = $salaModel->getAll();

        $this->view('traslados/create', [
            'title' => 'Solicitar Traslado',
            'computadores' => $computadores,
            'salas' => $salas
        ]);
    }

    /**
     * Procesar Autorización (Aprobar / Rechazar) (SOLO ADMINISTRADOR)
     */
    public function autorizar($id, $action) {
        Session::requireRole(['Administrador']);
        $id = (int)$id;
        $action = Security::sanitize($action); // 'aprobar' o 'rechazar'

        if (!in_array($action, ['aprobar', 'rechazar'])) {
            $_SESSION['flash_error'] = 'Acción de autorización inválida.';
            $this->redirect('traslados');
        }

        $trasladoModel = $this->model('Traslado');
        $traslado = $trasladoModel->getById($id);

        if (!$traslado) {
            $_SESSION['flash_error'] = 'Registro de traslado no encontrado.';
            $this->redirect('traslados');
        }

        if ($trasladoModel->authorize($id, Session::get('user_id'), $action)) {
            // Log de auditoría
            $logModel = $this->model('Log');
            $logModel->write(
                Session::get('user_id'),
                'Autorizar Traslado',
                'traslados',
                $id,
                "Se procesó la solicitud de traslado ID {$id} con resultado: {$action}."
            );
            $_SESSION['flash_success'] = "El traslado ha sido " . ($action === 'aprobar' ? 'aprobado y aplicado físicamente' : 'rechazado') . " con éxito.";
        } else {
            $_SESSION['flash_error'] = 'Error al procesar la autorización del traslado.';
        }

        $this->redirect('traslados');
    }
}
