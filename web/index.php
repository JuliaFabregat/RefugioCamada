<?php
// Conexiones
require '../includes/functions.php';

$title = 'Refugio Camada - Inicio';
$description = 'Bienvenid@ al Refugio Camada en Baena. Adoptar es dar esperanza.';
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/web/index.css">

<!-- Hero -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content container text-center">
        <h1>Bienvenid@ al Refugio Camada</h1>
        <p>Ubicado en Baena, comprometidos con el bienestar animal</p>
        <a href="quienes-somos.php" class="button aceptar">Quiénes Somos</a>
    </div>
</section>

<!-- Ventajas -->
<section class="ventajas">
    <div class="container">
        <h2>Por qué adoptar con Camada</h2>
        <div class="ventajas-grid">
            <div class="card">
                <span class="material-icons icon-large">favorite</span>
                <h3>Amor Incondicional</h3>
                <p>Lleva a casa un amigo fiel que te llene de cariño.</p>
            </div>
            <div class="card">
                <span class="material-icons icon-large">shield</span>
                <h3>Proceso Seguro</h3>
                <p>Garantizamos salud y bienestar en cada adopción.</p>
            </div>
            <div class="card">
                <span class="material-icons icon-large">emoji_events</span>
                <h3>Apoyo Continuo</h3>
                <p>Te asesoramos antes, durante y después.</p>
            </div>
            <div class="card">
                <span class="material-icons icon-large">pets</span>
                <h3>Causa Noble</h3>
                <p>Contribuye a una misión de rescate y cuidado.</p>
            </div>
        </div>
    </div>
</section>

<!-- Adoptar -->
<section class="cta-adoptar">
    <div class="container text-center">
        <h2>¿List@ para adoptar?</h2>
        <a href="lista-animales.php" class="button aceptar">Ver Animales Disponibles</a>
    </div>
</section>

<!-- FAQ -->
<section class="faq">
    <div class="container">
        <h2 class="text-center">Preguntas Frecuentes</h2>
        <div class="formDiv faq__container">
            <div class="faq__item campo">
                <button class="faq__question">¿Cómo puedo adoptar?</button>
                <div class="faq__answer">
                    <p>Completa nuestro formulario de adopción o visítanos en el refugio.</p>
                </div>
            </div>
            <div class="faq__item campo">
                <button class="faq__question">¿Cuánto tiempo tarda?</button>
                <div class="faq__answer">
                    <p>El proceso suele durar entre 3 y 7 días hábiles tras la solicitud.</p>
                </div>
            </div>
            <div class="faq__item campo">
                <button class="faq__question">¿Qué necesito para adoptar?</button>
                <div class="faq__answer">
                    <p>Documento de identidad, comprobante de domicilio y compromiso de cuidado.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contacto -->
<section class="contacto-final">
    <div class="container text-center">
        <p>¿Tienes más dudas? <br><br>
        <a href="contacto.php" class="button aceptar">Contáctanos</a></p>
    </div>
</section>

<?php include '../includes/footer-web.php'; ?>

<script>
    document.querySelectorAll('.faq__question').forEach(btn => {
        btn.addEventListener('click', () => {
            const item = btn.closest('.faq__item');
            // alterna activo
            item.classList.toggle('active');
            // opcional: cierra los demás
            document.querySelectorAll('.faq__item').forEach(other => {
                if (other !== item) other.classList.remove('active');
            });
        });
    });
</script>