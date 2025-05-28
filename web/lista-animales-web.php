<?php
declare(strict_types=1);
require '../includes/database-connection.php';
require '../includes/functions.php';
require_once '../models/Animal.php';

// Parámetros
$selectedSpecies = $_GET['especie'] ?? '';
$searchName = trim($_GET['nombre'] ?? '');
$page = max(1, (int)($_GET['p'] ?? 1));
$perPage = 8;

// Obtener especies para filtro
$especies = Animal::obtenerEspecies($pdo);

// Obtener animales filtrados con paginación
$result = Animal::listarConFiltros($pdo, $selectedSpecies, $searchName, $page, $perPage);

$animales = $result['animales'];
$total = $result['total'];
$pages = $result['pages'];

// Datos para la vista
$title = html_escape('Adopta - Refugio Camada');
$description = html_escape('Descubre a nuestros peludos disponibles');
$section = 'webAnimales';
?>

<?php include '../includes/header-web.php'; ?>

<link rel="stylesheet" href="../css/web/lista-animales-web.css">
<link rel="stylesheet" href="../css/admin/lista-animales.css">

<main>
    <div class="container">
        <section class="lista-animales">
            <h1>Animales en Adopción</h1>

            <form id="filtroForm" class="divFiltro">
                <div class="campo">
                    <label for="especie">Especie:</label>
                    <select name="especie" id="especie">
                        <option value="">Todas</option>
                        <?php foreach ($especies as $esp): ?>
                            <option value="<?= $esp['id'] ?>" <?= $selectedSpecies == $esp['id'] ? 'selected' : '' ?>>
                                <?= html_escape($esp['especie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre" value="<?= html_escape($searchName) ?>" placeholder="Buscar por nombre…">
                </div>
            </form>

            <div class="grid-tarjetas-animales">
                <?php if (!$animales): ?>
                    <p>No hay resultados.</p>
                <?php endif; ?>

                <?php foreach ($animales as $a): ?>
                    <article class="tarjeta-animal">
                        <a href="animal-web.php?id=<?= $a['id'] ?>">
                            <img src="../uploads/<?= html_escape($a['image_file'] ?: 'blank.jpg') ?>"
                                alt="<?= html_escape($a['image_alt'] ?: $a['nombre']) ?>">
                            <h2><?= html_escape($a['nombre']) ?></h2>
                            <p><strong>Especie:</strong> <?= html_escape($a['especie']) ?></p>
                            <p><strong>Raza:</strong> <?= html_escape($a['raza']) ?></p>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php if ($pages > 1): ?>
                <nav class="paginacion">
                    <?php for ($p = 1; $p <= $pages; $p++): ?>
                        <a href="?<?= http_build_query(['especie' => $selectedSpecies, 'nombre' => $searchName, 'p' => $p]) ?>"
                            class="<?= $p === $page ? 'active' : '' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </nav>
            <?php endif; ?>
        </section>
    </div>
</main>

<?php include '../includes/footer-web.php'; ?>

<script src="../js/web/lista-animales-web.js" defer></script>
