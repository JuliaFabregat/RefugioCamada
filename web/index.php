<?php
// Conexiones
require '../includes/functions.php';

// Cargar img de animales
$imagenes = glob("../uploads/*.{jpg,jpeg,png,webp}", GLOB_BRACE);

// Filtrar para excluir "blank.png"
$imagenes = array_filter($imagenes, function ($img) {
    return basename($img) !== 'blank.png';
});

shuffle($imagenes);
$imagenes = array_slice($imagenes, 0, 6); // Solo 6 animales

$title = html_escape('Inicio - Refugio Camada');
$description = html_escape('Bienvenid@ al Refugio Camada en Baena.');
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/web/index.css">
<link rel="stylesheet" href="../css/web/quienes-somos.css">
<link rel="stylesheet" href="../css/web/animal-web.css">

<!-- Hero -->
<section class="hero-index">
    <div class="hero-index-contenido">
        <h1>Bienvenid@ al Refugio Camada</h1>
        <p>Ubicado en Baena, buscando hogares para peluditos originales</p>
        <a href="quienes-somos.php" class="button aceptar">Quiénes Somos</a>
    </div>
</section>

<!-- Historia -->
<section class="historia">
    <div class="container-web historia-grid fade-in">
        <div class="historia-imagen">
            <img src="../img/index-historia.jpg" alt="Rescate de animales en Refugio Camada">
        </div>
        <div class="historia-texto">
            <h2>Bienvenidos a Refugio Camada</h2>
            <p>
                Desde 2018, en Baena, trabajamos de forma independiente para proteger y dar una segunda oportunidad
                a animales abandonados. No dependemos de subvenciones públicas ni de apoyos de partidos o empresas,
                lo que nos permite actuar con plena libertad, gracias al compromiso de nuestros socios y personas solidarias que nos apoyan.
                <br><br>
                Actualmente estamos construyendo redes de colaboración con veterinarios locales, con el objetivo de
                ofrecer una atención cada vez más cercana y especializada a nuestros animales. Mientras tanto, seguimos
                gestionando nuestro centro de acogida en Baena (Córdoba), donde cada día luchamos por cambiar vidas.
                <br><br>
                Si quieres conocernos mejor, te invitamos a visitar nuestra página <a href="quienes-somos.php">quiénes somos</a>.
            </p>
        </div>
    </div>
</section>

<!-- Ventajas de adoptar con Camada -->
<section>
    <div class="container-web ventajas">
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
    <div class="container-web text-center">
        <h2>¿Quieres adoptar?</h2>
        <p>Mira nuestros animales refugiados y encuentra tu compañero ideal.</p>
        <div class="galeria-adopcion">
            <?php foreach ($imagenes as $img): ?>
                <div class="img-wrapper">
                    <img src="<?= $img ?>" alt="Animal en adopción">
                </div>
            <?php endforeach; ?>
        </div>
        <a href="lista-animales-web.php" class="button aceptar">Ver Animales Disponibles</a>
    </div>
</section>

<!-- FAQ -->
<section>
    <div class="container-web">
        <h2 class="text-center">Preguntas Frecuentes</h2>
        <div class="faq-container">
            <div class="faq-item campo">
                <button class="faq-question">¿Cómo puedo adoptar?</button>
                <div class="faq-answer">
                    <p>Tienes dos opciones:</p>
                    <ul>
                        <li>Opción 1: Crea una cuenta en nuestra web y solicita el animal mediante esta, en tu perfil de usuario aparecerán las solicitudes y su estado. Si la solicitud es aprobada nos pondremos en contacto contigo mediante tu email. ¡Estate atento!</li> <br>
                        <li>Opción 2: Mándanos un email con el animal que quieres adoptar y realizaremos la solicitud por ahí.</li>
                    </ul>
                </div>
            </div>
            <div class="faq-item campo">
                <button class="faq-question">¿Cuánto tiempo tarda?</button>
                <div class="faq-answer">
                    <p>El proceso suele durar entre 2 y 5 días hábiles tras la solicitud.</p>
                </div>
            </div>
            <div class="faq-item campo">
                <button class="faq-question">¿Qué hago una vez la solicitud sea aceptada?</button>
                <div class="faq-answer">
                    <p>Una vez que la solicitud sea aceptada recibirás un email con los pasos a seguir para continuar la adopción, ¡no te preocupes!</p>
                </div>
            </div>
            <div class="faq-item campo">
                <button class="faq-question">¿El animal tendrá una revisión veterinaria previa?</button>
                <div class="faq-answer">
                    <p>Sí, cuando la solicitud sea aceptada el día de la cita para la entrega del animal se realizará en una de nuestras clínicas veterinarias asociadas, en la cual se realizará un examen completo del animal, microchip y citas de las próximas vacunas y/o necesidades del mismo.</p>
                </div>
            </div>
            <div class="faq-item campo">
                <button class="faq-question">¿Una vez que adopte al animal podré seguir en contacto con vosotros?</button>
                <div class="faq-answer">
                    <p>¡Claro! Estaremos encantados de realizar un seguimiento y aportar consejos para la crianza del animal en las primeras semanas, al final, somos una camada :)</p>
                </div>
            </div>
        </div>
</section>

<?php include '../includes/contacto-ctp.php'; ?>

<?php include '../includes/footer-web.php'; ?>

<!-- Scripts -->
<script src="../js/web/index-faq.js" defer></script>