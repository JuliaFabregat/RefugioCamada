<?php

declare(strict_types=1);

class Usuario
{
    private int $id;
    private string $nombre;
    private string $apellidos;
    private string $email;
    private bool $admin;
    private string $password;

    // Constructor con id opcional primero
    public function __construct(
        string $nombre,
        string $apellidos,
        string $email,
        string $password = '',
        bool $admin = false,
        int $id = 0
    ) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->email = strtolower($email);
        $this->password = $password;
        $this->admin = $admin;
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellidos(): string
    {
        return $this->apellidos;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function isAdmin(): bool
    {
        return $this->admin;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    // Setters
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setApellidos(string $apellidos): void
    {
        $this->apellidos = $apellidos;
    }

    public function setEmail(string $email): void
    {
        $this->email = strtolower($email);
    }

    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    // Validación de registro
    public function validarRegistro(): array
    {
        $errores = [];

        if ($this->nombre === '') {
            $errores['nombre'] = 'Nombre obligatorio.';
        }
        if ($this->apellidos === '') {
            $errores['apellidos'] = 'Apellidos obligatorios.';
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Email no válido.';
        }

        return $errores;
    }


    // Obtener usuario por ID
    public static function obtenerPorId(PDO $pdo, int $id): ?self
    {
        $sql = "SELECT id, nombre, apellidos, email, password, admin FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new self(
            $row['nombre'],
            $row['apellidos'],
            $row['email'],
            $row['password'],
            (bool)$row['admin'],
            (int)$row['id']
        );
    }

    // Insertar nuevo usuario
    public function registrar(PDO $pdo): void
    {
        $sql = "INSERT INTO usuarios (nombre, apellidos, email, password, admin, created_at)
                VALUES (:n, :a, :e, :p, 0, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'n' => $this->nombre,
            'a' => $this->apellidos,
            'e' => $this->email,
            'p' => password_hash($this->password, PASSWORD_DEFAULT)
        ]);
    }

    // Registrar nuevo usuario si se cumplen las validaciones
    public static function registrarUsuario(PDO $pdo, string $nombre, string $apellidos, string $email, string $password, string $password2): array
    {
        $usuario = new self($nombre, $apellidos, $email, $password);

        $errores = $usuario->validarRegistro();

        if ($password === '' || $password !== $password2) {
            $errores['password'] = 'Las contraseñas deben coincidir y no estar vacías.';
        }

        if ($usuario->emailDuplicado($pdo)) {
            $errores['email'] = 'Este email ya está registrado.';
        }

        if (empty($errores)) {
            $usuario->registrar($pdo);
        }

        return $errores;
    }



    // Obtener usuario por email - para login
    public static function obtenerPorEmail(PDO $pdo, string $email): ?self
    {
        $sql = "SELECT id, nombre, apellidos, email, password, admin FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return new self(
            $row['nombre'],
            $row['apellidos'],
            $row['email'],
            $row['password'],
            (bool)$row['admin'],
            (int)$row['id']
        );
    }

    // Verifica si el email ya existe en la BD
    public function emailDuplicado(PDO $pdo): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $this->email]);
        return $stmt->fetchColumn() > 0;
    }

    // Cambiar contraseña - Usuario
    public static function actualizarPassword(PDO $pdo, int $id, string $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET password = :password WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['password' => $hash, 'id' => $id]);
    }
}
