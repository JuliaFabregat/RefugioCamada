<?php
declare(strict_types=1);
session_start();
require '../includes/database-connection.php';
require '../includes/functions.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    include '../web/page-not-found.php';
    exit;
}

// Obtener datos del animal
$sql = "SELECT 
          a.*,
          r.nombre AS raza,
          e.especie, e.descripcion AS especie_descripcion,
          i.imagen AS image_file, i.alt AS image_alt,
          v.microchip, v.castracion, v.vacunas, v.info_adicional
        FROM animales a
        JOIN especies e ON a.especie_id = e.id
        JOIN raza r ON a.raza_id = r.id
        LEFT JOIN imagenes i ON a.imagen_id = i.id
        LEFT JOIN vet_data v ON a.vet_data_id = v.id
        WHERE a.id = :id";
$animal = pdo($pdo, $sql, ['id' => $id])->fetch();
if (!$animal) {
    include '../web/page-not-found.php';
    exit;
}

// Procesar solicitud si se envió
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    // Verificar que no exista solicitud aceptada para este animal
    $sqlAceptada = "SELECT COUNT(*) FROM solicitudes_adopcion WHERE id_animal = :aid AND resolucion = 'Aceptada'";
    $tieneAceptada = pdo($pdo, $sqlAceptada, ['aid' => $id])->fetchColumn();

    if ($tieneAceptada) {
        $mensaje = 'Este animal ya fue adoptado, no puedes enviar otra solicitud.';
    } else if ($animal['estado'] === 'Disponible' || $animal['estado'] === 'En proceso') {
        $sql = "SELECT COUNT(*) FROM solicitudes_adopcion
                WHERE id_usuario = :uid AND id_animal = :aid";
        $params = ['uid' => $_SESSION['user_id'], 'aid' => $id];
        $yaSolicitada = pdo($pdo, $sql, $params)->fetchColumn();

        if ($yaSolicitada) {
            $mensaje = 'Ya has enviado una solicitud para este animal.';
        } else {
            $sql = "INSERT INTO solicitudes_adopcion (id_usuario, id_animal, fecha, resolucion)
                    VALUES (:uid, :aid, NOW(), 'En proceso')";
            pdo($pdo, $sql, $params);
            $mensaje = 'Solicitud enviada correctamente. Te contactaremos pronto.';
        }
    } else {
        $mensaje = 'Este animal ya no está disponible para adopción.';
    }
}

// Anterior / siguiente
$prev = pdo($pdo, "SELECT id FROM animales WHERE id < :id ORDER BY id DESC LIMIT 1", ['id' => $id])->fetchColumn();
$next = pdo($pdo, "SELECT id FROM animales WHERE id > :id ORDER BY id ASC  LIMIT 1", ['id' => $id])->fetchColumn();

// Datos
$title       = html_escape($animal['nombre']);
$description = "Detalles de {$animal['nombre']}";
$section     = 'descripcionAnimal';
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<link rel="stylesheet" href="../css/web/animal-web.css">

<main class="container">
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

    <?php if (isset($_SESSION['user_id']) && $animal['estado'] === 'Disponible'): ?>
        <div class="solicitud-adopcion">
            <?php if ($mensaje): ?>
                <p class="mensaje"><?= html_escape($mensaje) ?></p>
            <?php else: ?>
                <form method="post">
                    <button type="submit" class="button aceptar">Solicitar Adopción</button>
                </form>
            <?php endif; ?>
        </div>
    <?php elseif (!isset($_SESSION['user_id'])): ?>
        <p class="mensaje login-requerido">Inicia sesión para solicitar la adopción de este animal.</p>
    <?php elseif ($animal['estado'] !== 'Disponible'): ?>
        <p class="mensaje no-disponible">Este animal ya no está disponible para adopción.</p>
    <?php endif; ?>
</main>

<section class="animal-cta">
    <div class="animal-cta__overlay"></div>
    <div class="animal-cta__contenido">
        <h2>¿Tienes alguna duda?</h2>
        <a href="contacto.php" class="button aceptar">Contáctanos</a>
    </div>
</section>

<?php include '../includes/footer-web.php'; ?>
