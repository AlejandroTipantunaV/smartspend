<?php
// config/database.php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Detectar si el servidor es local o en la nube (InfinityFree)
        $isLocal = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1');

        if ($isLocal) {
            // Configuración Local (XAMPP)
            $host = 'localhost';
            $db_name = 'smartspend';
            $username = 'root';
            $password = '';
        } else {
            // Configuración Servidor Remoto (InfinityFree)
            $host = 'sql208.infinityfree.com'; // Servidor remoto
            $db_name = 'if0_42973541_smartspend'; // Nombre exacto en tu InfinityFree
            $username = 'if0_42973541'; // Tu usuario de InfinityFree
            $password = 'rSyxqnG2DjsSVc'; // Tu contraseña de BD
        }

        try {
            $dsn = "mysql:host={$host};dbname={$db_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            
            $this->conn = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Error en la conexión con la base de datos: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>