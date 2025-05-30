<?php
declare(strict_types=1);
require __DIR__ . '/../includes/admin-auth.php';
require_once '../models/Conexion.php';
require_once '../models/Animal.php';
require_once '../includes/functions.php';

$pdo = Conexion::obtenerConexion();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    include 'page-not-found.php';
    exit;
}

$animal = Animal::obtenerPorId($pdo, $id);
if (!$animal) {
    include 'page-not-found.php';
    exit;
}

// Datos para la plantilla
$title = html_escape("Info de {$animal['nombre']}");
$description = html_escape("Detalles de {$animal['nombre']} - {$animal['raza']}{$animal['especie']}");
$section = "descripcionAnimal";
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/web/animal-web.css">

<main>
    <div class="container">
        <div class="detalles-animal">
            <div class="animal-imagen">
                <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.jpg') ?>"
                     alt="<?= html_escape($animal['image_alt'] ?? 'Imagen de animal') ?>">
            </div>

            <div class="animal-info">
                <div class="animal-acciones">
                    <a href="editar-animal.php?id=<?= $animal['id'] ?>" class="btn-edit">
                        <span class="material-icons" aria-hidden="true">edit</span>
                    </a>
                    <a href="#" class="btn-delete"
                       data-id="<?= $animal['id'] ?>"
                       data-nombre="<?= html_escape($animal['nombre']) ?>"
                       title="Eliminar">
                        <span class="material-icons" aria-hidden="true">delete</span>
                    </a>
                </div>

                <h1><?= html_escape($animal['nombre']) ?></h1>

                <ul class="animal-datos">
                    <li><strong>Estado:</strong> <?= html_escape($animal['estado'] ?? 'No especificado') ?></li>
                    <li><strong>Especie:</strong> <?= html_escape($animal['especie']) ?></li>
                    <li><strong>Raza:</strong> <?= html_escape($animal['raza'] ?? 'Desconocida') ?></li>
                    <li><strong>Edad:</strong> <?= html_escape($animal['edad'] ?? 'N/A') ?></li>
                    <li><strong>Género:</strong> <?= html_escape($animal['genero']) ?></li>
                    <li><strong>Rescatado:</strong> <?= format_date($animal['joined']) ?></li>
                </ul>

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

        <!-- Modal eliminar -->
        <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmar eliminación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Eliminar a <span id="nombreAnimalModal"></span>?
                        <p>Esta acción no se puede deshacer.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary cancelar" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger aceptar" id="confirmarEliminar">Sí, eliminar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../includes/footer.php'; ?>
<script src="../js/admin/animal.js" defer></script>
