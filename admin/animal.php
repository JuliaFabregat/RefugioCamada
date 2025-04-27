<?php
declare(strict_types = 1);
require '../includes/database-connection.php';
require '../includes/functions.php';
// Sesión
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Validar ID del animal
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    include 'page-not-found.php';
    exit;
}

// Obtener datos del animal
$sql = "SELECT 
            a.id, 
            a.nombre, 
            a.edad, 
            a.genero, 
            a.joined,
            a.estado,
            r.nombre AS raza,
            e.especie,
            e.descripcion AS especie_descripcion,
            i.imagen AS image_file,
            i.alt AS image_alt,
            v.microchip,
            v.castracion,
            v.vacunas,
            v.info_adicional
        FROM animales AS a
        LEFT JOIN raza AS r ON a.raza_id = r.id
        LEFT JOIN especies AS e ON r.especie_id = e.id
        LEFT JOIN imagenes AS i ON a.imagen_id = i.id
        LEFT JOIN vet_data AS v ON a.vet_data_id = v.id
        WHERE a.id = :id";

$animal = pdo($pdo, $sql, ['id' => $id])->fetch();

if (!$animal) {
    include 'page-not-found.php';
    exit;
}

// Especies
$sql_especies = "SELECT id, especie FROM Especies";
$especies_nav = pdo($pdo, $sql_especies)->fetchAll();

// Datos
$title = html_escape("Información de {$animal['nombre']}");
$description = "Detalles de {$animal['nombre']} - {$animal['raza']}{$animal['especie']}";
$section = "descripcionAnimal";
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS de animal.php -->
<link rel="stylesheet" href="../css/admin/animal.css">

<main class="container" id="content">
    <div class="detalles-animal">

        <!-- IMAGEN -->
        <section class="animal-imagen">
            <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.jpg') ?>"
                alt="<?= html_escape($animal['image_alt'] ?? 'Imagen de animal') ?>">
                
            <!-- BOTÓN DE EDICIÓN -->
            <div class="animal-acciones">
                <a href="editar-animal.php?id=<?= $animal['id'] ?>" class="btn-edit">
                    <span class="material-icons" aria-hidden="true">edit</span>
                </a>
                <a href="eliminar-animal.php?id=<?= $animal['id'] ?>" class="btn-delete"
                    onclick="return confirm('¿Eliminar a <?= html_escape($animal['nombre']) ?>?');">
                    <span class="material-icons" aria-hidden="true">delete</span>
                </a>
            </div>
        </section>

        <!-- INFO -->
        <section class="animal-info details">
            <h1><?= html_escape($animal['nombre']) ?></h1>
            
            <ul class="metadata animal-datos">
                <li><strong>Estado:</strong> <?= html_escape($animal['estado'] ?? 'No especificado') ?></li>
                <li><strong>Especie:</strong> <?= html_escape($animal['especie']) ?></li>
                <li><strong>Raza:</strong> <?= html_escape($animal['raza'] ?? 'Desconocida') ?></li>
                <li><strong>Edad:</strong> <?= html_escape($animal['edad'] ?? 'N/A') ?></li>
                <li><strong>Género:</strong> <?= html_escape($animal['genero']) ?></li>
                <li><strong>Rescatado:</strong> <?= format_date($animal['joined']) ?></li>
            </ul>
            
            <!-- <div class="descripcion">
                <h2>Dato curioso sobre la especie</h2>
                <p><?= html_escape($animal['especie_descripcion'] ?? 'No hay dato curioso disponible :(') ?></p>
            </div> -->
            
            <?php if ($animal['microchip'] !== null || $animal['castracion'] !== null || $animal['vacunas'] || $animal['info_adicional']) : ?>
                <div class="descripcion">
                    <h2>Ficha Veterinaria</h2>
                    <ul class="animal-datos">
                        <li><strong>Microchip:</strong> <?= $animal['microchip'] ? 'Sí' : 'No' ?></li>
                        <li><strong>Castración:</strong> <?= $animal['castracion'] ? 'Sí' : 'No' ?></li>
                        <li><strong>Vacunas:</strong> <?= html_escape($animal['vacunas'] ?? 'N/E') ?></li>
                        <li><strong>Información adicional:</strong> <?= html_escape($animal['info_adicional'] ?? 'N/E') ?></li>
                        </ul>
                </div>
            <?php endif; ?>
            
        </section>
    </div>
</main>


<?php include '../includes/footer.php'; ?>