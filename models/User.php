<?php
require_once __DIR__ . '/../config/database.php';
final class User {
    private PDO $db;
    public function __construct() { $this->db = Database::getInstance(); }
    public function byEmail(string $email): ?array {
        $q=$this->db->prepare('SELECT * FROM usuarios WHERE correo = ?');
        $q->execute([$email]); return $q->fetch() ?: null;
    }
    public function byId(int $id): ?array {
        $q=$this->db->prepare('SELECT id_usuario,nombre,correo,fecha_registro FROM usuarios WHERE id_usuario = ?');
        $q->execute([$id]); return $q->fetch() ?: null;
    }
    public function create(string $name,string $email,string $password): int {
        $q=$this->db->prepare('INSERT INTO usuarios (nombre,correo,password) VALUES (?,?,?)');
        $q->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT)]);
        return (int)$this->db->lastInsertId();
    }
}
