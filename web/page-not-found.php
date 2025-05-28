<?php
declare(strict_types=1);

http_response_code(404);

require_once '../includes/database-connection.php';
require_once '../includes/functions.php';

// Datos para la vista
$section     = '';
$title       = 'Página no encontrada';
$description = '';
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
