<?php

declare(strict_types=1);
session_start();

require_once '../models/Conexion.php';
$pdo = Conexion::obtenerConexion();

require '../includes/functions.php';
require '../models/Usuario.php';

$errores = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';

    // Validación básica
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = 'Datos no válidos.';
    }

    if (!$errores) {
        // Usar método de Usuario para obtener por email
        $usuario = Usuario::obtenerPorEmail($pdo, $email);

        if ($usuario && password_verify($password, $usuario->getPassword())) {
            $_SESSION['usuario_id'] = $usuario->getId();
            $_SESSION['usuario_nombre'] = $usuario->getNombre();
            $_SESSION['usuario_admin'] = $usuario->isAdmin();

            error_log('isAdmin: ' . ($_SESSION['usuario_admin'] ? 'true' : 'false')); // Ver en el log

            if ($usuario->isAdmin()) {
                header('Location: ../admin/index.php');
                exit;
            } else {
                header('Location: index.php');
                exit;
            }
        } else {
            $errores[] = 'Email o contraseña incorrectos.';
        }
    }
}
?>

<!-- HTML -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/web/login.css">
</head>

<body>
    <div class="login-container">
        <?php if (isset($_GET['registered']) && $_GET['registered'] == '1'): ?>
            <p class="success">Cuenta creada correctamente, ¡inicia sesión!</p>
        <?php endif; ?>

        <h1>Iniciar sesión</h1>

        <?php foreach ($errores as $e): ?>
            <p class="error"><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endforeach; ?>

        <form method="post" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    required
                    value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    required>
            </div>

            <button class="btnLogin" type="submit">Entrar</button>
        </form>
        <br>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>
        <p><a href="cambiar-pass.php">¿Olvidaste tu contraseña? Cambiar contraseña</a></p>
    </div>
</body>

</html>