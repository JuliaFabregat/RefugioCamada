<?php

declare(strict_types=1);
session_start();

require_once '../models/Conexion.php';
$pdo = Conexion::obtenerConexion();

require '../includes/functions.php';
require '../models/Usuario.php';

$email = '';
$message = '';
$error = '';
$show_password_fields = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['check_email'])) {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL) ?? '';
        if ($email) {
            $user = Usuario::obtenerPorEmail($pdo, $email);
            if ($user) {
                $message = "Email encontrado. Por favor ingresa la nueva contraseña.";
                $show_password_fields = true;
                $_SESSION['reset_user_id'] = $user->getId();
            } else {
                $error = "No se encontró ninguna cuenta con ese correo.";
            }
        } else {
            $error = "Por favor ingresa un email válido.";
        }
    }

    if (isset($_POST['reset_password'])) {
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if (!isset($_SESSION['reset_user_id'])) {
            $error = "Error inesperado. Verifica el correo primero.";
        } elseif ($password === '' || $password !== $password2) {
            $error = "Las contraseñas deben coincidir y no estar vacías.";
            $show_password_fields = true;
        } else {
            $success = Usuario::actualizarPassword($pdo, $_SESSION['reset_user_id'], $password);
            if ($success) {
                unset($_SESSION['reset_user_id']);
                $message = "Contraseña cambiada con éxito. <br> <a href='login.php'>Inicia sesión</a>.";
            } else {
                $error = "Error al actualizar la contraseña, intenta nuevamente.";
                $show_password_fields = true;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reestablecer contraseña</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/web/login.css">
</head>

<body>
    <div class="login-container">
        <h1>Reestablecer contraseña</h1>

        <?php if ($message): ?>
            <p class="msg"><?= $message ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
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
                    value="<?= htmlspecialchars($email) ?>"
                    <?= $show_password_fields ? 'readonly' : '' ?>>
            </div>

            <?php if (!$show_password_fields): ?>
                <button type="submit" name="check_email">Verificar email</button>
            <?php else: ?>
                <div class="form-group">
                    <label for="password">Nueva contraseña</label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        required>
                </div>

                <div class="form-group">
                    <label for="password2">Repite nueva contraseña</label>
                    <input
                        type="password"
                        name="password2"
                        id="password2"
                        class="form-control"
                        required>
                </div>

                <button type="submit" name="reset_password">Cambiar contraseña</button>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>