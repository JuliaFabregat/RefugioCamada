<?php
declare(strict_types=1);

http_response_code(404);

require_once '../models/Conexion.php';
$pdo = Conexion::obtenerConexion();

require_once '../includes/functions.php';

// Datos para la vista
$title       = html_escape('Página no encontrada');
$description       = html_escape('Página no encontrada');
$section     = '';
?>

<!-- HTML -->
<?php require_once '../includes/header-web.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/web/page-not-found.css">

<main class="container" id="content">
  <h1>¡Lo siento! Rex no ha podido olfatear esta página.</h1>
  <p>Intenta volver al <a href="index.php">inicio</a> o mándanos un e-mail:
    <a href="mailto:contacto@refugiocamada.es">contacto@refugiocamada.es</a>
  </p>
</main>

<?php require_once '../includes/footer-web.php'; ?>
