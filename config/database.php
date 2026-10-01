<?php
<<<<<<< HEAD
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
            
=======
/** Singleton PDO connection manager. Credentials loaded from env variables — never hardcoded. */

class Database {

    /** @var Database|null Singleton instance */
    private static $instance = null;

    /** @var \PDO Active PDO connection */
    private $conn;

    /** Opens a PDO connection using env variables. Use getInstance() instead. */
    private function __construct() {
        // Load .env file FIRST (system env vars already set by Docker take precedence)
        $this->loadEnv(__DIR__ . '/../.env');

        // Resolve connection parameters — getenv() reads from system env (Docker) or .env
        $host     = getenv('DB_HOST') ?: '127.0.0.1';
        $port     = getenv('DB_PORT') ?: '3306';
        $db_name  = getenv('DB_NAME') ?: 'smartspend';
        $username = getenv('DB_USER') ?: 'root';
        // DB_PASS can be an empty string (valid) — use !== false to distinguish "not set"
        $password = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,   // Throw on SQL errors
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,         // Associative arrays by default
                PDO::ATTR_EMULATE_PREPARES   => false,                    // Use native prepared statements
            ];
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
            $this->conn = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Error en la conexión con la base de datos: " . $e->getMessage());
        }
    }

<<<<<<< HEAD
    public static function getInstance() {
=======
    /** Parses .env and registers variables. Does NOT overwrite vars already set by the system (e.g., Docker). */
    private function loadEnv(string $filePath): void {
        if (!file_exists($filePath)) {
            return; // .env is optional; fall back to system env or defaults
        }

        foreach (file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);

            // Skip comments and malformed lines
            if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name  = trim($name);
            $value = trim($value, " \t\"'");  // Strip surrounding whitespace and quotes

            // Only register if not already defined by the system environment (e.g., Docker)
            if (getenv($name) === false) {
                putenv("{$name}={$value}");
                $_ENV[$name]    = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    /** Returns the shared PDO connection (Singleton pattern). */
    public static function getInstance(): \PDO {
>>>>>>> c749ca896438d59128bb5756d8d02b60d84adb63
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>