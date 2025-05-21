<?php
// Conexiones
require '../includes/functions.php';

$title = 'Quienes Somos - Refugio Camada';
$description = 'Conoce la historia, misión y visión del Refugio Camada en Baena.';
?>




<!-- HTML -->
<?php include '../includes/header-web.php'; ?>

<!-- CSS -->
<link rel="stylesheet" href="../css/web/quienes-somos.css">

<!-- Hero -->
<section class="hero-about">
    <div class="hero-about-contenido">
        <h1>Quiénes somos</h1>
        <p>Rescatando animales desde hace 5 años.</p>
    </div>
</section>

<section class="container historia">
    <div class="historia-grid">
        <div class="historia-imagen">
            <img src="../img/about-historia.jpg" alt="Rescate de animales en Refugio Camada">
        </div>
        <section class="historia-texto">
            <h1>Nuestra historia</h1>
            <p>
                Refugio Camada nació en 2018 en Baena con un solo propósito: dar voz y una segunda oportunidad a los animales sin hogar.
                Desde entonces, este proyecto ha crecido gracias al esfuerzo colectivo de un pequeño grupo de personas comprometidas que,
                sin apoyos externos, han construido algo enorme desde cero. No contamos con subvenciones de instituciones ni empresas,
                y hasta ahora no tenemos socios ni donantes habituales. Todo lo que hemos conseguido ha sido posible con trabajo, constancia
                y mucho corazón.
            </p>

            <p>
                Nos dedicamos al rescate y recuperación de animales abandonados de todo tipo: perros, gatos y otros que han sido víctimas
                del maltrato, el abandono o la indiferencia. Nos encargamos de cuidarlos, rehabilitarlos y prepararlos para su adopción,
                con el objetivo de encontrarles una familia que los quiera como merecen. En Refugio Camada defendemos firmemente el principio
                del <strong>sacrificio cero</strong>: ningún animal sano o recuperable debería morir por falta de recursos o espacio.
            </p>

            <p>
                Sabemos que aún queda mucho por hacer y, por eso, estamos buscando colaborar activamente con clínicas veterinarias locales,
                como Tucán, que compartan nuestra visión. Estas alianzas nos permitirán ofrecer un mejor servicio veterinario a nuestros
                animales rescatados y avanzar hacia un modelo más sostenible y profesionalizado.
            </p>

            <p>
                En Camada creemos que cada vida cuenta. Y aunque nuestro camino no ha sido fácil, seguimos adelante con la misma ilusión del
                primer día: rescatar, sanar, proteger y encontrar hogares definitivos para aquellos que más lo necesitan.
            </p>

            <p>
                Gracias por acompañarnos en este viaje. Si quieres saber más o formar parte de nuestra historia,
                <a href="contacto.php">ponte en contacto con nosotros</a>.
            </p>
        </section>

    </div>
</section>

<!-- Misión y Visión -->
<section class="valores">
    <div class="valores-contenido card text-center">
        <h3>Misión</h3>
        <p>
            Proteger, rehabilitar y encontrar un hogar definitivo a cada animal que
            lo necesita, fomentando la adopción responsable y la sensibilización
            sobre el bienestar animal.
        </p>
    </div>
    <div class="valores-contenido card text-center">
        <h3>Visión</h3>
        <p>
            Ser un referente en la provincia de Córdoba por nuestro compromiso con
            el rescate, la educación y la integración comunitaria en favor de los animales.
        </p>
    </div>
</section>

<!-- Nuestro Equipo -->
<section class="equipo">
    <div class="container">
        <h2>Nuestro Equipo</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap:2rem;">
            <div class="card text-center">
                <img src="../img/catgatmusic.png" alt="Ana - Fundadora" class="equipo-foto">
                <h4>Ana García</h4>
                <p>Fundadora y directora</p>
            </div>
            <div class="card text-center">
                <img src="../img/catgatmusic.png" alt="Luis - Veterinario" class="equipo-foto">
                <h4>Dr. Luis Pérez</h4>
                <p>Veterinario jefe</p>
            </div>
            <div class="card text-center">
                <img src="../img/catgatmusic.png" alt="María - Voluntaria" class="equipo-foto">
                <h4>María López</h4>
                <p>Coordinadora de voluntarios</p>
            </div>
        </div>
    </div>
</section>

<!-- Contacto -->
<section class="cta">
    <div class="text-center">
        <div class="contacto">
            <h2>¿Tienes dudas? Te las resolvemos sin compromiso</h2>
            <p>
                En Refugio Camada estamos siempre abiertos a escucharte: ya sea para resolver tus dudas sobre adopciones, proponernos ideas de colaboración o simplemente para compartir historias y fotos de tus peludos. Tu apoyo, sugerencias y preguntas nos ayudan a mejorar cada día.
            </p>
            <p>
                Escríbenos sin compromiso y te responderemos en menos de 48 h. También puedes llamarnos o visitarnos en nuestro refugio —nos encantará conocerte—. ¡Entre todos podemos construir un hogar lleno de esperanza para cada animal!
            </p>
        </div>
        <a href="contacto.php" class="button aceptar">Contáctanos</a>
        <a href="lista-animales-web.php" class="button aceptar">Ver Animales para Adoptar</a>
    </div>
</section>

<?php include '../includes/footer-web.php'; ?>