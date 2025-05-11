<?php
// Conexiones
require '../includes/functions.php';

// Inicializar variables
$nombre = "";
$email = "";
$mensaje = "";
$errores = [
    "nombre" => "",
    "email" => "",
    "mensaje" => ""
];

// Validación del formulario
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validar nombre
    if (empty(trim($_POST["nombre"]))) {
        $errores["nombre"] = "El nombre es obligatorio.";
    } else {
        $nombre = htmlspecialchars(trim($_POST["nombre"]));
    }

    // Validar email
    if (empty(trim($_POST["email"]))) {
        $errores["email"] = "El email es obligatorio.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errores["email"] = "El formato del email no es válido.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    // Validar mensaje
    if (empty(trim($_POST["mensaje"]))) {
        $errores["mensaje"] = "El mensaje no puede estar vacío.";
    } else {
        $mensaje = htmlspecialchars(trim($_POST["mensaje"]));
    }

    // Si no hay errores
    if (!array_filter($errores)) {
        echo "<p style='color: green; text-align: center;'>¡Gracias por tu mensaje!</p>";

        // Resetear valores después de enviar
        $nombre = $email = $mensaje = "";
    }
}

$title = 'Contacto - Refugio Camada';
$description = 'Contacto del Refugio Camada.';
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/web/contacto.css">

<!-- Hero Section -->
<section class="hero-contacto">
    <div class="hero-contacto-contenido">
        <h1>Contacto</h1>
    </div>
</section>

<!-- Contact Form + Info -->
<section class="container contacto-grid">

    <!-- Formulario -->
    <div class="contacto-form">
        <h2>Escríbenos</h2>
        <form action="https://formsubmit.co/juliaperfabregat@gmail.com" method="POST">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" required>
            </div>

            <div class="campo">
                <label for="asunto">Asunto</label>
                <input type="text" id="asunto" name="asunto" required>
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="campo">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" required></textarea>
            </div>

            <div class="campo campo-inline">
                <input type="checkbox" id="terminos" name="terminos" required>
                <p for="terminos">Acepto los términos y condiciones.</p>
            </div>

            <button type="submit" class="button aceptar">Enviar</button>
        </form>
    </div>

    <!-- Información del refugio -->
    <div class="info-container">
        <div class="refugio-info">
            <h2>Información del refugio</h2>
            <p><strong>Email:</strong> contacto@refugio-camada.es</p>
            <p><strong>Teléfono:</strong> +34 600 123 456</p>
            <p><strong>Ubicación:</strong> Avenida Castro del Río 15, Baena</p>
            <p><strong>Horario:</strong> Lun a Vie de 9 a 17hs</p>
        </div>
    </div>

</section>

<!-- Mapa -->
<section class="contacto-mapa">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1723.1951031036212!2d-4.326189448458389!3d37.61736737578526!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6da165ee4ce5eb%3A0x9abc662336af35de!2sAv.%20Castro%20del%20R%C3%ADo%2C%2015%2C%2014850%20Baena%2C%20C%C3%B3rdoba!5e0!3m2!1ses!2ses!4v1746949214354!5m2!1ses!2ses"
        width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</section>

<?php include '../includes/footer-web.php'; ?>