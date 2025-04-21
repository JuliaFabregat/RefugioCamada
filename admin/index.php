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

// Consulta para obtener los animales ordenados por más recientes
$sql = "SELECT 
            a.id, 
            a.nombre, 
            r.nombre AS raza,
            a.edad, 
            a.genero, 
            a.joined,
            e.especie AS especie,
            i.imagen AS image_file,
            i.alt AS image_alt
        FROM animales AS a
        JOIN especies AS e ON a.especie_id = e.id
        JOIN raza AS r ON a.raza_id = r.id
        LEFT JOIN imagenes AS i ON a.imagen_id = i.id
        ORDER BY a.joined DESC;";
        // LIMIT 6";

$animales = pdo($pdo, $sql)->fetchAll();

// Datos
$section = 'Inicio';
$title = 'Refugio de Animales';
$description = 'Últimos animales rescatados en nuestro refugio';
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<div class="divUltimosAnimales">
    <h1 class="h1_title">Últimos animales rescatados</h1>
</div>

<main class="container grid" id="content">
    <?php foreach ($animales as $animal) { ?>
        <article class="summary">
            <a href="animal.php?id=<?= $animal['id'] ?>">
                <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.png') ?>"
                     alt="<?= html_escape($animal['image_alt'] ?? 'Imagen de animal') ?>">
                <h2><?= html_escape($animal['nombre']) ?></h2>
                <p>
                    <b>Especie:</b> <?= html_escape($animal['especie']) ?><br>
                    <b>Raza:</b> <?= html_escape($animal['raza'] ?? 'Desconocida') ?><br>
                    <b>Edad:</b> <?= html_escape($animal['edad'] ?? 'N/A') ?><br>
                    <b>Género:</b> <?= html_escape($animal['genero']) ?>
                </p>
            </a>
        </article>
    <?php } ?>
</main>

<?php include '../includes/footer.php'; ?>