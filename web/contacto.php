<?php
// contacto.php
require '../includes/functions.php';

// Detectamos el modal
$mostrarModal = isset($_GET["enviado"]) && $_GET["enviado"] == "1";

$nombre = "";
$email = "";
$mensaje = "";
$asunto = "";
$errores = [
    "nombre" => "",
    "email" => "",
    "mensaje" => "",
    "asunto" => "",
    "terminos" => ""
];


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty(trim($_POST["nombre"]))) {
        $errores["nombre"] = "El nombre es obligatorio.";
    } else {
        $nombre = htmlspecialchars(trim($_POST["nombre"]));
    }

    if (empty(trim($_POST["email"]))) {
        $errores["email"] = "El email es obligatorio.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $errores["email"] = "El formato del email no es válido.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    if (empty(trim($_POST["mensaje"]))) {
        $errores["mensaje"] = "El mensaje no puede estar vacío.";
    } else {
        $mensaje = htmlspecialchars(trim($_POST["mensaje"]));
    }

    if (empty(trim($_POST["asunto"]))) {
        $errores["asunto"] = "El asunto es obligatorio.";
    } else {
        $asunto = htmlspecialchars(trim($_POST["asunto"]));
    }

    if (!isset($_POST["terminos"])) {
        $errores["terminos"] = "Debes aceptar los términos y condiciones.";
    }

    // Si todo está bien, redirigir a enviar.php
    if (!array_filter($errores)) {
        session_start();
        $_SESSION["form_data"] = compact("nombre", "email", "mensaje", "asunto");
        header("Location: enviar.php");
        exit;
    }
}

$title = html_escape('Contacto - Refugio Camada');
$description = html_escape('Contacto del Refugio Camada.');
$section     = 'contacto';
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<link rel="stylesheet" href="../css/web/contacto.css">

<section class="hero-contacto">
    <div class="hero-contacto-contenido">
        <h1>Contacto</h1>
    </div>
</section>

<section>
    <div class="container contacto-grid">
        <div class="contacto-form">
            <!-- Modal del envío del formulario -->
            <?php if ($mostrarModal): ?>
                <div id="modal-enviado" class="modal">
                    <div class="modal-contenido">
                        <p>¡Formulario enviado correctamente!</p>
                        <button id="cerrar-modal" class="button">Cerrar</button>
                    </div>
                </div>
            <?php endif; ?>


            <h2>Escríbenos</h2>
            <form method="POST" novalidate>
                <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="<?= html_escape($nombre) ?>">
                    <?php if ($errores['nombre']): ?>
                        <p class="error"><?= $errores['nombre'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="campo">
                    <label for="asunto">Asunto</label>
                    <input type="text" id="asunto" name="asunto" value="<?= html_escape($asunto) ?>">
                    <?php if ($errores['asunto']): ?>
                        <p class="error"><?= $errores['asunto'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="campo">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?= html_escape($email) ?>">
                    <?php if ($errores['email']): ?>
                        <p class="error"><?= $errores['email'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="campo">
                    <label for="mensaje">Mensaje</label>
                    <textarea id="mensaje" name="mensaje"><?= html_escape($mensaje) ?></textarea>
                    <?php if ($errores['mensaje']): ?>
                        <p class="error"><?= $errores['mensaje'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="campo campo-inline">
                    <input type="checkbox" id="terminos" name="terminos" <?= isset($_POST["terminos"]) ? "checked" : "" ?>>
                    <p class="terms">Acepto los términos y condiciones.</p>
                </div>
                <?php if ($errores['terminos']): ?>
                    <p class="error"><?= $errores['terminos'] ?></p>
                <?php endif; ?>

                <button type="submit" class="button aceptar">Enviar</button>
            </form>
        </div>

        <div class="info-container">
            <div class="refugio-info">
                <h2>Información del refugio</h2>
                <p>
                    <span class="material-icons">email</span>
                    <a href="mailto:contacto@refugiocamada.org">contacto@refugiocamada.org</a>
                </p>
                <p>
                    <span class="material-icons">call</span>
                    <a href="tel:+34600123456">+34 600 123 456</a>
                </p>
                <p>
                    <span class="material-icons">location_on</span>
                    Avenida Castro del Río 15, Baena
                </p>
                <p>
                    <span class="material-icons">schedule</span>
                    Lun a Vie de 9 a 17hs
                </p>
            </div>
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

<!-- Scripts -->
<script src="../js/web/contacto.js" defer></script>