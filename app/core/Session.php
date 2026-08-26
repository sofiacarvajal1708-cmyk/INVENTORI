<?php
/**
 * Gestión de Sesiones Seguras y Expiración por Inactividad
 */
class Session {
    
    // Inicializar sesión y comprobar inactividad
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            // Aumentar la seguridad de la cookie de sesión
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', 1);
            }
            session_start();
        }

        // Verificar inactividad
        if (self::isLoggedIn()) {
            if (isset($_SESSION['last_activity'])) {
                $elapsedTime = time() - $_SESSION['last_activity'];
                if ($elapsedTime > SESSION_TIMEOUT) {
                    self::destroy();
                    // Redirigir a login con alerta de tiempo agotado
                    header("Location: " . BASE_URL . "/auth/login?timeout=1");
                    exit();
                }
            }
            // Actualizar la última actividad
            $_SESSION['last_activity'] = time();
        }
    }

    // Establecer un valor de sesión
    public static function set($key, $value) {
        $_SESSION[$key] = $value;
    }

    // Obtener un valor de sesión
    public static function get($key) {
        $val = $_SESSION[$key] ?? null;
        // Normalización automática para roles (evita problemas con sesiones activas previas o alias)
        if ($key === 'role_name' && is_string($val)) {
            if (stripos($val, 'admin') !== false) return 'Administrador';
            if (stripos($val, 'rector') !== false) return 'Rector';
            if (stripos($val, 'almacen') !== false) return 'Almacenista';
            if (stripos($val, 'contralor') !== false) return 'Contralor';
            if (stripos($val, 'docente') !== false) return 'Docente';
        }
        return $val;
    }

    // Verificar si existe una clave de sesión
    public static function has($key) {
        return isset($_SESSION[$key]);
    }

    // Eliminar una clave de sesión
    public static function remove($key) {
        if (self::has($key)) {
            unset($_SESSION[$key]);
        }
    }

    // Verificar si el usuario ha iniciado sesión
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Destruir sesión por completo
    public static function destroy() {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 42000,
                    $params["path"],
                    $params["domain"],
                    $params["secure"],
                    $params["httponly"]
                );
            }
            session_destroy();
        }
    }

    // Verificar accesos basados en roles
    public static function requireRole($allowedRoles = []) {
        if (!self::isLoggedIn()) {
            header("Location: " . BASE_URL . "/auth/login");
            exit();
        }

        $userRole = self::get('role_name');
        
        // Comprobar coincidencia exacta o normalizada
        $hasAccess = in_array($userRole, $allowedRoles);
        if (!$hasAccess) {
            foreach ($allowedRoles as $role) {
                if (stripos($userRole, $role) !== false || stripos($role, $userRole) !== false) {
                    $hasAccess = true;
                    break;
                }
            }
        }

        if (!$hasAccess) {
            // Si el rol no está permitido, redirigir al dashboard con error de denegado
            header("Location: " . BASE_URL . "/dashboard?forbidden=1");
            exit();
        }
    }
}
