<?php
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
            
            $this->conn = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            die("Error en la conexión con la base de datos: " . $e->getMessage());
        }
    }

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
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}
?>