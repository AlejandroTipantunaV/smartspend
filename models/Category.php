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
    public function getAll(): array
    {
        $sql = 'SELECT id_categoria, nombre_categoria, tipo, descripcion, icono, color, estado, fecha
                FROM categorias
                ORDER BY tipo ASC, nombre_categoria ASC';

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getByType(string $type): array
    {
        $sql = 'SELECT id_categoria, nombre_categoria, tipo, descripcion, icono, color, estado, fecha
                FROM categorias
                WHERE tipo = :tipo AND estado = 1
                ORDER BY nombre_categoria ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tipo' => $type]);

        return $stmt->fetchAll();
    }

    /**
     * Check if a category exists and matches the given type.
     */
    public function belongsToType(int $categoryId, string $type): bool
    {
        $sql = 'SELECT 1
                FROM categorias
                WHERE id_categoria = :id_categoria
                  AND tipo = :tipo
                  AND estado = 1
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_categoria' => $categoryId,
            'tipo' => $type,
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Get a category by its ID.
     */
    public function getById(int $id): ?array
    {
        $sql = 'SELECT id_categoria, nombre_categoria, tipo, descripcion, icono, color, estado, fecha
                FROM categorias
                WHERE id_categoria = :id';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function create(array $data): bool
    {
        $sql = 'INSERT INTO categorias (nombre_categoria, tipo, descripcion, icono, color, estado)
                VALUES (:nombre_categoria, :tipo, :descripcion, :icono, :color, :estado)';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre_categoria' => $data['nombre_categoria'],
            'tipo'             => $data['tipo'],
            'descripcion'      => $data['descripcion'] ?? null,
            'icono'            => $data['icono'] ?? null,
            'color'            => $data['color'] ?? null,
            'estado'           => $data['estado'] ?? 1,
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $sql = 'UPDATE categorias
                SET nombre_categoria = :nombre_categoria,
                    tipo = :tipo,
                    descripcion = :descripcion,
                    icono = :icono,
                    color = :color,
                    estado = :estado
                WHERE id_categoria = :id';

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre_categoria' => $data['nombre_categoria'],
            'tipo'             => $data['tipo'],
            'descripcion'      => $data['descripcion'] ?? null,
            'icono'            => $data['icono'] ?? null,
            'color'            => $data['color'] ?? null,
            'estado'           => $data['estado'] ?? 1,
            'id'               => $id,
        ]);
    }

    public function isUsed(int $id): bool {
        $q = $this->db->prepare('SELECT 1 FROM transacciones WHERE id_categoria = ? LIMIT 1');
        $q->execute([$id]); return (bool)$q->fetchColumn();
    }
    public function delete(int $id): bool
    {
        $sql = 'DELETE FROM categorias WHERE id_categoria = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
