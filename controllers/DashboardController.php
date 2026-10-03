<?php
declare(strict_types=1);

final class DashboardController
{
    public function data(?int $authenticatedUserId = null, ?PDO $connection = null): array
    {
        $data = [
            'status' => 'unavailable',
            'message' => 'La autenticación todavía no está integrada. Inicia sesión cuando esté disponible para consultar tus movimientos.',
            'income' => null, 'expenses' => null, 'balance' => null,
            'count' => 0, 'movements' => [],
        ];

        if ($authenticatedUserId === null || $authenticatedUserId <= 0) {
            return $data;
        }

        try {
            if ($connection === null) {
                require_once __DIR__ . '/../config/database.php';
                $connection = Database::getInstance();
            }
            $summary = $connection->prepare(
                "SELECT COUNT(*) AS total,
                    COALESCE(SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE 0 END), 0) AS income,
                    COALESCE(SUM(CASE WHEN tipo = 'gasto' THEN monto ELSE 0 END), 0) AS expenses,
                    COALESCE(SUM(CASE WHEN tipo = 'ingreso' THEN monto ELSE -monto END), 0) AS balance
                 FROM transacciones WHERE id_usuario = :user_id"
            );
            $summary->execute(['user_id' => $authenticatedUserId]);
            $totals = $summary->fetch(PDO::FETCH_ASSOC);
            if (!$totals) {
                throw new RuntimeException('Resumen no disponible');
            }
            $recent = $connection->prepare(
                'SELECT t.tipo, t.monto, t.concepto, t.fecha_transaccion, c.nombre_categoria
                 FROM transacciones t
                 LEFT JOIN categorias c ON c.id_categoria = t.id_categoria
                 WHERE t.id_usuario = :user_id
                 ORDER BY t.fecha_transaccion DESC, t.id_transaccion DESC LIMIT 5'
            );
            $recent->execute(['user_id' => $authenticatedUserId]);
            return [
                'status' => 'ready', 'message' => '',
                'income' => $totals['income'], 'expenses' => $totals['expenses'],
                'balance' => $totals['balance'], 'count' => (int) $totals['total'],
                'movements' => $recent->fetchAll(PDO::FETCH_ASSOC),
            ];
        } catch (Throwable $error) {
            $data['status'] = 'error';
            $data['message'] = 'No se pudieron cargar tus movimientos. Inténtalo de nuevo más tarde.';
            return $data;
        }
    }

    public function index(?int $authenticatedUserId = null): void
    {
        $dashboard = $this->data($authenticatedUserId);
        require __DIR__ . '/../views/dashboard.php';
    }
}
