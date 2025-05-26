<?php
declare(strict_types=1);
session_start();
require '../includes/database-connection.php';
require '../includes/functions.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre    = trim($_POST['nombre'] ?? '');
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email     = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '';
    $pass      = $_POST['password'] ?? '';
    $pass2     = $_POST['password2'] ?? '';

    // Validaciones básicas
    if ($nombre === '')      $errors[] = 'Nombre obligatorio.';
    if ($apellidos === '')   $errors[] = 'Apellidos obligatorios.';
    if (!$email)             $errors[] = 'Email no válido.';
    if ($pass === '' || $pass !== $pass2)
        $errors[] = 'Las contraseñas deben coincidir y no estar vacías.';

    if (!$errors) {
        // Comprobar email único
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        if (pdo($pdo, $sql, ['email' => $email])->fetchColumn() > 0) {
            $errors[] = 'Ese email ya está registrado.';
        } else {
            // Insertar
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            pdo(
                $pdo,
                "INSERT INTO usuarios
                (nombre, apellidos, email, password, admin, created_at)
             VALUES
                (:n, :a, :e, :p, 0, NOW())",
                ['n' => $nombre, 'a' => $apellidos, 'e' => $email, 'p' => $hash]
            );
            header('Location: login.php?registered=1');
            exit;
        }
    }
}
?>




<!-- HTML -->
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

        <?php foreach ($errors as $e): ?>
            <p class="error"><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endforeach; ?>

        <form method="post" novalidate>
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input 
                    type="text" 
                    name="nombre" 
                    id="nombre" 
                    class="form-control" 
                    required 
                    value="<?= htmlspecialchars($nombre ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos</label>
                <input 
                    type="text" 
                    name="apellidos" 
                    id="apellidos" 
                    class="form-control" 
                    required
                    value="<?= htmlspecialchars($apellidos ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control" 
                    required
                    value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>"
                >
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input 
                    type="password" 
                    name="password" 
                    id="password" 
                    class="form-control" 
                    required
                >
            </div>
            <div class="form-group">
                <label for="password2">Repite Contraseña</label>
                <input 
                    type="password" 
                    name="password2" 
                    id="password2" 
                    class="form-control" 
                    required
                >
            </div>

            <button type="submit">Crear cuenta</button>
        </form>
        <br>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>

</html>