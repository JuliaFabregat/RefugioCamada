<?php
session_start();

// Si ya está logueado, redirigir al inicio
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    // En el futuro - Login de distintos usuarios de distintos Refugios si me da la vida :')
    if ($username === 'adminCamada' && $password === 'adminCamada') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $_SESSION['login_error'] = 'Usuario o contraseña incorrectos.';
    }
}

// Recuperar y limpiar error
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>




<!-- HTML -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <!-- CSS general y login -->
    <link rel="stylesheet" href="../css/styles.css">    
    <link rel="stylesheet" href="../css/admin/login.css">
</head>
<body>
    <div class="login-container">
        <h1>Iniciar sesión</h1>
        <h2>Admin. Refugio Camada</h2>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <form method="post" novalidate>
            <label for="username">Usuario</label>
            <input type="text" name="username" class="form-control" required>

            <label for="password">Contraseña</label>
            <input type="password" name="password" class="form-control" required>

            <button type="submit">LogIn</button>
        </form>
    </div>
</body>
</html>