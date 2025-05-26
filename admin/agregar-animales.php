<?php

declare(strict_types=1);
require __DIR__ . '/../includes/admin-auth.php';
require '../includes/database-connection.php';
require '../includes/functions.php';
require '../includes/validate.php';

// Obtenemos la lista de especies
$sql_especies = "SELECT id, especie FROM especies ORDER BY especie";
$especies_list = pdo($pdo, $sql_especies)->fetchAll();

// Inicializar
$animal = [
    'nombre' => '',
    'especie_id' => '',
    'raza' => '',
    'edad' => '',
    'genero' => '',
    'imagen' => '',
    'alt' => ''
];

$vet_data = [
    'microchip' => '',
    'castracion' => '',
    'vacunas' => '',
    'info_adicional' => ''
];

$errors = [
    'nombre' => '',
    'especie' => '',
    'raza' => '',
    'imagen' => '',
    'edad' => '',
    'warning' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captar los datos del formulario
    $animal['nombre']   = $_POST['nombre'] ?? '';
    $animal['especie_id'] = $_POST['especie_id'] ?? '';
    $animal['raza']     = $_POST['raza'] ?? '';
    $animal['edad']     = $_POST['edad'] ?? '';
    $animal['genero']   = $_POST['genero'] ?? '';
    $animal['alt']      = $_POST['alt'] ?? '';

    // Comprobar checkbox de Vet_Data
    $rellenar_ficha_vet = isset($_POST['rellenar_ficha']) && $_POST['rellenar_ficha'] == '1';

    if ($rellenar_ficha_vet) {
        // Capturar los datos de Vet Data
        $vet_data['microchip'] = isset($_POST['microchip']) && $_POST['microchip'] !== '' ? $_POST['microchip'] : 0;
        $vet_data['castracion'] = isset($_POST['castracion']) && $_POST['castracion'] !== '' ? $_POST['castracion'] : 0;
        $vet_data['vacunas'] = trim($_POST['vacunas'] ?? '') !== '' ? $_POST['vacunas'] : 'N/E';
        $vet_data['info_adicional'] = trim($_POST['info_adicional'] ?? '') !== '' ? $_POST['info_adicional'] : 'N/E';
    } else {
        // Si no, guardamos los valores por defecto
        $vet_data['microchip'] = 0;
        $vet_data['castracion'] = 0;
        $vet_data['vacunas'] = 'N/E';
        $vet_data['info_adicional'] = 'N/E';
    }

    // Validación - Nombre
    $errors['nombre'] = is_text($animal['nombre'], 1, 50)
        ? '' : 'El nombre es obligatorio.';

    // Validación - Especie
    $errors['especie'] = is_especie_id($animal['especie_id'], $especies_list)
        ? '' : 'Debe seleccionar una Especie existente.';

    // Validación - Raza
    $errors['raza'] = is_text($animal['raza'], 1, 50)
        ? '' : 'Debe introducir una raza.';

    // Validación - Edad
    $errors['edad'] = is_age_valid($animal['edad'])
        ? '' : 'Debe introducir una edad aproximada. Formato: "Joven(2 años)", "Cachorro(11 meses)".';

    // Validación - Imagen
    if (!empty($_FILES['imagen']['name'])) {
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];
        $allowed_mime_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size = 2 * 1024 * 1024; // 2MB

        $file_name = $_FILES['imagen']['name'];
        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_size = $_FILES['imagen']['size'];
        $file_error = $_FILES['imagen']['error'];

        // Validar extensión
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $errors['imagen'] = 'Formato no permitido. Solo JPG, JPEG, PNG.';
        }

        // Validar MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file_tmp);
        finfo_close($finfo);

        if (!in_array($mime_type, $allowed_mime_types)) {
            $errors['imagen'] = 'El archivo no es una imagen válida.';
        }

        // Validar contenido real
        if (!getimagesize($file_tmp)) {
            $errors['imagen'] = 'El archivo no es una imagen válida.';
        }

        // Validar tamaño
        if ($file_size > $max_size) {
            $errors['imagen'] = 'El tamaño máximo permitido es 2MB.';
        }

        // Validar error de subida
        if ($file_error !== UPLOAD_ERR_OK) {
            $errors['imagen'] = 'Error al subir el archivo.';
        }

        // Validar que sea un archivo subido
        if (!is_uploaded_file($file_tmp)) {
            $errors['imagen'] = 'Archivo no válido o corrupto.';
        }
    } else {
        $errors['imagen'] = 'Debes subir una imagen del animal.';
    }

    // Procesar si no hay errores
    if (!array_filter($errors)) {
        try {
            // Configurar nombre único para la img
            $animal_name = strtolower(preg_replace('/[^a-zA-Z0-9]/', '_', $animal['nombre']));
            $animal_name = trim($animal_name, '_') ?: 'animal';
            $new_filename = $animal_name . '_' . uniqid() . '.' . $file_extension;  // Nombre con código aleatorio
            $target_dir = "../uploads/";
            $target_file = $target_dir . $new_filename;

            // Obtener el nombre de la especie
            $sql_especie_nombre = "SELECT especie FROM especies WHERE id = :id LIMIT 1";
            $especie_result = pdo($pdo, $sql_especie_nombre, ['id' => $animal['especie_id']])->fetch();
            $especie_name = $especie_result ? $especie_result['especie'] : 'Animal';

            // Texto alt automático
            $alt_text = "{$especie_name}: {$animal['raza']}";

            // Crear directorio si no existe
            if (!is_dir($target_dir)) {
                if (!mkdir($target_dir, 0755, true)) {
                    throw new Exception("No se pudo crear el directorio de imágenes");
                }
            }

            // Verificar existencia (aunque es improbable con uniqid)
            if (file_exists($target_file)) {
                $errors['imagen'] = 'Nombre de archivo duplicado. Por favor, intenta de nuevo.';
            }

            // Subir archivo
            if (!move_uploaded_file($file_tmp, $target_file)) {
                throw new Exception("Error al mover el archivo");
            }

            // Insertar imagen
            $sql = "INSERT INTO imagenes (imagen, alt) VALUES (:imagen, :alt)";
            pdo($pdo, $sql, [
                'imagen' => $new_filename,
                'alt' => $alt_text
            ]);

            $imagen_id = $pdo->lastInsertId();

            // Insertar datos veterinarios
            $sql_vet_data = "INSERT INTO vet_data 
                             (microchip, castracion, vacunas, info_adicional) 
                             VALUES 
                             (:microchip, :castracion, :vacunas, :info_adicional)";

            pdo($pdo, $sql_vet_data, [
                'microchip' => $vet_data['microchip'],
                'castracion' => $vet_data['castracion'],
                'vacunas' => $vet_data['vacunas'],
                'info_adicional' => $vet_data['info_adicional']
            ]);
            $vet_data_id = $pdo->lastInsertId();

            // Obtener el ID de la raza
            $sql_raza = "SELECT id FROM raza WHERE nombre = :raza AND especie_id = :especie_id LIMIT 1";
            $stmt = pdo($pdo, $sql_raza, [
                'raza' => $animal['raza'],
                'especie_id' => $animal['especie_id']
            ]);
            $raza = $stmt->fetch();

            // Comprobar que la raza existe para no crear 2 razas iguales
            if ($raza) {
                $raza_id = $raza['id'];
            } else {
                // Si la raza no existe, insertarla
                $sql_insert_raza = "INSERT INTO raza (nombre, especie_id) VALUES (:nombre, :especie_id)";
                pdo($pdo, $sql_insert_raza, [
                    'nombre' => $animal['raza'],
                    'especie_id' => $animal['especie_id']
                ]);
                $raza_id = $pdo->lastInsertId();  // Obtener el ID de la nueva raza
            }

            // Insertar animal
            $sql_animal = "INSERT INTO animales 
               (nombre, edad, genero, especie_id, raza_id, imagen_id, vet_data_id, estado) 
               VALUES 
               (:nombre, :edad, :genero, :especie_id, :raza_id, :imagen_id, :vet_data_id, 'Disponible')";

            pdo($pdo, $sql_animal, [
                'nombre' => $animal['nombre'],
                'edad' => $animal['edad'],
                'genero' => $animal['genero'],
                'especie_id' => $animal['especie_id'],
                'raza_id' => $raza_id,
                'imagen_id' => $imagen_id,
                'vet_data_id' => $vet_data_id
            ]);

            redirect('lista-animales.php', ['success' => 'Animal agregado']);
        } catch (PDOException $e) {
            $errors['warning'] = 'Error en la base de datos: ' . $e->getMessage();
        } catch (Exception $e) {
            $errors['warning'] = 'Error: ' . $e->getMessage();
        }
    } else {
        $errors['warning'] = 'Por favor, corrija los siguientes errores.';
    }
}

// Datos
$title = html_escape("Agregar Animal");
$description = html_escape("Formulario para dar de alta nuevos animales");
$section = 'agregarAnimales';
?>




<!-- HTML -->
<?php include '../includes/header.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/admin/editar-animal.css">

<main class="formDiv">
    <form action="agregar-animales.php" method="post" enctype="multipart/form-data" class="narrow" novalidate>

        <!-- FORM - Datos del Animal -->
        <h1>Datos del Animal</h1>

        <?php if ($errors['warning']): ?>
            <div class="alert alert-danger"><?= $errors['warning'] ?></div>
        <?php endif; ?>

        <div class="campo">
            <label>Nombre:</label>
            <input type="text" name="nombre" value="<?= html_escape($animal['nombre']) ?>" placeholder="Rex, Kitty..." required> <br>
            <span class="errors"><?= $errors['nombre'] ?></span>
        </div>

        <div class="campo">
            <label>Especie:</label>
            <select name="especie_id" required>
                <option value="">Selecciona</option>
                <?php foreach ($especies_list as $especie): ?>
                    <option value="<?= $especie['id'] ?>">
                        <?= html_escape($especie['especie']) ?>
                    </option>
                <?php endforeach; ?>
            </select> <br>
            <span class="errors"><?= $errors['especie'] ?></span>
        </div>

        <div class="campo">
            <label>Raza:</label>
            <input type="text" name="raza" value="<?= html_escape($animal['raza']) ?>" placeholder="Labrador, Persa..." required> <br>
            <span class="errors"><?= $errors['raza'] ?></span>
        </div>

        <div class="campo">
            <label>Edad:</label>
            <input type="text" name="edad" value="<?= html_escape($animal['edad']) ?>"
                placeholder="Ej: 4 años, Jóven (2 años)"> <br>
            <span class="errors"><?= $errors['edad'] ?></span>
        </div>

        <div class="campo">
            <label>Género:</label>
            <select name="genero">
                <option value="Indefinido">N/E</option>
                <option value="Macho">Macho</option>
                <option value="Hembra">Hembra</option>
            </select>
        </div>

        <div class="campo">
            <label>Imagen:</label>
            <input type="file" name="imagen" accept="image/*" required> <br>
            <span class="errors"><?= $errors['imagen'] ?></span>
        </div>

        <br>

        <!-- CHECKBOX para rellenar la ficha Veterinaria -->
        <div class="campo campo-inline">
            <label>
                <input type="checkbox" id="rellenar_ficha" name="rellenar_ficha" value="1"
                    <?php echo isset($_POST['rellenar_ficha']) ? 'checked' : ''; ?>>
                ¿Desea rellenar la ficha veterinaria del animal?
            </label>
        </div>

        <div id="ficha_veterinaria" style="display: none;">

            <!-- FORM - Datos del Animal -->
            <h2>Datos Veterinarios</h2>

            <div class="campo">
                <label>¿Tiene Microchip?</label>
                <select name="microchip" required>
                    <option value="" <?= $vet_data['microchip'] === '' ? 'selected' : '' ?>>N/E</option>
                    <option value="1" <?= $vet_data['microchip'] === '1' ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= $vet_data['microchip'] === '0' ? 'selected' : '' ?>>No</option>
                </select>
            </div>

            <div class="campo">
                <label>¿Está Castrado?</label>
                <select name="castracion">
                    <option value="" <?= $vet_data['castracion'] === '' ? 'selected' : '' ?>>N/E</option>
                    <option value="1" <?= $vet_data['castracion'] === '1' ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= $vet_data['castracion'] === '0' ? 'selected' : '' ?>>No</option>
                </select>
            </div>

            <div class="campo">
                <label>Vacunas:</label>
                <input type="text" name="vacunas" value="<?= html_escape($vet_data['vacunas']) ?>" placeholder="Ej: Rabia, trivalente...">
            </div>

            <div class="campo">
                <label>Información adicional:</label>
                <textarea name="info_adicional"><?= html_escape($vet_data['info_adicional']) ?></textarea>
            </div>
        </div>

        <br><br>

        <!-- Botones de Acción -->
        <button type="submit" class="button aceptar">Agregar Animal</button>
        <a href="lista-animales.php" class="button cancelar">Cancelar</a>
    </form>
</main>

<?php include '../includes/footer.php'; ?>

<!-- Script -->
<script src="../js/admin/agregar-animal.js" defer></script>