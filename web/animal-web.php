<?php

declare(strict_types=1);
session_start();
require '../includes/database-connection.php';
require '../includes/functions.php';
require_once '../models/Animal.php';
require_once '../models/SolicitudAdopcion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    // Redirige a página 404 si no hay ID válido
    include '../web/page-not-found.php';
    exit;
}

$animal = Animal::obtenerPorId($pdo, $id);
if (!$animal) {
    include '../web/page-not-found.php';
    exit;
}

// Procesar solicitud si se envió
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['usuario_id'])) {
    if ($animal['estado'] === 'Disponible' || $animal['estado'] === 'En proceso') {
        if (SolicitudAdopcion::yaAceptada($pdo, $id)) {
            $mensaje = 'Este animal ya fue adoptado, no puedes enviar otra solicitud.';
        } elseif (SolicitudAdopcion::yaSolicitada($pdo, $id, $_SESSION['usuario_id'])) {
            $mensaje = 'Ya has enviado una solicitud para este animal.';
        } else {
            $solicitud = new SolicitudAdopcion($_SESSION['usuario_id'], $id);
            $solicitud->guardar($pdo);
            $mensaje = 'Solicitud enviada correctamente. Te contactaremos pronto.';
        }
    } else {
        $mensaje = 'Este animal ya no está disponible para adopción.';
    }
}

// Anterior / siguiente
$prev = Animal::obtenerAnterior($pdo, $id);
$next = Animal::obtenerSiguiente($pdo, $id);

// Datos
$title       = html_escape($animal['nombre']);
$description = html_escape("Detalles de {$animal['nombre']}");
$section     = 'descripcionAnimal';
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<link rel="stylesheet" href="../css/web/animal-web.css">

<main class="container animal-web">
    <div class="nav-animal">
        <?php if ($prev): ?>
            <a href="?id=<?= $prev ?>">&lt; ANTERIOR</a>
        <?php endif ?>
        <a href="lista-animales-web.php" class="volver">VOLVER</a>
        <?php if ($next): ?>
            <a href="?id=<?= $next ?>" class="siguiente">SIGUIENTE &gt;</a>
        <?php endif ?>
    </div>

    <div class="detalles-animal">
        <div class="animal-imagen">
            <img src="../uploads/<?= html_escape($animal['image_file'] ?: 'blank.jpg') ?>"
                alt="<?= html_escape($animal['image_alt'] ?: $animal['nombre']) ?>">
        </div>
        <div class="animal-info">
            <h1><?= html_escape($animal['nombre']) ?></h1>
            <ul class="animal-datos">
                <li><strong>Especie:</strong> <?= html_escape($animal['especie']) ?></li>
                <li><strong>Raza:</strong> <?= html_escape($animal['raza']) ?></li>
                <li><strong>Edad:</strong> <?= html_escape($animal['edad']) ?></li>
                <li><strong>Género:</strong> <?= html_escape($animal['genero']) ?></li>
                <li><strong>Rescatado:</strong> <?= format_date($animal['joined']) ?></li>
            </ul>

            <div class="descripcion">
                <h2>Dato curioso sobre la especie</h2>
                <p><?= html_escape($animal['especie_descripcion'] ?: 'No hay dato curioso disponible :(') ?></p>
            </div>

            <div class="vet-icons">
                <?php
                $fields = [
                    'Microchip'   => $animal['microchip'],
                    'Castración'  => $animal['castracion'],
                ];
                if (!empty($animal['vacunas']) && $animal['vacunas'] !== 'N/E') {
                    $fields['Vacunas'] = $animal['vacunas'];
                }
                if (!empty($animal['info_adicional']) && $animal['info_adicional'] !== 'N/E') {
                    $fields['Info adicional'] = $animal['info_adicional'];
                }
                foreach ($fields as $label => $value): ?>
                    <div class="vet-item">
                        <span class="material-icons<?= $value ? '' : ' cancel' ?>">
                            <?= $value ? 'check_circle' : 'cancel' ?>
                        </span>
                        <span class="vet-label"><?= $label ?></span>
                        <?php if ($value && !in_array($label, ['Microchip', 'Castración'])): ?>
                            <span class="vet-value"><?= html_escape($value) ?></span>
                        <?php endif ?>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['usuario_id']) && in_array($animal['estado'], ['Disponible', 'En proceso'])): ?>
        <div class="solicitud-adopcion">
            <?php if ($mensaje): ?>
                <p class="mensaje"><?= html_escape($mensaje) ?></p>
            <?php else: ?>
                <form method="post">
                    <button type="submit" class="button aceptar">Solicitar Adopción</button>
                </form>
            <?php endif; ?>
        </div>
    <?php elseif (!isset($_SESSION['usuario_id'])): ?>
        <p class="mensaje login-requerido">Inicia sesión para solicitar la adopción de este animal.</p>
    <?php elseif (!in_array($animal['estado'], ['Disponible', 'En proceso'])): ?>
        <p class="mensaje no-disponible">Este animal ya no está disponible para adopción.</p>
    <?php endif; ?>
</main>

<?php include '../includes/contacto-ctp.php'; ?>

<?php include '../includes/footer-web.php'; ?>