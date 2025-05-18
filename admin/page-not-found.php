<?php
declare(strict_types=1);
http_response_code(404);
require __DIR__ . '/../includes/admin-auth.php';
require_once '../includes/database-connection.php';
require_once '../includes/functions.php';

$sql = "SELECT id, name FROM category WHERE navigation = 1;";

// Datos
$title       = html_escape('Página no encontrada');
$description       = html_escape('Página no encontrada');
$section     = '';
?>




<!-- HTML -->
<?php require_once '../includes/header.php'; ?>

<main>
  <div class="container">
    <h1>¡Lo siento! Rex no ha podido olfatear esta página.</h1>
    <p>Intenta volver al <a href="index.php">inicio</a> o mándanos un e-mail:
      <a href="mailto:contacto@refugiocamada.org">contacto@refugiocamada.org</a>
    </p>
  </div>
</main>

<?php require_once '../includes/footer.php';
exit;
?>