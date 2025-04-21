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

// Validar id
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirect('lista-animales.php');
}

/// Obtenemos los datos del animal con la consulta
$sql = "SELECT 
            a.nombre, 
            a.edad, 
            a.estado, 
            a.raza_id,
            a.vet_data_id,
            v.microchip, 
            v.castracion, 
            v.vacunas, 
            v.info_adicional, 
            e.id AS especie_id,
            r.nombre AS raza_nombre
        FROM animales AS a
        LEFT JOIN vet_data AS v ON a.vet_data_id = v.id
        LEFT JOIN raza AS r ON a.raza_id = r.id
        LEFT JOIN especies AS e ON r.especie_id = e.id
        WHERE a.id = :id";
$animal = pdo($pdo, $sql, ['id' => $id])->fetch();

if (!$animal) {
    redirect('lista-animales.php');
}

// Obtener razas de la especie del animal
$sqlRazas = "SELECT id, nombre FROM raza WHERE especie_id = :especie_id ORDER BY nombre ASC";
$razas = pdo($pdo, $sqlRazas, ['especie_id' => $animal['especie_id']])->fetchAll();

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $edad = filter_input(INPUT_POST, 'edad', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $raza_id = filter_input(INPUT_POST, 'raza_id', FILTER_VALIDATE_INT);
    $raza_nombre = filter_input(INPUT_POST, 'raza_nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Nuevo campo para editar el nombre de la raza

    // Actualizar el animal
    $sqlAnimal = "UPDATE animales SET nombre = :nombre, edad = :edad, estado = :estado, raza_id = :raza_id WHERE id = :id";
    pdo($pdo, $sqlAnimal, [
        'nombre' => $nombre,
        'edad' => $edad ?: null,
        'estado' => $estado,
        'raza_id' => $raza_id,
        'id' => $id
    ]);

    // Actualizar el nombre de la raza si se modificó
    if ($raza_nombre) {
        $sqlRazaUpdate = "UPDATE raza SET nombre = :nombre WHERE id = :raza_id";
        pdo($pdo, $sqlRazaUpdate, [
            'nombre' => $raza_nombre,
            'raza_id' => $raza_id
        ]);
    }

    // Actualizar la ficha veterinaria
    $microchip = isset($_POST['microchip']) ? 1 : 0;
    $castracion = isset($_POST['castracion']) ? 1 : 0;
    $vacunas = filter_input(INPUT_POST, 'vacunas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $info_adicional = filter_input(INPUT_POST, 'info_adicional', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Verificamos si el animal ya tiene una ficha veterinaria asignada
    if ($animal['vet_data_id']) {
        // Si ya tiene ficha veterinaria, actualizarla
        $sqlVetUpdate = "UPDATE vet_data SET microchip = :microchip, castracion = :castracion, vacunas = :vacunas, info_adicional = :info_adicional WHERE id = :vet_data_id";
        pdo($pdo, $sqlVetUpdate, [
            'microchip' => $microchip,
            'castracion' => $castracion,
            'vacunas' => $vacunas,
            'info_adicional' => $info_adicional,
            'vet_data_id' => $animal['vet_data_id']
        ]);
    } else {
        // Si por alguna razón no tiene ficha veterinaria (caso no esperado), crear una nueva
        $sqlVetInsert = "INSERT INTO vet_data (animal_id, microchip, castracion, vacunas, info_adicional) VALUES (:animal_id, :microchip, :castracion, :vacunas, :info_adicional)";
        pdo($pdo, $sqlVetInsert, [
            'animal_id' => $id,
            'microchip' => $microchip,
            'castracion' => $castracion,
            'vacunas' => $vacunas,
            'info_adicional' => $info_adicional
        ]);
    }

    redirect('lista-animales.php?mensaje=modificado');
}

// Datos
$title = 'Editar Animal';
$description = 'Formulario para editar datos del animal';
$section = 'listaAnimales';
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<main class="container" id="content">

    <h1>Editar Animal</h1>

    <form method="POST">

        <div class="field">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= html_escape($animal['nombre'] ?? '') ?>" required>
        </div>

        <div class="field">
            <label>Edad:</label>
            <input type="text" name="edad" value="<?= $animal['edad'] ?? '' ?>">
        </div>

        <div class="field">
            <label>Estado:</label>
            <select name="estado" required>
                <option value="Disponible" <?= ($animal['estado'] === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                <option value="En proceso" <?= ($animal['estado'] === 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                <option value="Adoptado" <?= ($animal['estado'] === 'Adoptado') ? 'selected' : '' ?>>Adoptado</option>
            </select>
        </div>

        <div class="field">
            <label>Raza:</label>
            <select name="raza_id" id="raza_id" required>
                <?php foreach ($razas as $raza) { ?>
                    <option value="<?= $raza['id'] ?>" <?= ($raza['id'] == $animal['raza_id']) ? 'selected' : '' ?>>
                        <?= html_escape($raza['nombre']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="field">
            <label>Editar nombre de la raza:</label>
            <input type="text" name="raza_nombre" id="raza_nombre" value="<?= html_escape($animal['raza_nombre'] ?? '') ?>">
        </div>

        <br>

        <h3>Ficha Veterinaria</h3>

        <div class="field">
            <label>Microchip:</label>
            <input type="checkbox" name="microchip" <?= $animal['microchip'] ? 'checked' : '' ?>>
        </div>
        <div class="field">
            <label>Castración:</label>
            <input type="checkbox" name="castracion" <?= $animal['castracion'] ? 'checked' : '' ?>>
        </div>
        <div class="field">
            <label>Vacunas:</label>
            <input type="text" name="vacunas" value="<?= html_escape($animal['vacunas'] ?? '') ?>">
        </div>
        <div class="field">
            <label>Información adicional:</label>
            <textarea name="info_adicional"><?= html_escape($animal['info_adicional'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="button secondary">Guardar cambios</button>
        <a href="lista-animales.php" class="button secondary">Cancelar</a>

    </form>

    <!-- Script -->
    <script src="../js/editar-animal.js"></script>
</main>

<?php include '../includes/footer.php'; ?>
