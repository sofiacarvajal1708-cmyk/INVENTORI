<?php
/**
 * Front Controller - Punto de Entrada Único del Sistema
 */

// Definir constante raíz de la aplicación para prevenir accesos directos a otros scripts
define('APP_ROOT', dirname(__DIR__) . '/app');

// Cargar archivos de configuración
require_once APP_ROOT . '/config/Config.php';
require_once APP_ROOT . '/config/Database.php';

// Cargar clases del núcleo (Core)
require_once APP_ROOT . '/core/App.php';
require_once APP_ROOT . '/core/Controller.php';
require_once APP_ROOT . '/core/Model.php';
require_once APP_ROOT . '/core/Session.php';
require_once APP_ROOT . '/core/Security.php';

// Inicializar gestión de sesión segura e inactividad
Session::init();

// Arrancar el despachador de rutas (Router)
$app = new App();
