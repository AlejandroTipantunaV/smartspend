<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';

            $user = User::getByEmail($correo);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id_usuario'];
                $_SESSION['user_name'] = $user['nombre'];

                header("Location: ../views/dashboard.php");
                exit;
            } else {
                header("Location: ../views/auth/login.php?error=Correo o contraseña incorrectos");
                exit;
            }
        }
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($nombre) || empty($correo) || empty($password)) {
                header("Location: ../views/auth/register.php?error=Todos los campos son obligatorios");
                exit;
            }

            if ($password !== $confirm_password) {
                header("Location: ../views/auth/register.php?error=Las contraseñas no coinciden");
                exit;
            }

            if (User::getByEmail($correo)) {
                header("Location: ../views/auth/register.php?error=El correo ya está registrado");
                exit;
            }

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            if (User::create($nombre, $correo, $hashed_password)) {
                header("Location: ../views/auth/login.php?success=Registro exitoso. Ya puedes iniciar sesión.");
                exit;
            } else {
                header("Location: ../views/auth/register.php?error=Error al registrar el usuario.");
                exit;
            }
        }
    }

    // Actualizar información del usuario
    public function updateProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../views/auth/login.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['user_id'];
            $nombre = trim($_POST['nombre'] ?? '');
            $correo = trim($_POST['correo'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($nombre) || empty($correo)) {
                header("Location: ../views/auth/profile.php?error=El nombre y el correo son obligatorios");
                exit;
            }

            if (User::getByEmailExceptUser($correo, $id_usuario)) {
                header("Location: ../views/auth/profile.php?error=El correo ya está en uso por otro usuario");
                exit;
            }

            $hashed_password = null;
            if (!empty($password)) {
                if ($password !== $confirm_password) {
                    header("Location: ../views/auth/profile.php?error=Las nuevas contraseñas no coinciden");
                    exit;
                }
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            }

            if (User::update($id_usuario, $nombre, $correo, $hashed_password)) {
                $_SESSION['user_name'] = $nombre; // Actualizar el nombre almacenado en la sesión
                header("Location: ../views/auth/profile.php?success=Perfil actualizado correctamente");
                exit;
            } else {
                header("Location: ../views/auth/profile.php?error=Error al actualizar el perfil");
                exit;
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header("Location: ../views/auth/login.php");
        exit;
    }

    // Eliminar perfil de usuario
    public function deleteProfile() {
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../views/auth/login.php");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['user_id'];

            if (User::delete($id_usuario)) {
                session_unset();
                session_destroy();
                header("Location: ../views/auth/login.php?success=Tu cuenta ha sido eliminada correctamente.");
                exit;
            } else {
                header("Location: ../views/auth/profile.php?error=Error al eliminar la cuenta.");
                exit;
            }
        }
    }
}

// Router básico según el parámetro 'action'
if (isset($_GET['action'])) {
    $auth = new AuthController();
    switch ($_GET['action']) {
        case 'login':
            $auth->login();
            break;
        case 'register':
            $auth->register();
            break;
        case 'update_profile':
            $auth->updateProfile();
            break;
        case 'logout':
            $auth->logout();
            break;
        case 'delete_profile':
            $auth->deleteProfile();
            break;
    }
}
?>