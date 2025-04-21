<?php
declare(strict_types = 1);                                    // Use strict types
http_response_code(404);                                      // Set HTTP response code
require_once 'includes/database-connection.php';              // Create PDO object
require_once 'includes/functions.php';                        // Include functions

$sql = "SELECT id, name FROM category WHERE navigation = 1;"; // SQL to get categories

// Datos
$section     = '';                                            // Current category
$title       = 'Page not found';                              // HTML <title> content
$description = '';                                            // Meta description content
?>




<!-- HTML -->
<?php require_once 'includes/header.php'; ?>
  <main class="container" id="content">
    <h1>¡Lo siento! Rex no ha podido olfatear esta página.</h1>
    <p>Intenta volver al <a href="index.php">inicio</a> o mándanos un e-mail:
      <a href="mailto:hello@eg.link">camadarefugioanimales@gmail.com</a></p>
  </main>
<?php
require_once 'includes/footer.php';
exit;
?>