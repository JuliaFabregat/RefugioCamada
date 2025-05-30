<?php
declare(strict_types=1);
session_start();

require_once '../models/Conexion.php';
$pdo = Conexion::obtenerConexion();

require '../includes/functions.php';
require_once '../models/Usuario.php';
require_once '../models/SolicitudAdopcion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['usuario_id'];

$user = Usuario::obtenerPorId($pdo, $userId);

if (!$user) {
    // Si no se encuentra el usuario, redirigir al login o mostrar error
    header('Location: login.php');
    exit;
}

// Sus solicitudes de adopción
$solicitudes = SolicitudAdopcion::obtenerPorUsuario($pdo, $userId);

// Datos
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
            <h2>Bienvenido, <?= htmlspecialchars($user->getNombre()) . ' ' . htmlspecialchars($user->getApellidos()) ?></h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($user->getEmail()) ?></p>
        </section>

        <section>
            <h2>Mis solicitudes de adopción</h2>
            <?php if (empty($solicitudes)): ?>
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
                    <tbody>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['animal_nombre']) ?></td>
                                <td><?= htmlspecialchars($s['fecha']) ?></td>
                                <td><?= htmlspecialchars($s['resolucion']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>

                    </tbody>
                </table>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include '../includes/footer-web.php'; ?>