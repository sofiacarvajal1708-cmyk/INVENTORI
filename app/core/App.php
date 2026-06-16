<?php
/**
 * Enrutador principal de la aplicación (Front Controller router)
 */
class App {
    protected $controller = 'AuthController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseUrl();

        // Si el usuario ya está autenticado y no hay ruta específica, ir a Dashboard
        if (empty($url)) {
            if (Session::isLoggedIn()) {
                $this->controller = 'DashboardController';
                $this->method = 'index';
            }
        }

        // Determinar controlador
        if (isset($url[0])) {
            $controllerKey = strtolower($url[0]);
            
            // Mapa para traducir rutas plurales a nombres de controladores singulares/específicos
            $routeMap = [
                'computadores' => 'ComputadorController',
                'salas'        => 'SalaController',
                'prestamos'    => 'PrestamoController',
                'traslados'    => 'TrasladoController',
                'bajas'        => 'BajaController',
                'reportes'     => 'ReportController'
            ];
            
            if (array_key_exists($controllerKey, $routeMap)) {
                $controllerName = $routeMap[$controllerKey];
            } else {
                $controllerName = ucfirst($url[0]) . 'Controller';
            }

            if (file_exists(APP_ROOT . '/controllers/' . $controllerName . '.php')) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                // Si el controlador no existe, redirigir a un error 404
                $this->controller = 'AuthController';
                $this->method = 'notFound';
            }
        }

        require_once APP_ROOT . '/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Determinar método
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            } else {
                $this->method = 'notFound';
            }
        }

        // Determinar parámetros restantes
        $this->params = $url ? array_values($url) : [];

        // Ejecutar controlador, método y parámetros
        try {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } catch (Exception $e) {
            die("Error al procesar la solicitud: " . $e->getMessage());
        }
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
