<?php
/**
 * CategoryController — CRUD for categories.
 */
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/Category.php';

class CategoryController
{
    private const VIEW_LIST = '../views/category/index.php';
    private const ALLOWED_TYPES = ['ingreso', 'gasto'];

    private Category $categories;
    private int $userId;

    public function __construct(?Category $categories = null)
    {
        $this->categories = $categories ?? new Category();
        $this->userId = (int)($_SESSION['user_id'] ?? $_SESSION['id_usuario'] ?? 0);
    }

    public function store(): void
    {
        require_login();
        $this->assertPost();

        $result = $this->validateInput($_POST);
        if (!$result['ok']) {
            $this->flashAndRedirect('danger', implode(' ', $result['errors']));
        }

        $ok = $this->categories->create($result['data'], $this->userId);
        $this->flashAndRedirect(
            $ok ? 'success' : 'danger',
            $ok ? 'Categoría registrada correctamente.' : 'No se pudo registrar la categoría.'
        );
    }

    public function update(): void
    {
        require_login();
        $this->assertPost();

        $id = (int) ($_POST['id_categoria'] ?? 0);
        $this->findOrFail($id);

        $result = $this->validateInput($_POST);
        if (!$result['ok']) {
            $this->flashAndRedirect(
                'danger',
                implode(' ', $result['errors']),
                '../views/category/edit.php?id=' . $id
            );
        }

        $current = $this->categories->getById($id, $this->userId);
        if ($current['tipo'] !== $result['data']['tipo'] && $this->categories->isUsed($id, $this->userId)) {
            $this->flashAndRedirect('danger', 'No se puede cambiar el tipo de una categoría con transacciones.');
        }
        $ok = $this->categories->update($id, $result['data'], $this->userId);
        $this->flashAndRedirect(
            $ok ? 'success' : 'danger',
            $ok ? 'Categoría actualizada correctamente.' : 'No se pudo actualizar la categoría.'
        );
    }

    public function delete(): void
    {
        require_login();
        $this->assertPost();

        $id = (int) ($_POST['id_categoria'] ?? 0);
        $this->findOrFail($id);

        try {
            $ok = $this->categories->deactivate($id, $this->userId);
            $this->flashAndRedirect(
                $ok ? 'success' : 'danger',
                $ok ? 'Categoría desactivada correctamente.' : 'No se pudo desactivar la categoría.'
            );
        } catch (PDOException $e) {
            $this->flashAndRedirect('danger', 'Error al desactivar la categoría.');
        }
    }

    public function activate(): void
    {
        $this->assertPost();

        $id = (int) ($_POST['id_categoria'] ?? 0);
        $this->findOrFail($id);

        try {
            $ok = $this->categories->activate($id, $this->userId);
            $this->flashAndRedirect(
                $ok ? 'success' : 'danger',
                $ok ? 'Categoría activada correctamente.' : 'No se pudo activar la categoría.'
            );
        } catch (PDOException $e) {
            $this->flashAndRedirect('danger', 'Error al activar la categoría.');
        }
    }

    private function assertPost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flashAndRedirect('danger', 'Método no permitido.');
        }
    }

    private function findOrFail(int $id): array
    {
        if ($id <= 0) {
            $this->flashAndRedirect('danger', 'Categoría no válida.');
        }

        $row = $this->categories->getById($id, $this->userId);
        if (!$row) {
            $this->flashAndRedirect('danger', 'No se encontró la categoría.');
        }

        return $row;
    }

    private function validateInput(array $input): array
    {
        $errors = [];

        $name = trim((string) ($input['nombre_categoria'] ?? ''));
        $type = trim((string) ($input['tipo'] ?? ''));
        $description = trim((string) ($input['descripcion'] ?? ''));
        $icon = trim((string) ($input['icono'] ?? ''));
        $color = trim((string) ($input['color'] ?? ''));
        $status = isset($input['estado']) ? (int) $input['estado'] : 1;

        if ($name === '' || mb_strlen($name) < 3 || mb_strlen($name) > 50) {
            $errors[] = 'El nombre de la categoría debe tener al menos 3 caracteres.';
        }

        if (!in_array($type, self::ALLOWED_TYPES, true)) {
            $errors[] = 'Seleccione un tipo válido (Ingreso o Gasto).';
        }

        if (mb_strlen($description)>255 || strlen($icon)>100 || !preg_match('/^#[0-9a-fA-F]{6}$/', $color) || !in_array($status,[0,1],true)) { $errors[] = 'Descripción, icono, color o estado no válido.'; }
        return [
            'ok' => empty($errors),
            'errors' => $errors,
            'data' => [
                'nombre_categoria' => $name,
                'tipo'             => $type,
                'descripcion'      => $description,
                'icono'            => $icon,
                'color'            => $color,
                'estado'           => $status,
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
verify_csrf();
$controller = new CategoryController();

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
    case 'activate':
        $controller->activate();
        break;
    default:
        header('Location: ../views/category/index.php');
        exit;
}
