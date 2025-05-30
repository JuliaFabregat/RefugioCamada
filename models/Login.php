<?php
require_once 'Conexion.php';

class Auth
{
    // Asegura que la sesión esté iniciada
    private static function asegurarSesion(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    // Intenta hacer login con email y contraseña
    public static function login(string $email, string $password): bool
    {
        $pdo = Conexion::obtenerConexion();
        $sql = "SELECT id, nombre, apellidos, password, admin FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['usuario_id'] = (int)$user['id'];
            $_SESSION['usuario_nombre'] = $user['nombre'] . ' ' . $user['apellidos'];
            $_SESSION['usuario_admin'] = (bool)$user['admin'];
            return true;
        }
        return false;
    }

    // Destruye la sesión para hacer logout
    public static function logout(): void
    {
        self::asegurarSesion();
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            setcookie(session_name(), '', time() - 42000, '/');
        }
        session_destroy();
    }

    // Comprueba si el usuario está logueado
    public static function isLoggedIn(): bool
    {
        self::asegurarSesion();
        return !empty($_SESSION['usuario_id']);
    }

    // Devuelve el nombre completo del usuario logueado
    public static function getUserName(): ?string
    {
        self::asegurarSesion();
        return $_SESSION['usuario_nombre'] ?? null;
    }
}
