<?php
declare(strict_types=1);
require __DIR__ . '/../includes/admin-auth.php';
require '../includes/database-connection.php';
require '../includes/functions.php';

// Validar id del animal
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirect('lista-animales.php');
}

// Obtener datos del animal
$sql = "SELECT 
          a.nombre, a.edad, a.estado, a.raza_id, a.vet_data_id, a.imagen_id,
          v.microchip, v.castracion, v.vacunas, v.info_adicional,
          e.id AS especie_id, r.nombre AS raza_nombre,
          i.imagen AS image_file, i.alt AS image_alt
        FROM animales AS a
        LEFT JOIN vet_data AS v ON a.vet_data_id = v.id
        LEFT JOIN raza AS r ON a.raza_id = r.id
        LEFT JOIN especies AS e ON r.especie_id = e.id
        LEFT JOIN imagenes AS i ON a.imagen_id = i.id
        WHERE a.id = :id";
$animal = pdo($pdo, $sql, ['id' => $id])->fetch();
if (!$animal) redirect('lista-animales.php');

// Obtener razas
$sqlRazas = "SELECT id, nombre FROM raza WHERE especie_id = :especie_id ORDER BY nombre ASC";
$razas = pdo($pdo, $sqlRazas, ['especie_id' => $animal['especie_id']])->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre       = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $edad         = filter_input(INPUT_POST, 'edad', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $estado       = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $raza_id      = filter_input(INPUT_POST, 'raza_id', FILTER_VALIDATE_INT);
    $raza_nombre  = filter_input(INPUT_POST, 'raza_nombre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Actualizar datos del animal
    pdo(
        $pdo,
        "UPDATE animales 
                SET nombre = :nombre, edad = :edad, estado = :estado, raza_id = :raza_id 
                WHERE id = :id",
        compact('nombre', 'edad', 'estado', 'raza_id', 'id')
    );
    if ($raza_nombre) {
        pdo(
            $pdo,
            "UPDATE raza SET nombre = :nombre WHERE id = :raza_id",
            ['nombre' => $raza_nombre, 'raza_id' => $raza_id]
        );
    }

    // Subir imagen si se ha seleccionado una nueva
    if (!empty($_FILES['nueva_imagen']['name']) && $_FILES['nueva_imagen']['error'] === UPLOAD_ERR_OK) {
        $tmp       = $_FILES['nueva_imagen']['tmp_name'];
        $fileName  = $_FILES['nueva_imagen']['name'];
        $ext       = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $mime      = mime_content_type($tmp);
        $allowed   = ['jpg', 'jpeg', 'png'];
        $maxSize   = 2 * 1024 * 1024;

        if (
            in_array($ext, $allowed, true)
            && preg_match('#^image/#', $mime)
            && $_FILES['nueva_imagen']['size'] <= $maxSize
            && getimagesize($tmp)
        ) {
            // Nombre del archivo
            $base = preg_replace('/[^a-z0-9]+/i', '_', $nombre);
            $nuevoNombre = strtolower(trim($base, '_')) . '_' . uniqid() . ".$ext";
            $destino     = __DIR__ . '/../uploads/' . $nuevoNombre;

            if (move_uploaded_file($tmp, $destino)) {
                // Insertar en imágenes
                pdo($pdo, "INSERT INTO imagenes (imagen, alt) VALUES (:img, :alt)", [
                    'img' => $nuevoNombre,
                    'alt' => $nombre
                ]);
                $newImgId = $pdo->lastInsertId();

                // Actualizar FK en animales
                pdo($pdo, "UPDATE animales SET imagen_id = :imgId WHERE id = :id", [
                    'imgId' => $newImgId,
                    'id'    => $id
                ]);

                // Eliminar antigua imagen *DESPUÉS* de actualizar FK
                if ($animal['imagen_id']) {
                    // Nombre de img antiguo
                    $oldFile = pdo(
                        $pdo,
                        "SELECT imagen FROM imagenes WHERE id = :id",
                        ['id' => $animal['imagen_id']]
                    )->fetchColumn();
                    // Borrar fichero
                    @unlink(__DIR__ . '/../uploads/' . $oldFile);
                    // Borrar fila en imágenes
                    pdo($pdo, "DELETE FROM imagenes WHERE id = :id", [
                        'id' => $animal['imagen_id']
                    ]);
                }
            }
        }
    }

    // Vet-Data
    $microchip   = isset($_POST['microchip'])   ? 1 : 0;
    $castracion  = isset($_POST['castracion'])  ? 1 : 0;
    $vacunas     = filter_input(INPUT_POST, 'vacunas', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $info        = filter_input(INPUT_POST, 'info_adicional', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if ($animal['vet_data_id']) {
        pdo($pdo, "UPDATE vet_data 
                    SET microchip=:microchip, castracion=:castracion, vacunas=:vacunas, info_adicional=:info 
                    WHERE id=:vid", [
            'microchip' => $microchip,
            'castracion' => $castracion,
            'vacunas' => $vacunas,
            'info' => $info,
            'vid' => $animal['vet_data_id']
        ]);
    } else {
        pdo(
            $pdo,
            "INSERT INTO vet_data (microchip, castracion, vacunas, info_adicional) 
                   VALUES (:microchip, :castracion, :vacunas, :info)",
            compact('microchip', 'castracion', 'vacunas', 'info')
        );
    }

    redirect('lista-animales.php?mensaje=modificado');
}

// Datos
$title = html_escape("Editar a {$animal['nombre']}");
$description = html_escape('Formulario para editar datos del animal');
$section = 'listaAnimales';
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/admin/editar-animal.css">

<main class="formDiv">

    <form method="POST" enctype="multipart/form-data">
        <h1>Editar a <?= html_escape($animal['nombre']) ?></h1>

        <!-- Imagen del animal -->
        <div class="campo">
            <label for="imagen-input" class="imagen-label">
                <div class="imagen-container">
                    <img src="../uploads/<?= html_escape($animal['image_file'] ?? 'blank.jpg') ?>"
                        alt="<?= html_escape($animal['nombre']) ?>"
                        class="animal-imagen-clickable">
                    <span class="overlay">Cambiar imagen</span>
                </div>
            </label>
            <input type="file" name="nueva_imagen" id="imagen-input" accept="image/*" class="hidden">
        </div>

        <div class="campo">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= html_escape($animal['nombre'] ?? '') ?>" required>
        </div>

        <div class="campo">
            <label>Edad:</label>
            <input type="text" name="edad" value="<?= $animal['edad'] ?? '' ?>">
        </div>

        <div class="campo">
            <label>Estado:</label>
            <select name="estado" required>
                <option value="Disponible" <?= ($animal['estado'] === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                <option value="En proceso" <?= ($animal['estado'] === 'En proceso') ? 'selected' : '' ?>>En proceso</option>
                <option value="Adoptado" <?= ($animal['estado'] === 'Adoptado') ? 'selected' : '' ?>>Adoptado</option>
            </select>
        </div>

        <div class="campo">
            <label>Raza:</label>
            <select name="raza_id" id="raza_id" required>
                <?php foreach ($razas as $raza) { ?>
                    <option value="<?= $raza['id'] ?>" <?= ($raza['id'] == $animal['raza_id']) ? 'selected' : '' ?>>
                        <?= html_escape($raza['nombre']) ?>
                    </option>
                <?php } ?>
            </select>
        </div>

        <div class="campo">
            <label>Editar nombre de la raza:</label>
            <input type="text" name="raza_nombre" id="raza_nombre" value="<?= html_escape($animal['raza_nombre'] ?? '') ?>">
        </div>

        <br>

        <h2>Ficha Veterinaria</h2>

        <br>

        <div class="detalle-vet">
            <div class="campo">
                <label><input type="checkbox" name="microchip" <?= $animal['microchip'] ? 'checked' : '' ?>>Microchip</label>
            </div>
            <div class="campo">
                <label><input type="checkbox" name="castracion" <?= $animal['castracion'] ? 'checked' : '' ?>>Castración</label>
            </div>
            <div class="campo">
                <label>Vacunas:</label>
                <input type="text" name="vacunas" value="<?= html_escape($animal['vacunas'] ?? '') ?>">
            </div>
            <div class="campo">
                <label>Información adicional:</label>
                <textarea name="info_adicional"><?= html_escape($animal['info_adicional'] ?? '') ?></textarea>
            </div>
        </div>

        <button type="submit" class="button aceptar">Guardar cambios</button>
        <a href="lista-animales.php" class="button cancelar">Cancelar</a>

    </form>
</main>

<!-- Script -->
<script src="../js/editar-animal.js"></script>

<?php include '../includes/footer.php'; ?>