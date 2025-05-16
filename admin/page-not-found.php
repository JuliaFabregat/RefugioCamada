<?php
declare(strict_types = 1);
http_response_code(404);
require __DIR__ . '/../includes/admin-auth.php';
require_once '../includes/database-connection.php';
require_once '../includes/functions.php';

$sql = "SELECT id, name FROM category WHERE navigation = 1;";

// Datos
$section     = '';
$title       = 'Page not found';
$description = '';
?>




<!-- HTML -->
<?php require_once '../includes/header.php'; ?>
  <main class="container" id="content">
    <h1>¡Lo siento! Rex no ha podido olfatear esta página.</h1>
    <p>Intenta volver al <a href="index.php">inicio</a> o mándanos un e-mail:
      <a href="mailto:hello@eg.link">contacto@refugiocamada.es</a></p>
  </main>
<?php require_once '../includes/footer.php';
exit;
?>