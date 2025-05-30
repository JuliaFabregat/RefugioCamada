<?php

declare(strict_types=1);
require __DIR__ . '/../includes/admin-auth.php';

require_once '../models/Conexion.php';
require_once '../models/SolicitudAdopcion.php';
require_once '../includes/functions.php';

$pdo = Conexion::obtenerConexion();

// Procesar cambios de resolución
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitud_id'], $_POST['resolucion'])) {
    $solId = (int)$_POST['solicitud_id'];
    $newRes = $_POST['resolucion'];

    if ($newRes === 'Aceptada') {
        SolicitudAdopcion::aceptar($pdo, $solId);
    } elseif ($newRes === 'Denegada') {
        SolicitudAdopcion::denegar($pdo, $solId);
    }

    header('Location: solicitudes.php');
    exit;
}

// Obtener todas las solicitudes
$solicitudes = SolicitudAdopcion::obtenerTodas($pdo);

// Datos meta y para estilos
$title       = html_escape('Solicitudes de Adopción');
$description = html_escape('Revisa y gestiona las solicitudes de adopción');
$section     = 'solicitudes';
?>

<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/admin/solicitudes.css">
<link rel="stylesheet" href="../css/admin/lista-animales.css">

<main>
    <section>
        <div class="container">
            <h1>Solicitudes de Adopción</h1>

            <?php if (empty($solicitudes)): ?>
                <p>No hay solicitudes registradas.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Animal</th>
                            <th>Resolución</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitudes as $s): ?>
                            <tr>
                                <td><?= html_escape($s['fecha']) ?></td>
                                <td><?= html_escape("{$s['usuario_nombre']} {$s['usuario_apellidos']}") ?></td>
                                <td><a href="mailto:<?= html_escape($s['usuario_email']) ?>"><?= html_escape($s['usuario_email']) ?></a></td>
                                <td><?= html_escape($s['animal_nombre']) ?></td>
                                <td>
                                    <span class="status status-<?= strtolower(str_replace(' ', '', $s['resolucion'])) ?>">
                                        <?= html_escape($s['resolucion']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($s['resolucion'] === 'En proceso'): ?>
                                        <form method="post" style="display:inline">
                                            <input type="hidden" name="solicitud_id" value="<?= $s['id'] ?>">
                                            <button
                                                type="submit"
                                                name="resolucion"
                                                value="Aceptada"
                                                class="btn btn-primary">
                                                Aceptar
                                            </button>
                                        </form>
                                        <form method="post" style="display:inline">
                                            <input type="hidden" name="solicitud_id" value="<?= $s['id'] ?>">
                                            <button
                                                type="submit"
                                                name="resolucion"
                                                value="Denegada"
                                                class="btn btn-secondary">
                                                Denegar
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">Resuelta</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include '../includes/footer.php'; ?>