<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../models/User.php';
verify_csrf();
$action = $_POST['action'] ?? '';
$target = $action === 'register' ? 'register' : 'login';
if ($action === 'logout') {
    $_SESSION = []; session_regenerate_id(true); set_flash('info', 'Has cerrado sesión.');
    header('Location: ../views/auth/login.php'); exit;
}
try {
    $email = trim((string)($_POST['correo'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $name = trim((string)($_POST['nombre'] ?? ''));
    $_SESSION['auth_old'] = ['correo'=>$email,'nombre'=>$name];
    if (!filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($email)>150) throw new InvalidArgumentException('Introduce un correo válido.');
    $users = new User();
    if ($action === 'register') {
        if (mb_strlen($name)<2 || mb_strlen($name)>100 || strlen($password)<8 || strlen($password)>72) throw new InvalidArgumentException('Nombre: de 2 a 100 caracteres. Contraseña: de 8 a 72 bytes.');
        if ($password !== ($_POST['password_confirmation'] ?? '')) throw new InvalidArgumentException('Las contraseñas no coinciden.');
        $id=$users->create($name,$email,$password);
    } elseif ($action === 'login') {
        $user=$users->byEmail($email);
        if (!$user || !password_verify($password,$user['password'])) throw new InvalidArgumentException('Usuario o contraseña incorrectos.');
        $id=(int)$user['id_usuario']; $name=$user['nombre'];
    } else { throw new InvalidArgumentException('Acción no válida.'); }
    session_regenerate_id(true); unset($_SESSION['auth_old'],$_SESSION['csrf_token']);
    $_SESSION['id_usuario']=$_SESSION['user_id']=$id;
    $_SESSION['nombre']=$_SESSION['user_name']=$name;
    set_flash('success',$action==='register' ? 'Cuenta creada correctamente.' : 'Bienvenido a SmartSpend.');
    header('Location: ../views/dashboard.php'); exit;
} catch (InvalidArgumentException $error) { set_flash('danger',$error->getMessage());
} catch (PDOException $error) { set_flash('danger',$error->getCode()==='23000' ? 'Este correo ya está registrado.' : 'No se pudo conectar con la base de datos. Inténtalo más tarde.'); }
header('Location: ../views/auth/'.$target.'.php'); exit;
