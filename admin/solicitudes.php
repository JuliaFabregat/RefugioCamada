<?php

declare(strict_types=1);
require __DIR__ . '/../includes/admin-auth.php';
require '../includes/database-connection.php';
require '../includes/functions.php';

// Procesar cambios de resolución
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['solicitud_id'], $_POST['resolucion'])) {
    $solId = (int)$_POST['solicitud_id'];
    $newRes = $_POST['resolucion']; // 'Aceptada' o 'Denegada'

    // Obtener el id del animal primero
    $sql = "SELECT id_animal FROM solicitudes_adopcion WHERE id = :id LIMIT 1";
    $stmt = pdo($pdo, $sql, ['id' => $solId]);
    $animalId = $stmt->fetchColumn();

    if ($newRes === 'Aceptada') {
        // Comprobar si ya existe una solicitud aceptada para este animal
        $sqlCheck = "SELECT COUNT(*) FROM solicitudes_adopcion WHERE id_animal = :aid AND resolucion = 'Aceptada'";
        $countAccepted = pdo($pdo, $sqlCheck, ['aid' => $animalId])->fetchColumn();

        if ($countAccepted > 0) {
            header('Location: solicitudes.php?error=Ya hay una solicitud aceptada para este animal');
            exit;
        }

        // Iniciar transacción
        $pdo->beginTransaction();

        // Denegar todas las solicitudes de ese animal
        pdo(
            $pdo,
            "UPDATE solicitudes_adopcion 
             SET resolucion = 'Denegada' 
             WHERE id_animal = :aid",
            ['aid' => $animalId]
        );

        // Aceptar la seleccionada
        pdo(
            $pdo,
            "UPDATE solicitudes_adopcion 
             SET resolucion = 'Aceptada' 
             WHERE id = :id",
            ['id' => $solId]
        );

        // Cambiar estado del animal a 'Adoptado'
        pdo(
            $pdo,
            "UPDATE animales 
             SET estado = 'Adoptado' 
             WHERE id = :aid",
            ['aid' => $animalId]
        );

        $pdo->commit();
    } elseif ($newRes === 'Denegada') { // Si es denegada
        $pdo->beginTransaction();

        // Denegar esta solicitud
        pdo(
            $pdo,
            "UPDATE solicitudes_adopcion 
             SET resolucion = 'Denegada' 
             WHERE id = :id",
            ['id' => $solId]
        );

        // Comprobar si quedan solicitudes Aceptadas o Pendientes
        $sql = "SELECT COUNT(*) FROM solicitudes_adopcion
                WHERE id_animal = :aid AND resolucion != 'Denegada'";
        $count = pdo($pdo, $sql, ['aid' => $animalId])->fetchColumn();

        // Si no hay ninguna otra solicitud activa, el animal vuelve a estar 'Disponible'
        if ((int)$count === 0) {
            pdo(
                $pdo,
                "UPDATE animales 
                 SET estado = 'Disponible' 
                 WHERE id = :aid",
                ['aid' => $animalId]
            );
        }

        $pdo->commit();
    }

    header('Location: solicitudes.php');
    exit;
}


// Obtener todas las solicitudes con usuario y animal
$sql = "SELECT s.id, s.fecha, s.resolucion,
               u.nombre AS usuario_nombre, u.apellidos AS usuario_apellidos, u.email AS usuario_email,
               a.nombre AS animal_nombre
        FROM solicitudes_adopcion s
        JOIN usuarios u   ON s.id_usuario = u.id
        JOIN animales a   ON s.id_animal  = a.id
        ORDER BY s.fecha DESC";
$solicitudes = pdo($pdo, $sql)->fetchAll();

// Datos
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