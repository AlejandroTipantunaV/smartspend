<?php
/**
 * TransactionController — CRUD de ingresos y gastos.
 */
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php';

class TransactionController
{
    private const VIEW_LIST = '../views/transactions.php';
    private const ALLOWED_TYPES = ['ingreso', 'gasto'];

    private Transaction $transactions;
    private Category $categories;

    public function __construct(?Transaction $transactions = null, ?Category $categories = null)
    {
        $this->transactions = $transactions ?? new Transaction();
        $this->categories = $categories ?? new Category();
    }

    public function store(): void
    {
        $idUsuario = require_login();
        $this->assertPost();

        $result = $this->validateInput($_POST, $idUsuario);
        if (!$result['ok']) {
            $this->flashAndRedirect('danger', implode(' ', $result['errors']));
        }

        $datos = $result['data'];
        $datos['id_usuario'] = $idUsuario;

        $ok = $this->transactions->create($datos);
        $this->flashAndRedirect(
            $ok ? 'success' : 'danger',
            $ok ? 'Transacción registrada correctamente.' : 'No se pudo registrar la transacción.'
        );
    }

    public function update(): void
    {
        $idUsuario = require_login();
        $this->assertPost();

        $id = (int) ($_POST['id_transaccion'] ?? 0);
        $this->findOwnedOrFail($id, $idUsuario);

        $result = $this->validateInput($_POST, $idUsuario);
        if (!$result['ok']) {
            $this->flashAndRedirect(
                'danger',
                implode(' ', $result['errors']),
                '../views/edit_transaction.php?id=' . $id
            );
        }

        $datos = $result['data'];
        $datos['id_usuario'] = $idUsuario;

        $ok = $this->transactions->update($id, $datos);
        $this->flashAndRedirect(
            $ok ? 'success' : 'danger',
            $ok ? 'Transacción actualizada correctamente.' : 'No se pudo actualizar la transacción.'
        );
    }

    public function delete(): void
    {
        $idUsuario = require_login();
        $this->assertPost();

        $id = (int) ($_POST['id_transaccion'] ?? 0);
        $this->findOwnedOrFail($id, $idUsuario);

        $ok = $this->transactions->delete($id, $idUsuario);
        $this->flashAndRedirect(
            $ok ? 'success' : 'danger',
            $ok ? 'Transacción eliminada correctamente.' : 'No se pudo eliminar la transacción.'
        );
    }

    private function assertPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flashAndRedirect('danger', 'Método no permitido.');
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function findOwnedOrFail(int $id, int $idUsuario): array
    {
        if ($id <= 0) {
            $this->flashAndRedirect('danger', 'Transacción no válida.');
        }

        $row = $this->transactions->getById($id, $idUsuario);
        if (!$row) {
            $this->flashAndRedirect('danger', 'No se encontró la transacción o no tiene permiso.');
        }

        return $row;
    }

    /**
     * @return array{ok:bool, errors:array<int,string>, data:array<string,mixed>}
     */
    private function validateInput(array $input, int $idUsuario): array
    {
        $errors = [];

        $tipo = trim((string) ($input['tipo'] ?? ''));
        $idCategoria = (int) ($input['id_categoria'] ?? 0);
        $montoRaw = str_replace(',', '.', trim((string) ($input['monto'] ?? '')));
        $concepto = trim((string) ($input['concepto'] ?? ''));
        $fecha = trim((string) ($input['fecha_transaccion'] ?? ''));

        if (!in_array($tipo, self::ALLOWED_TYPES, true)) {
            $errors[] = 'Seleccione un tipo válido (Ingreso o Gasto).';
        }

        if ($idCategoria <= 0) {
            $errors[] = 'Seleccione una categoría.';
        }

        if ($montoRaw === '' || !preg_match('/^\d{1,8}(\.\d{1,2})?$/', $montoRaw) || (float) $montoRaw <= 0 || (float) $montoRaw > 99999999.99) {
            $errors[] = 'El monto debe ser un número mayor a 0.';
        }

        if ($concepto === '' || mb_strlen($concepto) < 3 || mb_strlen($concepto) > 255) {
            $errors[] = 'El concepto debe tener al menos 3 caracteres.';
        }

        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);
        if (!$date || $date->format('Y-m-d') !== $fecha || $fecha < '1000-01-01') {
            $errors[] = 'Ingrese una fecha válida.';
        }

        if (empty($errors) && !$this->categories->belongsToType($idCategoria, $tipo, $idUsuario)) {
            $errors[] = 'La categoría no corresponde al tipo seleccionado o no te pertenece.';
        }

        return [
            'ok' => empty($errors),
            'errors' => $errors,
            'data' => [
                'tipo' => $tipo,
                'id_categoria' => $idCategoria,
                'monto' => round((float) $montoRaw, 2),
                'concepto' => $concepto,
                'fecha_transaccion' => $fecha,
            ],
        ];
    }

    private function flashAndRedirect(string $type, string $message, string $path = self::VIEW_LIST): void
    {
        set_flash($type, $message);
        header('Location: ' . $path);
        exit;
    }
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
require_login();

$allowedActions = ['store', 'update', 'delete'];
if (!in_array($action, $allowedActions, true)) {
    header('Location: ../views/transactions.php');
    exit;
}

verify_csrf();
$controller = new TransactionController();

switch ($action) {
    case 'store':
        $controller->store();
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete();
        break;
}
