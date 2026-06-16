<?php
/**
 * Controlador de API REST
 */
class ApiController extends Controller {

    /**
     * Endpoint: GET /api/traslados
     * Retorna el listado de traslados en formato JSON
     */
    public function traslados() {
        // Verificar autenticación básica o de sesión activa
        if (!Session::isLoggedIn()) {
            return $this->json(['error' => 'No autorizado. Inicie sesión.'], 401);
        }

        $trasladoModel = $this->model('Traslado');
        $traslados = $trasladoModel->getAll();

        return $this->json([
            'status' => 'success',
            'data' => $traslados
        ]);
    }

    /**
     * Endpoint: GET /api/computadores
     * Retorna los computadores en formato JSON
     */
    public function computadores() {
        if (!Session::isLoggedIn()) {
            return $this->json(['error' => 'No autorizado.'], 401);
        }

        $compModel = $this->model('Computador');
        $computadores = $compModel->getAll();

        return $this->json([
            'status' => 'success',
            'data' => $computadores
        ]);
    }
}
