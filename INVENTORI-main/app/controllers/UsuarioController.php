<?php
/**
 * Controlador de Gestión de Usuarios y Contraseñas
 * Acceso EXCLUSIVO para el rol Administrador
 */
class UsuarioController extends Controller {

    public function __construct() {
        if (!Session::isLoggedIn()) {
            $this->redirect('auth/login');
        }
        // Exclusivo para Administrador
        Session::requireRole(['Administrador']);
    }

    /**
     * Listado principal de usuarios
     */
    public function index() {
        $userModel = $this->model('User');

        $filters = [
            'rol' => $_GET['rol'] ?? '',
            'estado' => $_GET['estado'] ?? '',
            'search' => $_GET['search'] ?? ''
        ];

        $usuarios = $userModel->getAll($filters);
        $roles = $userModel->getRoles();

        $this->view('usuarios/index', [
            'title' => 'Gestión de Usuarios',
            'usuarios' => $usuarios,
            'roles' => $roles,
            'filters' => $filters
        ]);
    }

    /**
     * Crear nuevo usuario
     */
    public function create() {
        $userModel = $this->model('User');
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipoDoc = trim($_POST['tipo_documento'] ?? 'C.C.');
            $doc = trim($_POST['documento_identidad'] ?? '');
            $nombres = trim($_POST['nombres'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            $idRol = (int)($_POST['id_rol'] ?? 0);
            $estado = $_POST['estado_usuario'] ?? 'Activo';
            $tic = isset($_POST['capacitacion_tic_aprobada']) ? 1 : 0;
            $horas = (int)($_POST['horas_asistencia_tic'] ?? 0);

            if (empty($doc) || empty($nombres) || empty($apellidos) || empty($email) || empty($password) || empty($idRol)) {
                $error = 'Por favor complete todos los campos obligatorios (*).';
            } elseif ($password !== $passwordConfirm) {
                $error = 'Las contraseñas no coinciden.';
            } elseif (strlen($password) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } else {
                // Comprobar si email ya existe
                $existing = $userModel->getByEmail($email);
                if ($existing) {
                    $error = 'El correo electrónico ya se encuentra registrado.';
                } else {
                    $userId = $userModel->create([
                        'tipo_documento' => $tipoDoc,
                        'documento_identidad' => $doc,
                        'nombres' => $nombres,
                        'apellidos' => $apellidos,
                        'email' => $email,
                        'password' => $password,
                        'id_rol' => $idRol,
                        'estado_usuario' => $estado,
                        'capacitacion_tic_aprobada' => $tic,
                        'horas_asistencia_tic' => $horas
                    ]);

                    // Log de auditoría
                    $logModel = $this->model('Log');
                    $logModel->write(
                        Session::get('user_id'),
                        'Creación de Usuario',
                        'usuarios',
                        $userId,
                        "El administrador creó el usuario {$nombres} {$apellidos} ({$email}) con rol ID {$idRol}."
                    );

                    $_SESSION['flash_success'] = "Usuario '{$nombres} {$apellidos}' registrado exitosamente.";
                    $this->redirect('usuarios');
                }
            }
        }

        $roles = $userModel->getRoles();
        $this->view('usuarios/form', [
            'title' => 'Registrar Nuevo Usuario',
            'roles' => $roles,
            'user' => $_POST ?? [],
            'error' => $error,
            'isEdit' => false
        ]);
    }

    /**
     * Editar datos de usuario
     */
    public function edit($id) {
        $userModel = $this->model('User');
        $user = $userModel->getById($id);

        if (!$user) {
            $this->redirect('usuarios');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipoDoc = trim($_POST['tipo_documento'] ?? 'C.C.');
            $doc = trim($_POST['documento_identidad'] ?? '');
            $nombres = trim($_POST['nombres'] ?? '');
            $apellidos = trim($_POST['apellidos'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $idRol = (int)($_POST['id_rol'] ?? 0);
            $estado = $_POST['estado_usuario'] ?? 'Activo';
            $tic = isset($_POST['capacitacion_tic_aprobada']) ? 1 : 0;
            $horas = (int)($_POST['horas_asistencia_tic'] ?? 0);

            if (empty($doc) || empty($nombres) || empty($apellidos) || empty($email) || empty($idRol)) {
                $error = 'Por favor complete todos los campos obligatorios (*).';
            } else {
                $userModel->update($id, [
                    'tipo_documento' => $tipoDoc,
                    'documento_identidad' => $doc,
                    'nombres' => $nombres,
                    'apellidos' => $apellidos,
                    'email' => $email,
                    'id_rol' => $idRol,
                    'estado_usuario' => $estado,
                    'capacitacion_tic_aprobada' => $tic,
                    'horas_asistencia_tic' => $horas
                ]);

                // Log de auditoría
                $logModel = $this->model('Log');
                $logModel->write(
                    Session::get('user_id'),
                    'Modificación de Usuario',
                    'usuarios',
                    $id,
                    "El administrador actualizó los datos del usuario ID {$id} ({$nombres} {$apellidos})."
                );

                $_SESSION['flash_success'] = "Usuario '{$nombres} {$apellidos}' actualizado exitosamente.";
                $this->redirect('usuarios');
            }
        }

        $roles = $userModel->getRoles();
        $this->view('usuarios/form', [
            'title' => 'Editar Usuario',
            'roles' => $roles,
            'user' => $user,
            'error' => $error,
            'isEdit' => true
        ]);
    }

    /**
     * Gestión exclusiva de Contraseñas
     */
    public function password($id) {
        $userModel = $this->model('User');
        $user = $userModel->getById($id);

        if (!$user) {
            $this->redirect('usuarios');
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($newPassword) || empty($confirmPassword)) {
                $error = 'Por favor complete todos los campos.';
            } elseif ($newPassword !== $confirmPassword) {
                $error = 'Las contraseñas no coinciden.';
            } elseif (strlen($newPassword) < 6) {
                $error = 'La nueva contraseña debe tener al menos 6 caracteres.';
            } else {
                $userModel->updatePassword($id, $newPassword);

                // Log de auditoría
                $logModel = $this->model('Log');
                $logModel->write(
                    Session::get('user_id'),
                    'Cambio de Contraseña',
                    'usuarios',
                    $id,
                    "El administrador restableció la contraseña del usuario {$user['nombres']} {$user['apellidos']} (ID {$id})."
                );

                $_SESSION['flash_success'] = "Contraseña actualizada exitosamente para el usuario {$user['nombres']} {$user['apellidos']}.";
                $this->redirect('usuarios');
            }
        }

        $this->view('usuarios/password', [
            'title' => 'Cambiar Contraseña de Usuario',
            'user' => $user,
            'error' => $error
        ]);
    }

    /**
     * Alternar estado Activo / Inactivo
     */
    public function toggleStatus($id) {
        if ($id == Session::get('user_id')) {
            $_SESSION['flash_error'] = 'No puedes desactivar tu propia cuenta de administrador.';
            $this->redirect('usuarios');
        }

        $userModel = $this->model('User');
        $userModel->toggleStatus($id);

        $_SESSION['flash_success'] = 'Estado del usuario modificado correctamente.';
        $this->redirect('usuarios');
    }

    /**
     * Eliminar usuario
     */
    public function delete($id) {
        if ($id == Session::get('user_id')) {
            $_SESSION['flash_error'] = 'No puedes eliminar tu propia cuenta de administrador.';
            $this->redirect('usuarios');
        }

        $userModel = $this->model('User');
        $user = $userModel->getById($id);

        if ($user) {
            $userModel->delete($id);

            // Log de auditoría
            $logModel = $this->model('Log');
            $logModel->write(
                Session::get('user_id'),
                'Eliminación de Usuario',
                'usuarios',
                $id,
                "El administrador eliminó al usuario {$user['nombres']} {$user['apellidos']} (ID {$id})."
            );

            $_SESSION['flash_success'] = "Usuario '{$user['nombres']} {$user['apellidos']}' eliminado permanentemente.";
        }

        $this->redirect('usuarios');
    }
}
