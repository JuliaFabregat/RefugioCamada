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

// Recoger filtros
$selectedSpecies = $_GET['especie'] ?? '';
$searchName     = trim($_GET['nombre']  ?? '');
$selectedState  = $_GET['estado']  ?? '';

// Validar especie y estado
if ($selectedSpecies !== '' && !ctype_digit($selectedSpecies)) {
    $selectedSpecies = '';
}
$validStates = ['Disponible', 'En proceso', 'Adoptado'];
if ($selectedState !== '' && !in_array($selectedState, $validStates, true)) {
    $selectedState = '';
}

// Base SQL (ahora incluye a.estado)
$sql = "SELECT 
          a.id,
          a.nombre,
          a.edad,
          a.estado,
          e.especie,
          i.imagen AS image_file
        FROM animales AS a
        JOIN especies AS e ON a.especie_id = e.id
        LEFT JOIN imagenes AS i ON a.imagen_id = i.id";

$conditions = [];
$params     = [];

if ($selectedSpecies !== '') {
    $conditions[]         = "e.id = :especie_id";
    $params['especie_id'] = $selectedSpecies;
}
if ($searchName !== '') {
    $conditions[]      = "a.nombre LIKE :nombre";
    $params['nombre']  = '%' . $searchName . '%';
}
if ($selectedState !== '') {
    $conditions[]      = "a.estado = :estado";
    $params['estado']  = $selectedState;
}
if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}
$sql .= " ORDER BY a.nombre ASC";

$animales = pdo($pdo, $sql, $params)->fetchAll();

// Para el select de especies
$sqlEspecies = "SELECT id, especie FROM especies ORDER BY especie ASC";
$especies    = pdo($pdo, $sqlEspecies)->fetchAll();

// Datos de la página
$title       = 'Gestión de Animales';
$description = 'Lista de todos los animales del refugio';
$section     = 'listaAnimales';
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/admin/lista-animales.css">

<main class="container" id="content">

    <!-- Confirmación de animal editado -->
    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'modificado') : ?>
        <div id="mensaje-exito" class="alert success">
            ✅ Animal modificado correctamente.
        </div>
        <!-- Independizar para reutilizar -->
        <script>
            setTimeout(() => {
                const m = document.getElementById('mensaje-exito');
                if (m) {
                    m.style.opacity = '0';
                    setTimeout(() => m.remove(), 500);
                }
            }, 3000);
        </script>
    <?php endif; ?>

    <!-- Confirmación de animal agregado -->
    <?php if (isset($_GET['success'])): ?>
        <div id="mensaje-exito" class="alert success">
            ✅ Animal agregado correctamente.
        </div>
        <script>
            setTimeout(() => {
                const m = document.getElementById('mensaje-exito');
                if (m) {
                    m.style.opacity = '0';
                    setTimeout(() => m.remove(), 500);
                }
            }, 3000);
        </script>
    <?php endif; ?>

    <h1>Gestión de Animales</h1>

    <!-- Formulario de filtros -->
    <form method="GET" action="lista-animales.php" id="filtroForm" class="divFiltro">

        <div class="campo">
            <label for="especie">Especie:</label>
            <select name="especie" id="especie">
                <option value="">Todas</option>
                <?php foreach ($especies as $opt): ?>
                    <option value="<?= $opt['id'] ?>"
                        <?= $selectedSpecies == $opt['id'] ? 'selected' : '' ?>>
                        <?= html_escape($opt['especie']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="estado">Estado:</label>
            <select name="estado" id="estado">
                <option value="">Todos</option>
                <?php foreach ($validStates as $st): ?>
                    <option value="<?= $st ?>"
                        <?= $selectedState === $st ? 'selected' : '' ?>>
                        <?= $st ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre"
                value="<?= html_escape($searchName) ?>"
                placeholder="Buscar…">
        </div>

    </form>

    <!-- Tabla -->
    <table class="table">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Edad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($animales as $animal): ?>
                <tr onclick="handleRowClick(event, <?= $animal['id'] ?>)">
                    <td>
                        <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.jpg') ?>"
                            alt="<?= html_escape($animal['nombre']) ?>" class="thumbnail">
                    </td>
                    <td><?= html_escape($animal['nombre']) ?></td>
                    <td><?= html_escape($animal['especie']) ?></td>
                    <td><?= html_escape($animal['edad'] ?? 'N/A') ?></td>
                    <td>
                        <span class="status status-<?= strtolower(str_replace(' ', '', $animal['estado'])) ?>">
                            <?= html_escape($animal['estado']) ?>
                        </span>
                    </td>
                    <td class="animal-acciones">
                        <a href="editar-animal.php?id=<?= $animal['id'] ?>" class="btn-edit" title="Editar">
                            <span class="material-icons" aria-hidden="true">edit</span>
                        </a>
                        <a href="#" class="btn-delete"
                            data-id="<?= $animal['id'] ?>"
                            data-nombre="<?= html_escape($animal['nombre']) ?>"
                            title="Eliminar">
                            <span class="material-icons" aria-hidden="true">delete</span>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- MODALES DE CONFIRMACIÓN -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteLabel">Confirmar eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar a <span id="animalNombreModal"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="button cancelar" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="button aceptar" id="firstConfirmBtn">Sí, eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="finalConfirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="finalConfirmDeleteLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="finalConfirmDeleteLabel">Confirmación final</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Esta acción es irreversible. ¿Realmente deseas eliminar a <span id="animalNombreModalFinal"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="button cancelar" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="button aceptar" id="finalConfirmBtn">Sí, eliminar permanentemente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../js/eliminar-animal.js" defer></script>
    <script src="../js/lista-animales.js" defer></script>

</main>

<?php include '../includes/footer.php'; ?>