<?php

declare(strict_types=1);
session_start();
require '../includes/database-connection.php';
require '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Datos del usuario
$userId = $_SESSION['user_id'];
$sql = "SELECT nombre, apellidos, email FROM usuarios WHERE id = :id";
$user = pdo($pdo, $sql, ['id' => $userId])->fetch();

// Sus solicitudes de adopción
$sql = "SELECT s.id, s.fecha, s.resolucion,
               a.nombre AS animal_nombre, a.id AS animal_id
        FROM solicitudes_adopcion s
        JOIN animales a ON s.id_animal = a.id
        WHERE s.id_usuario = :uid
        ORDER BY s.fecha DESC";
$solicitudes = pdo($pdo, $sql, ['uid' => $userId])->fetchAll();

$title = html_escape('Panel de Usuario - Refugio Camada');
$description = html_escape('Panel de los Usuarios del Refugio Camada.');
$section = 'Panel de Usuario';
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<link rel="stylesheet" href="../css/web/panel-usuario.css">

<main>
    <div class="container">
        <h1>Mi perfil</h1>

        <section>
            <h2>Bienvenido, <?= htmlspecialchars($user['nombre']) . ' ' . htmlspecialchars($user['apellidos']) ?></h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        </section>

        <section>
            <h2>Mis solicitudes de adopción</h2>
            <?php if (!$solicitudes): ?>
                <p>No has realizado ninguna solicitud aún.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Animal</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><a href="animal-web.php?id=<?= $s['animal_id'] ?>">
                                        <?= htmlspecialchars($s['animal_nombre']) ?></a></td>
                                <td><?= htmlspecialchars($s['fecha']) ?></td>
                                <td><?= htmlspecialchars($s['resolucion']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include '../includes/footer-web.php'; ?>