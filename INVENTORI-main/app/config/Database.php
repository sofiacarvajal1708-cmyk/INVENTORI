<?php
/**
 * Conector de Base de Datos (Singleton PDO)
 */
class Database {
    private static $host = 'localhost';
    private static $db_name = 'inventario_santa_margarita';
    private static $username = 'root';
    private static $password = ''; // Configuración por defecto de Laragon
    private static $conn = null;

    public static function connect() {
        if (self::$conn === null) {
            try {
                self::$conn = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$db_name . ";charset=utf8mb4",
                    self::$username,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
                    ]
                );
            } catch (PDOException $e) {
                // Mensaje elegante de error de conexión
                die("Error crítico de base de datos: No se pudo establecer conexión con el servidor MySQL local. Detalle: " . $e->getMessage());
            }
        }
        return self::$conn;
    }
}
