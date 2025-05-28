<?php

declare(strict_types=1);
session_start();
require '../includes/database-connection.php';
require '../includes/functions.php';
require '../models/Usuario.php';

$errors = [];
$nombre = $apellidos = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');

    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    if ($email === false) {
        $email = '';
    }

    $pass  = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    $errors = Usuario::registrarUsuario($pdo, $nombre, $apellidos, $email, $pass, $pass2);

    if (empty($errors)) {
        header('Location: login.php?registered=1');
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/web/login.css">
</head>

<body>
    <div class="login-container">
        <h1>Únete a la camada</h1>

        <form method="post" novalidate>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input
                    type="text"
                    name="nombre"
                    id="nombre"
                    class="form-control"
                    required
                    value="<?= htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') ?>">
                <?php if (isset($errors['nombre'])): ?>
                    <p class="error"><?= htmlspecialchars($errors['nombre'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input
                    type="text"
                    name="apellidos"
                    id="apellidos"
                    class="form-control"
                    required
                    value="<?= htmlspecialchars($apellidos, ENT_QUOTES, 'UTF-8') ?>">
                <?php if (isset($errors['apellidos'])): ?>
                    <p class="error"><?= htmlspecialchars($errors['apellidos'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    class="form-control"
                    required
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>">
                <?php if (isset($errors['email'])): ?>
                    <p class="error"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    required>
                <?php if (isset($errors['password'])): ?>
                    <p class="error"><?= htmlspecialchars($errors['password'], ENT_QUOTES, 'UTF-8') ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password2">Repite Contraseña</label>
                <input
                    type="password"
                    name="password2"
                    id="password2"
                    class="form-control"
                    required>
            </div>

            <button class="btnLogin" type="submit">Crear cuenta</button>
        </form>

        <br>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>

</html>