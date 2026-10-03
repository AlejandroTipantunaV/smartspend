<?php
/**
 * Category model — database queries for categories table.
 */
require_once __DIR__ . '/../config/database.php';

class Category
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getInstance();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(int $idUsuario): array
    {
        $sql = 'SELECT id_categoria, id_usuario, nombre_categoria, tipo, descripcion, icono, color, estado, fecha
                FROM categorias
                WHERE id_usuario = :id_usuario
                ORDER BY tipo ASC, nombre_categoria ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);
        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getByType(int $idUsuario, string $type): array
    {
        $sql = 'SELECT id_categoria, id_usuario, nombre_categoria, tipo, descripcion, icono, color, estado, fecha
                FROM categorias
                WHERE tipo = :tipo AND id_usuario = :id_usuario AND estado = 1
                ORDER BY nombre_categoria ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tipo' => $type, 'id_usuario' => $idUsuario]);

        return $stmt->fetchAll();
    }

    /**
     * Check if a category exists, belongs to the user, and matches the given type.
     */
    public function belongsToType(int $categoryId, string $type, int $idUsuario): bool
    {
        $sql = 'SELECT 1
                FROM categorias
                WHERE id_categoria = :id_categoria
                  AND id_usuario = :id_usuario
                  AND tipo = :tipo
                  AND estado = 1
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_categoria' => $categoryId,
            'id_usuario' => $idUsuario,
            'tipo' => $type,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Get a category by its ID and User ID.
     */
    public function getById(int $id, int $idUsuario): ?array
    {
        $sql = 'SELECT id_categoria, id_usuario, nombre_categoria, tipo, descripcion, icono, color, estado, fecha
                FROM categorias
                WHERE id_categoria = :id AND id_usuario = :id_usuario';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id, 'id_usuario' => $idUsuario]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function create(array $data, int $idUsuario): bool
    {
        $sql = 'INSERT INTO categorias (id_usuario, nombre_categoria, tipo, descripcion, icono, color, estado)
                VALUES (:id_usuario, :nombre_categoria, :tipo, :descripcion, :icono, :color, :estado)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_usuario'       => $idUsuario,
            'nombre_categoria' => $data['nombre_categoria'],
            'tipo'             => $data['tipo'],
            'descripcion'      => $data['descripcion'] ?? null,
            'icono'            => $data['icono'] ?? null,
            'color'            => $data['color'] ?? null,
            'estado'           => $data['estado'] ?? 1,
        ]);
    }

    public function update(int $id, array $data, int $idUsuario): bool
    {
        $sql = 'UPDATE categorias
                SET nombre_categoria = :nombre_categoria,
                    tipo = :tipo,
                    descripcion = :descripcion,
                    icono = :icono,
                    color = :color,
                    estado = :estado
                WHERE id_categoria = :id AND id_usuario = :id_usuario';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre_categoria' => $data['nombre_categoria'],
            'tipo'             => $data['tipo'],
            'descripcion'      => $data['descripcion'] ?? null,
            'icono'            => $data['icono'] ?? null,
            'color'            => $data['color'] ?? null,
            'estado'           => $data['estado'] ?? 1,
            'id'               => $id,
            'id_usuario'       => $idUsuario,
        ]);
    }

    public function isUsed(int $id, int $idUsuario): bool {
        $q = $this->db->prepare('SELECT 1 FROM transacciones WHERE id_categoria = ? AND id_usuario = ? LIMIT 1');
        $q->execute([$id, $idUsuario]); return (bool)$q->fetchColumn();
    }
    public function deactivate(int $id, int $idUsuario): bool
    {
        $sql = 'UPDATE categorias SET estado = 0 WHERE id_categoria = :id AND id_usuario = :id_usuario';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'id_usuario' => $idUsuario]);
    }

    public function activate(int $id, int $idUsuario): bool
    {
        $sql = 'UPDATE categorias SET estado = 1 WHERE id_categoria = :id AND id_usuario = :id_usuario';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id, 'id_usuario' => $idUsuario]);
    }
}
