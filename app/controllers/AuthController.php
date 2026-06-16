<?php
/**
 * Controlador de Autenticación y Cierre de Sesión
 */
class AuthController extends Controller {

    /**
     * Acción por defecto: redirige a la acción de login
     */
    public function index() {
        $this->login();
    }

    /**
     * Muestra el login y procesa la autenticación
     */
    public function login() {
        // Redirigir al dashboard si ya está autenticado
        if (Session::isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $error = null;
        $success = null;

        // Comprobar si proviene de una expiración de sesión
        if (isset($_GET['timeout'])) {
            $error = 'Tu sesión ha expirado por inactividad. Por favor, ingresa de nuevo.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitizar inputs
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Por favor complete todos los campos.';
            } else {
                $userModel = $this->model('User');
                $user = $userModel->getByEmail($email);

                if ($user && password_verify($password, $user['password_hash'])) {
                    // Cargar sesión con datos del usuario
                    Session::set('user_id', $user['id_usuario']);
                    Session::set('user_name', $user['nombres'] . ' ' . $user['apellidos']);
                    Session::set('user_email', $user['email']);
                    Session::set('role_name', $user['role_name']);
                    Session::set('last_activity', time());

                    // Log de auditoría encriptado
                    $logModel = $this->model('Log');
                    $logModel->write(
                        $user['id_usuario'],
                        'Inicio de Sesión',
                        'usuarios',
                        $user['id_usuario'],
                        "Sesión iniciada exitosamente por el usuario {$user['nombres']} {$user['apellidos']} con rol {$user['role_name']}."
                    );

                    $this->redirect('dashboard');
                } else {
                    $error = 'El correo electrónico o la contraseña son incorrectos.';
                }
            }
        }

        $this->view('auth/login', [
            'error' => $error,
            'success' => $success,
            'title' => 'Inicio de Sesión Premium'
        ]);
    }

    /**
     * Cierra la sesión activa
     */
    public function logout() {
        if (Session::isLoggedIn()) {
            $userId = Session::get('user_id');
            $userName = Session::get('user_name');
            
            // Log de auditoría antes de destruir sesión
            $logModel = $this->model('Log');
            $logModel->write(
                $userId,
                'Cierre de Sesión',
                'usuarios',
                $userId,
                "El usuario {$userName} cerró su sesión de forma manual."
            );
        }

        Session::destroy();
        $this->redirect('auth/login');
    }
}
