<?php
session_start();
if (!isset($_SESSION["form_data"])) {
    header("Location: contacto.php");
    exit;
}

$data = $_SESSION["form_data"];
unset($_SESSION["form_data"]);
?>

<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<section class="enviando">
    <div class="container">
        <h2>Enviando tu mensaje...</h2>
        <p>Por favor, espera un momento.</p>
    </div>
</section>

<form id="formSubmit" action="https://formsubmit.co/juliaperfabregat@gmail.com" method="POST" style="display: none;">
    <input type="hidden" name="Nombre" value="<?= htmlspecialchars($data["nombre"]) ?>">
    <input type="hidden" name="Asunto" value="<?= htmlspecialchars($data["asunto"]) ?>">
    <input type="hidden" name="Email" value="<?= htmlspecialchars($data["email"]) ?>">
    <input type="hidden" name="Mensaje" value="<?= htmlspecialchars($data["mensaje"]) ?>">

    <!-- Configuraciones de formsubmit.co necesarias -->
    <input type="hidden" name="_captcha" value="false">
    <input type="hidden" name="_next" value="http://localhost:3000/BloqueC/RefugioCamada/web/contacto.php?enviado=1">
</form>

<script>
    // Enviar automáticamente el formulario oculto
    document.getElementById("formSubmit").submit();
</script>

<?php include '../includes/footer-web.php'; ?>