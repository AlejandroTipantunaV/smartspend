<?php
/**
 * Modelo Transaction — consultas a la tabla transacciones.
 */
require_once __DIR__ . '/../config/database.php';

class Transaction
{
    private PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::getInstance();
    }

    /**
     * Lista transacciones del usuario (JOIN categorias).
     * Filtro opcional por tipo: ingreso | gasto.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getByUserId(int $idUsuario, ?string $tipo = null): array
    {
        $sql = 'SELECT t.id_transaccion,
                       t.id_usuario,
                       t.id_categoria,
                       t.tipo,
                       t.monto,
                       t.concepto,
                       t.fecha_transaccion,
                       t.fecha_registro,
                       c.nombre_categoria
                FROM transacciones t
                INNER JOIN categorias c ON c.id_categoria = t.id_categoria
                WHERE t.id_usuario = :id_usuario';

        $params = ['id_usuario' => $idUsuario];

        if ($tipo !== null && in_array($tipo, ['ingreso', 'gasto'], true)) {
            $sql .= ' AND t.tipo = :tipo';
            $params['tipo'] = $tipo;
        }

        $sql .= ' ORDER BY t.fecha_transaccion DESC, t.id_transaccion DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    /**
     * Obtiene una transacción por ID si pertenece al usuario.
     *
     * @return array<string, mixed>|null
     */
    public function getById(int $id, int $idUsuario): ?array
    {
        $sql = 'SELECT t.id_transaccion,
                       t.id_usuario,
                       t.id_categoria,
                       t.tipo,
                       t.monto,
                       t.concepto,
                       t.fecha_transaccion,
                       c.nombre_categoria
                FROM transacciones t
                INNER JOIN categorias c ON c.id_categoria = t.id_categoria
                WHERE t.id_transaccion = :id
                  AND t.id_usuario = :id_usuario
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario,
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    /**
     * @param array{
     *   id_usuario:int,
     *   id_categoria:int,
     *   tipo:string,
     *   monto:float|string,
     *   concepto:string,
     *   fecha_transaccion:string
     * } $datos
     */
    public function create(array $datos): bool
    {
        $sql = 'INSERT INTO transacciones
                    (id_usuario, id_categoria, tipo, monto, concepto, fecha_transaccion)
                VALUES
                    (:id_usuario, :id_categoria, :tipo, :monto, :concepto, :fecha_transaccion)';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id_usuario' => $datos['id_usuario'],
            'id_categoria' => $datos['id_categoria'],
            'tipo' => $datos['tipo'],
            'monto' => $datos['monto'],
            'concepto' => $datos['concepto'],
            'fecha_transaccion' => $datos['fecha_transaccion'],
        ]);
    }

    /**
     * @param array{
     *   id_categoria:int,
     *   tipo:string,
     *   monto:float|string,
     *   concepto:string,
     *   fecha_transaccion:string,
     *   id_usuario:int
     * } $datos
     */
    public function update(int $id, array $datos): bool
    {
        $sql = 'UPDATE transacciones
                SET id_categoria = :id_categoria,
                    tipo = :tipo,
                    monto = :monto,
                    concepto = :concepto,
                    fecha_transaccion = :fecha_transaccion
                WHERE id_transaccion = :id
                  AND id_usuario = :id_usuario';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id_categoria' => $datos['id_categoria'],
            'tipo' => $datos['tipo'],
            'monto' => $datos['monto'],
            'concepto' => $datos['concepto'],
            'fecha_transaccion' => $datos['fecha_transaccion'],
            'id' => $id,
            'id_usuario' => $datos['id_usuario'],
        ]);
    }

    public function delete(int $id, int $idUsuario): bool
    {
        $sql = 'DELETE FROM transacciones
                WHERE id_transaccion = :id
                  AND id_usuario = :id_usuario';

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'id_usuario' => $idUsuario,
        ]);
    }
}
