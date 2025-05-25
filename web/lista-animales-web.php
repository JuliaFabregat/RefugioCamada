<?php

declare(strict_types=1);
require '../includes/database-connection.php';
require '../includes/functions.php';

// Parámetros de filtro y paginación
$selectedSpecies = $_GET['especie'] ?? '';
$searchName      = trim($_GET['nombre']  ?? '');
$page            = max(1, (int)($_GET['p'] ?? 1));
$perPage         = 8; // Animales por página

// Obtener especies
$sqlEspeces = "SELECT id, especie FROM especies ORDER BY especie";
$especies   = pdo($pdo, $sqlEspeces)->fetchAll();

// Validar especie
if ($selectedSpecies !== '' && !ctype_digit($selectedSpecies)) {
    $selectedSpecies = '';
}

// WHERE dinámico
$conds  = [];
$params = [];
if ($selectedSpecies !== '') {
    $conds[]         = 'a.especie_id = :esp';
    $params['esp']   = $selectedSpecies;
}
if ($searchName !== '') {
    $conds[]            = 'a.nombre LIKE :nombre';
    $params['nombre']   = '%' . $searchName . '%';
}
$where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';

// Calcular total y páginas
$countSql = "SELECT COUNT(*) FROM animales a $where";
$total    = pdo($pdo, $countSql, $params)->fetchColumn();
$pages    = (int)ceil($total / $perPage);
$offset   = ($page - 1) * $perPage;

// Consulta paginación
$sql = "
  SELECT 
    a.id, a.nombre, a.edad, a.genero, a.joined,
    e.especie, r.nombre AS raza,
    i.imagen AS image_file, i.alt AS image_alt
  FROM animales a
  JOIN especies e ON a.especie_id = e.id
  JOIN raza    r ON a.raza_id    = r.id
  LEFT JOIN imagenes i ON a.imagen_id = i.id
  $where
  ORDER BY a.joined DESC
  LIMIT :lim OFFSET :off
";
$params['lim'] = $perPage;
$params['off'] = $offset;
$animales      = pdo($pdo, $sql, $params)->fetchAll();

// Datos de la página
$title       = 'Adopta un animal';
$description = 'Descubre a nuestros peludos disponibles';
$section     = 'webAnimales';
?>




<!-- HTML -->
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
                            <option value="<?= $esp['id'] ?>"
                                <?= $selectedSpecies == $esp['id'] ? 'selected' : '' ?>>
                                <?= html_escape($esp['especie']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="campo">
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" id="nombre"
                        value="<?= html_escape($searchName) ?>"
                        placeholder="Buscar por nombre…">
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
                        <a href="?<?= http_build_query([
                                        'especie' => $selectedSpecies,
                                        'nombre' => $searchName,
                                        'p'      => $p
                                    ]) ?>"
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

<!-- Scripts -->
<script src="../js/web/lista-animales-web.js" defer></script>