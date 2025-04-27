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
$searchName = trim($_GET['nombre'] ?? '');

// Validar especie
if ($selectedSpecies !== '' && !ctype_digit($selectedSpecies)) {
    $selectedSpecies = '';
}

// Consulta Base
$sql = "SELECT 
            a.id, 
            a.nombre, 
            a.edad, 
            e.especie,
            i.imagen AS image_file
        FROM animales AS a
        JOIN especies AS e ON a.especie_id = e.id
        LEFT JOIN imagenes AS i ON a.imagen_id = i.id";

// Condiciones dinámicas
$conditions = [];
$params = [];

if ($selectedSpecies !== '') {
    $conditions[] = "e.id = :especie_id";
    $params['especie_id'] = $selectedSpecies;
}

if ($searchName !== '') {
    $conditions[] = "a.nombre LIKE :nombre";
    $params['nombre'] = '%' . $searchName . '%';
}

if ($conditions) {
    $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= " ORDER BY a.nombre ASC";

// Ejecutar consulta
$animales = pdo($pdo, $sql, $params)->fetchAll();

// Consulta para especies
$sqlEspecies = "SELECT id, especie FROM especies ORDER BY especie ASC";
$especies = pdo($pdo, $sqlEspecies)->fetchAll();

// Datos de la página
$title = 'Gestión de Animales';
$description = 'Lista de todos los animales del refugio';
$section = 'listaAnimales';
?>

<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/admin/lista-animales.css">

<main class="container" id="content">

    <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'modificado') : ?>
        <div id="mensaje-exito" class="alert success">
            ✅ Animal modificado correctamente.
        </div>
        <script>
            setTimeout(function() {
                const mensajeExito = document.getElementById('mensaje-exito');
                if (mensajeExito) {
                    mensajeExito.style.opacity = '0';
                    setTimeout(() => mensajeExito.remove(), 500);
                }
            }, 3000);
        </script>
    <?php endif; ?>

    <h1>Gestión de Animales</h1>
    
    <!-- Formulario de filtros -->
    <form method="GET" action="lista-animales.php" id="filtroForm">
        <label for="especie">Filtrar por especie:</label>
        <select name="especie" id="especie">
            <option value="">Todas</option>
            <?php foreach ($especies as $especieOption) { ?>
                <option value="<?= $especieOption['id'] ?>" <?= ($selectedSpecies == $especieOption['id']) ? 'selected' : '' ?>>
                    <?= html_escape($especieOption['especie']) ?>
                </option>
            <?php } ?>
        </select>

        <label for="nombre">Buscar por nombre:</label>
        <input type="text" name="nombre" id="nombre" value="<?= html_escape($searchName) ?>" placeholder="Introduce un nombre...">
    </form>

    <!-- Tabla de Animales -->
    <table class="table">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Especie</th>
                <th>Edad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($animales as $animal): ?>
            <tr onclick="window.location.href='animal.php?id=<?= $animal['id'] ?>';" style="cursor: pointer;">
                <td>
                    <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.jpg') ?>" 
                         alt="<?= html_escape($animal['nombre']) ?>" class="thumbnail">
                </td>
                <td><?= html_escape($animal['nombre']) ?></td>
                <td><?= html_escape($animal['especie']) ?></td>
                <td><?= html_escape($animal['edad'] ?? 'N/A') ?></td>
                <td class="animal-acciones">
                    <a href="editar-animal.php?id=<?= $animal['id'] ?>" class="btn-edit" title="Editar <?= html_escape($animal['nombre']) ?>">
                        <span class="material-icons" aria-hidden="true">edit</span>
                    </a>
                    <a href="eliminar-animal.php?id=<?= $animal['id'] ?>" class="btn-delete"
                        onclick="return confirm('¿Eliminar a <?= html_escape($animal['nombre']) ?>?');"
                        title="Eliminar <?= html_escape($animal['nombre']) ?>">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="firstConfirmBtn">Sí, eliminar</button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="finalConfirmBtn">Sí, eliminar permanentemente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../js/eliminar-animal.js"></script>
    <script src="../js/lista-animales.js"></script>

</main>

<?php include '../includes/footer.php'; ?>
