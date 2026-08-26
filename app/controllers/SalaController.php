<?php
/**
 * Controlador de Salas de Cómputo e Infraestructura
 */
class SalaController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
        // Consulta permitida para todos los roles autorizados
        Session::requireRole(['Rector', 'Almacenista', 'Contralor', 'Docente', 'Administrador']);
    }

    /**
     * Listado principal de salas
     */
    public function index() {
        $salaModel = $this->model('Sala');
        $salas = $salaModel->getAll();

        $this->view('salas/index', [
            'title' => 'Gestión de Salas y Puestos',
            'salas' => $salas
        ]);
    }

    /**
     * Crear una nueva sala (SOLO ADMINISTRADOR)
     */
    public function crear() {
        Session::requireRole(['Administrador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);
            
            if (empty($data['nombre_sala'])) {
                $_SESSION['flash_error'] = 'El nombre de la sala es obligatorio.';
            } else {
                $salaModel = $this->model('Sala');
                if ($salaModel->create($data)) {
                    // Log
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Crear Sala',
                        'salas',
                        null,
                        "El administrador registró la nueva sala: {$data['nombre_sala']}"
                    );
                    $_SESSION['flash_success'] = 'Sala registrada exitosamente.';
                } else {
                    $_SESSION['flash_error'] = 'Error al registrar la sala.';
                }
            }
        }
        $this->redirect('salas');
    }

    /**
     * Editar infraestructura de una sala (SOLO ADMINISTRADOR)
     */
    public function editar($id) {
        Session::requireRole(['Administrador']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);
            $id = (int)$id;

            if (empty($data['nombre_sala'])) {
                $_SESSION['flash_error'] = 'El nombre de la sala es obligatorio.';
            } else {
                $salaModel = $this->model('Sala');
                if ($salaModel->update($id, $data)) {
                    // Log
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Editar Sala',
                        'salas',
                        $id,
                        "Se actualizó la infraestructura de la sala ID {$id}: {$data['nombre_sala']}"
                    );
                    $_SESSION['flash_success'] = 'Infraestructura de la sala actualizada.';
                } else {
                    $_SESSION['flash_error'] = 'Error al actualizar la sala.';
                }
            }
        }
        $this->redirect('salas');
    }
}
