<?php
/**
 * Controlador de Computadores y Fichas Técnicas de Componentes
 */
class ComputadorController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
        Session::requireRole(['Rector', 'Almacenista', 'Contralor', 'Docente', 'Administrador']);
    }

    /**
     * Listado principal de equipos con filtros (Sede, Sala, Laboratorio, Estado)
     */
    public function index() {
        $compModel = $this->model('Computador');
        $salaModel = $this->model('Sala');

        $filters = [
            'sala' => $_GET['sala'] ?? '',
            'sede' => $_GET['sede'] ?? '',
            'laboratorio' => $_GET['laboratorio'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        $computadores = $compModel->getAll($filters);
        $salas = $salaModel->getAll();

        $this->view('computadores/index', [
            'title' => 'Inventario de Equipos de Cómputo',
            'computadores' => $computadores,
            'salas' => $salas,
            'filters' => $filters
        ]);
    }

    /**
     * Generar vista imprimible / PDF de inventario de equipos
     */
    public function imprimir() {
        $compModel = $this->model('Computador');
        $salaModel = $this->model('Sala');

        $filters = [
            'sala' => $_GET['sala'] ?? '',
            'sede' => $_GET['sede'] ?? '',
            'laboratorio' => $_GET['laboratorio'] ?? '',
            'estado' => $_GET['estado'] ?? ''
        ];

        $computadores = $compModel->getAll($filters);
        $salas = $salaModel->getAll();

        $this->view('computadores/imprimir', [
            'title' => 'Reporte Oficial de Inventario de Equipos',
            'computadores' => $computadores,
            'salas' => $salas,
            'filters' => $filters
        ]);
    }

    /**
     * Formulario y proceso de registro de nuevo equipo
     */
    public function crear() {
        Session::requireRole(['Rector', 'Almacenista', 'Administrador']);
        
        $compModel = $this->model('Computador');
        $salaModel = $this->model('Sala');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);

            // Validaciones básicas
            if (empty($data['numero_serial']) || empty($data['id_marca']) || empty($data['id_sala_actual'])) {
                $_SESSION['flash_error'] = 'Por favor complete todos los campos obligatorios.';
            } else {
                $newId = $compModel->create($data);
                if ($newId) {
                    // Log de auditoría
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Crear Computador',
                        'computadores',
                        $newId,
                        "Se registró un equipo modelo {$data['modelo']} con Serial {$data['numero_serial']} y Placa SED {$data['placa_sed']}."
                    );
                    $_SESSION['flash_success'] = 'Equipo registrado exitosamente.';
                    $this->redirect('computadores');
                } else {
                    $_SESSION['flash_error'] = 'Error al registrar el equipo.';
                }
            }
        }

        $marcas = $compModel->getBrands();
        $salas = $salaModel->getAll();

        $this->view('computadores/form', [
            'title' => 'Nuevo Equipo',
            'marcas' => $marcas,
            'salas' => $salas,
            'isEdit' => false
        ]);
    }

    /**
     * Formulario y proceso de edición de equipo
     */
    public function editar($id) {
        Session::requireRole(['Rector', 'Almacenista', 'Administrador']);
        $id = (int)$id;

        $compModel = $this->model('Computador');
        $salaModel = $this->model('Sala');

        $computador = $compModel->getById($id);
        if (!$computador) {
            $_SESSION['flash_error'] = 'Computador no encontrado.';
            $this->redirect('computadores');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = Security::sanitize($_POST);

            if (empty($data['numero_serial']) || empty($data['id_marca']) || empty($data['id_sala_actual'])) {
                $_SESSION['flash_error'] = 'Por favor complete todos los campos obligatorios.';
            } else {
                if ($compModel->update($id, $data)) {
                    // Log
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Editar Computador',
                        'computadores',
                        $id,
                        "El administrador actualizó los datos del computador Serial: {$data['numero_serial']}."
                    );
                    $_SESSION['flash_success'] = 'Computador actualizado exitosamente.';
                    $this->redirect('computadores');
                } else {
                    $_SESSION['flash_error'] = 'Error al actualizar el computador.';
                }
            }
        }

        $marcas = $compModel->getBrands();
        $salas = $salaModel->getAll();

        $this->view('computadores/form', [
            'title' => 'Editar Computador',
            'computador' => $computador,
            'marcas' => $marcas,
            'salas' => $salas,
            'isEdit' => true
        ]);
    }

    /**
     * Ficha técnica detallada (Hoja de Vida) y gestión de piezas internas (Pérdida hormiga)
     */
    public function ficha($id) {
        $id = (int)$id;
        $compModel = $this->model('Computador');
        
        $computador = $compModel->getById($id);
        if (!$computador) {
            $_SESSION['flash_error'] = 'Computador no encontrado.';
            $this->redirect('computadores');
        }

        // --- Gestión de Componentes Internos (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && in_array(Session::get('role_name'), ['Rector', 'Almacenista'])) {
            $data = Security::sanitize($_POST);

            // 1. Agregar nuevo componente
            if (isset($data['action']) && $data['action'] === 'add_component') {
                if (empty($data['serial_componente']) || empty($data['tipo_componente'])) {
                    $_SESSION['flash_error'] = 'El tipo y serial de componente son obligatorios.';
                } else {
                    $data['id_computador'] = $id;
                    if ($compModel->addComponent($data)) {
                        // Log
                        $logModel = $this->model('Log');
                        $logModel->write(
                            Session::get('user_id'),
                            'Agregar Componente',
                            'componentes_internos',
                            $id,
                            "Se instaló un nuevo componente {$data['tipo_componente']} con Serial: {$data['serial_componente']} en el Computador ID: {$id}."
                        );
                        $_SESSION['flash_success'] = 'Componente instalado exitosamente.';
                    } else {
                        $_SESSION['flash_error'] = 'Error al registrar el componente.';
                    }
                }
            }

            // 2. Modificar estado de componente (Instalado / Removido / Falla)
            if (isset($data['action']) && $data['action'] === 'update_status') {
                $componentId = (int)$data['id_componente'];
                $status = $data['estado_componente'];
                if ($compModel->updateComponentStatus($componentId, $status)) {
                    // Log
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Modificar Componente',
                        'componentes_internos',
                        $componentId,
                        "Se modificó el estado del componente ID {$componentId} a: {$status}."
                    );
                    $_SESSION['flash_success'] = 'Estado del componente actualizado.';
                }
            }
            // Redirigir para refrescar y evitar reenvío de formulario
            $this->redirect('computadores/ficha/' . $id);
        }

        $componentes = $compModel->getComponents($id);

        $this->view('computadores/ficha', [
            'title' => 'Ficha Técnica de Activo',
            'computador' => $computador,
            'componentes' => $componentes
        ]);
    }
}
