<?php
/**
 * Configuración General del Sistema
 */

// Evitar acceso directo
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(dirname(__FILE__)));
}

// Configuración de URL Base Dinámica
$protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
// Remueve public/index.php para obtener la raíz del sitio
$baseDir = str_replace('/public/index.php', '', $scriptName);
define('BASE_URL', $protocol . '://' . $host . $baseDir);

// Nombre de la Aplicación
define('APP_NAME', 'IEBSM - Gestión de Activos');

// Clave de encriptación AES-256 (32 bytes hexadecimal)
define('AES_KEY', 'IEBSM_Secure_Encryption_Key_2026_#$!');

// Límite de inactividad de sesión (en segundos) - 15 Minutos = 900 Segundos
define('SESSION_TIMEOUT', 900);
