<?php
/**
 * Modelo Category — consultas a la tabla categorias.
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
        $sql = 'SELECT id_categoria, nombre_categoria, tipo
                FROM categorias
                ORDER BY tipo ASC, nombre_categoria ASC';

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getByTipo(string $tipo): array
    {
        $sql = 'SELECT id_categoria, nombre_categoria, tipo
                FROM categorias
                WHERE tipo = :tipo
                ORDER BY nombre_categoria ASC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tipo' => $tipo]);

        return $stmt->fetchAll();
    }

    /**
     * Comprueba si una categoría existe y coincide con el tipo.
     */
    public function belongsToTipo(int $idCategoria, string $tipo): bool
    {
        $sql = 'SELECT 1
                FROM categorias
                WHERE id_categoria = :id_categoria
                  AND tipo = :tipo
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id_categoria' => $idCategoria,
            'tipo' => $tipo,
        ]);

        return (bool) $stmt->fetchColumn();
    }
}
