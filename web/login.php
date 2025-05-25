<?php
declare(strict_types=1);
session_start();
require '../includes/database-connection.php';
require '../includes/functions.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        // Buscar usuario
        $sql = "SELECT id, nombre, apellidos, password, admin 
                FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = pdo($pdo, $sql, ['email' => $email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Login correcto
            $_SESSION['user_id']   = (int)$user['id'];
            $_SESSION['user_name'] = $user['nombre'] . ' ' . $user['apellidos'];
            $_SESSION['is_admin']  = (bool)$user['admin'];

            if ($_SESSION['is_admin']) {
                header('Location: ../admin/index.php');
            } else {
                header('Location: index.php');
            }
            exit;
        }
    }
    $error = 'Email o contraseña incorrectos.';
}
?>




<!-- HTML -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/web/login.css">
</head>

<body>
    <div class="login-container">
        <h1>Iniciar sesión</h1>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form method="post" novalidate>
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    class="form-control" 
                    required 
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>"
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

            <button type="submit">Entrar</button>
        </form>
        <br>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
        <p><a href="cambiar-pass.php">Olvidé mi contraseña</a></p>
    </div>
</body>

</html>