<?php
/**
 * Clase base para todos los controladores
 */
class Controller {
    
    // Carga de modelos
    public function model($model) {
        $modelPath = APP_ROOT . '/models/' . $model . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Error crítico: El modelo '{$model}' no existe.");
        }
    }

    // Carga de vistas con paso de datos
    public function view($view, $data = []) {
        $viewPath = APP_ROOT . '/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            // Extrae variables de $data para que estén disponibles en el archivo de la vista
            extract($data);
            require_once $viewPath;
        } else {
            die("Error crítico: La vista '{$view}' no existe.");
        }
    }

    // Redirección simple
    public function redirect($path) {
        header("Location: " . BASE_URL . "/" . ltrim($path, '/'));
        exit();
    }

    // Enviar respuesta JSON (para llamadas AJAX y API REST)
    public function json($data, $statusCode = 200) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($statusCode);
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    // Controlador genérico para 404 Not Found
    public function notFound() {
        http_response_code(404);
        $this->view('errors/404');
    }
}
