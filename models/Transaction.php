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

    /**
     * Dashboard: Get total balance, total income, and total expenses.
     */
    public function getBalanceStats(int $idUsuario): array
    {
        $sql = "SELECT 
                    SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END) as total_income,
                    SUM(CASE WHEN tipo = 'gasto' THEN monto ELSE 0 END) as total_expenses
                FROM transacciones 
                WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);
        $row = $stmt->fetch();

        $income = (float)($row['total_income'] ?? 0);
        $expenses = (float)($row['total_expenses'] ?? 0);

        return [
            'total_income' => $income,
            'total_expenses' => $expenses,
            'total_balance' => $income - $expenses
        ];
    }

    /**
     * Dashboard: Get distribution of transactions by category for a given type.
     */
    public function getCategoryDistribution(int $idUsuario, string $tipo): array
    {
        $sql = "SELECT 
                    c.nombre_categoria,
                    c.icono,
                    SUM(t.monto) as total_amount
                FROM transacciones t
                JOIN categorias c ON t.id_categoria = c.id_categoria
                WHERE t.id_usuario = :id_usuario AND t.tipo = :tipo
                GROUP BY t.id_categoria, c.nombre_categoria, c.icono
                ORDER BY total_amount DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario, 'tipo' => $tipo]);
        
        $results = $stmt->fetchAll();
        $totalSum = array_sum(array_column($results, 'total_amount'));

        $distribution = [];
        foreach ($results as $row) {
            $amount = (float)$row['total_amount'];
            $percentage = $totalSum > 0 ? ($amount / $totalSum) * 100 : 0;
            $distribution[] = [
                'category_name' => $row['nombre_categoria'],
                'icon' => $row['icono'] ?? 'lucide:tag',
                'amount' => $amount,
                'percentage' => round($percentage, 2)
            ];
        }

        return $distribution;
    }

    /**
     * Dashboard: Get the number of unique categories used by the user.
     */
    public function getUsedCategoriesCount(int $idUsuario): int
    {
        $sql = "SELECT COUNT(DISTINCT id_categoria) as cat_count 
                FROM transacciones 
                WHERE id_usuario = :id_usuario";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario]);
        $row = $stmt->fetch();
        return (int)($row['cat_count'] ?? 0);
    }

    /**
     * Dashboard: Get daily trend data (amount per day per category)
     */
    public function getDailyTrend(int $idUsuario, string $tipo): array
    {
        $sql = "SELECT 
                    DATE(t.fecha_transaccion) as fecha,
                    c.nombre_categoria,
                    SUM(t.monto) as total_amount
                FROM transacciones t
                JOIN categorias c ON t.id_categoria = c.id_categoria
                WHERE t.id_usuario = :id_usuario AND t.tipo = :tipo
                GROUP BY DATE(t.fecha_transaccion), t.id_categoria, c.nombre_categoria
                ORDER BY fecha ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_usuario' => $idUsuario, 'tipo' => $tipo]);
        
        $results = $stmt->fetchAll();
        
        // Formatear para Highcharts: agrupar por categoría
        $series = [];
        $dates = [];
        
        foreach ($results as $row) {
            $catName = $row['nombre_categoria'];
            $date = $row['fecha'];
            $amount = (float)$row['total_amount'];
            
            if (!isset($series[$catName])) {
                $series[$catName] = [];
            }
            $series[$catName][$date] = $amount;
            $dates[$date] = true;
        }
        
        // Obtener todas las fechas únicas ordenadas
        $sortedDates = array_keys($dates);
        sort($sortedDates);
        
        $finalSeries = [];
        foreach ($series as $catName => $dataByDate) {
            $data = [];
            foreach ($sortedDates as $date) {
                $data[] = $dataByDate[$date] ?? 0;
            }
            $finalSeries[] = [
                'name' => $catName,
                'data' => $data
            ];
        }
        
        return [
            'categories' => $sortedDates,
            'series' => $finalSeries
        ];
    }
}
