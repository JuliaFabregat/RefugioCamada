<?php

declare(strict_types=1);
require '../includes/database-connection.php';
require '../includes/functions.php';

// Sesión
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Consulta: últimos 3 animales refugiados
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
        ORDER BY a.joined DESC
        LIMIT 4;";

$ultimosAnimales = pdo($pdo, $sql)->fetchAll();

// Consulta: estadísticas del refugio
$sqlTotal = "SELECT 
                COUNT(*) AS total,
                SUM(estado = 'Disponible') AS disponibles,
                SUM(estado = 'En proceso') AS en_proceso,
                SUM(estado = 'Adoptado') AS adoptados
            FROM animales;";

$estadisticas = pdo($pdo, $sqlTotal)->fetch();

// Datos
$section = 'Inicio';
$title = 'Refugio de Animales';
$description = 'Inicio del Administrador';
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/admin/index.css">

<main>
    <div class="admin-dashboard container">
        <section class="dashboard-izq">
            <h1>Bienvenid@ Administrador de la Camada</h1>

            <h2>Últimos animales recogidos</h2>

            <div class="ultimos-animales">
                <?php foreach ($ultimosAnimales as $animal) { ?>
                    <div class="tarjeta-animal">
                        <a href="animal.php?id=<?= $animal['id'] ?>">
                            <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.png') ?>"
                                alt="<?= html_escape($animal['image_alt'] ?? 'Imagen de animal') ?>">
                            <h3><?= html_escape($animal['nombre']) ?></h3>
                            <p><b>Especie:</b> <?= html_escape($animal['especie']) ?></p>
                            <p><b>Raza:</b> <?= html_escape($animal['raza'] ?? 'Desconocida') ?></p>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <section class="refugio-info">
                <h2>Información del Refugio</h2>
                <ul>
                    <li><strong>Dirección:</strong> Avenida Castro del Río 15, Baena</li>
                    <li><strong>Email:</strong> contacto@refugio-camada.es</li>
                    <li><strong>Teléfono:</strong> +34 600 123 456</li>
                </ul>
            </section>
        </section>

        <aside class="dashboard-der">
            <h2>Info de la Camada</h2>
            <ul>
                <li><b>Total de animales:</b> <?= $estadisticas['total'] ?></li>
                <li><b>Disponibles:</b> <?= $estadisticas['disponibles'] ?></li>
                <li><b>En proceso:</b> <?= $estadisticas['en_proceso'] ?></li>
                <li><b>Adoptados:</b> <?= $estadisticas['adoptados'] ?></li>
            </ul>
        </aside>
    </div>

</main>

<?php include '../includes/footer.php'; ?>