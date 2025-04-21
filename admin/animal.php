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
$title = html_escape($animal['nombre']);
$description = "Detalles de {$animal['nombre']} - {$animal['especie']}";
$section = "descripcionAnimal";
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<main class="container" id="content">
    <div class="animal-detail">
        <section class="image">
            <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.jpg') ?>" 
                 alt="<?= html_escape($animal['image_alt'] ?? 'Imagen de animal') ?>">
        </section>
        <section class="details">

            <h1><?= html_escape($animal['nombre']) ?></h1>
            
            <div class="metadata">
                <p><strong>Estado:</strong> <?= html_escape($animal['estado'] ?? 'No especificado') ?></p>
                <p><strong>Especie:</strong> <?= html_escape($animal['especie']) ?></p>
                <p><strong>Raza:</strong> <?= html_escape($animal['raza'] ?? 'Desconocida') ?></p>
                <p><strong>Edad:</strong> <?= html_escape($animal['edad'] ?? 'N/A') ?></p>
                <p><strong>Género:</strong> <?= html_escape($animal['genero']) ?></p>
                <p><strong>Rescatado:</strong> <?= format_date($animal['joined']) ?></p>
            </div>

            <div class="description">
                <h3><b>Dato curioso sobre la especie</b></h3>
                <p><?= html_escape($animal['especie_descripcion'] ?? 'No hay dato curioso disponible :(') ?></p>
            </div>
            
            <?php if ($animal['microchip'] !== null || $animal['castracion'] !== null || $animal['vacunas'] || $animal['info_adicional']) : ?>
                
                <div class="description">
                    
                    <h3><b>Ficha Veterinaria</b></h3>
                    <div class="metadata">
                        <p><strong>Microchip:</strong> <?= $animal['microchip'] ? 'Sí' : 'No' ?></p>
                        <p><strong>Castración:</strong> <?= $animal['castracion'] ? 'Sí' : 'No' ?></p>
                        <p><strong>Vacunas:</strong> <?= html_escape($animal['vacunas'] ?? 'N/E') ?></p>
                        <p><strong>Información adicional:</strong> <?= html_escape($animal['info_adicional'] ?? 'N/E') ?></p>
                    </div>

                </div>
            <?php endif; ?>

        </section>
    </div>
</main>

<?php include '../includes/footer.php'; ?>