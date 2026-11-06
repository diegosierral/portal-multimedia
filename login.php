<?php
/**
 * @title Login y registro
 * @description Registro, inicio y cierre de sesión de usuarios.
 * @author Karen Arumil Lima Uranga
 * @date 2026-11-06
 */

session_start();

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'portal_multimedia';
$username = 'usuario_multimedia';
$password = 'ibero2026';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

$mensaje = '';
$tipo_alerta = '';

// Cierre de sesión
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}

// Registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registro'])) {
    $nombre = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    $pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre_completo, correo, password) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $correo, $pass_hash]);
        $mensaje = "Registro exitoso. Ahora puedes iniciar sesión.";
        $tipo_alerta = "success";
    } catch(PDOException $e) {
        $mensaje = "Error al registrar: Puede que el correo ya exista.";
        $tipo_alerta = "error";
    }
}

// Inicio de sesión
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $correo = $_POST['correo'];
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($pass, $usuario['password'])) {
        $_SESSION['id_usuario'] = $usuario['id_usuario'];
        $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
        header("Location: index.php");
        exit;
    } else {
        $mensaje = "Correo o contraseña incorrectos.";
        $tipo_alerta = "error";
    }
}
?>