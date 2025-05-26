<?php
// Conexiones
require '../includes/functions.php';

$title = 'Quiénes Somos - Refugio Camada';
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
    </div>
</section>

<section>
    <div class="container fade-in historia">
        <div class="historia-grid">
            <div class="historia-imagen">
                <img src="../img/quienes-somos-historia.jpg" alt="Rescate de animales en Refugio Camada">
            </div>
            <section class="historia-texto">
                <h1>Nuestra historia</h1>
                <p>
                    Refugio Camada nació en 2018 en Baena con un solo propósito: dar voz y una segunda oportunidad a los animales sin hogar.
                    Desde entonces, este proyecto ha crecido gracias al esfuerzo colectivo de un pequeño grupo de personas comprometidas que,
                    sin apoyos externos, han construido algo enorme desde cero.
                </p>

                <p>
                    Nos dedicamos al rescate y recuperación de animales abandonados de todo tipo. Nos encargamos de cuidarlos, rehabilitarlos y prepararlos para su adopción,
                    con el objetivo de encontrarles una familia que los quiera como merecen. En Refugio Camada defendemos firmemente el principio
                    del <strong>sacrificio cero</strong>: ningún animal sano o recuperable debería morir por falta de recursos o espacio.
                </p>

                <p>
                    Sabemos que aún queda mucho por hacer y, por eso, estamos buscando colaborar activamente con clínicas veterinarias locales,
                    como Tucán, que compartan nuestra visión. Estas alianzas nos permitirán ofrecer un mejor servicio veterinario a nuestros
                    animales rescatados y avanzar hacia un modelo más sostenible y profesionalizado.
                </p>
            </section>

        </div>
    </div>
</section>

<!-- Misión y Visión -->
<section class="valores">
    <div class="card valores-contenido text-center">
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
        <div class="grid">
            <div class="card text-center">
                <img src="../img/equipo-1.jpg" alt="Jules - Fundadora y voluntaria" class="equipo-foto">
                <h4>Jill Valentine</h4>
                <p>Fundadora</p>
            </div>
            <div class="card text-center">
                <img src="../img/equipo-2.png" alt="Manu - Coordinador de voluntarios" class="equipo-foto">
                <h4>Leon S.</h4>
                <p>Coordinador de voluntarios</p>
            </div>
            <div class="card text-center">
                <img src="../img/equipo-3.jpg" alt="Elsa - Voluntaria" class="equipo-foto">
                <h4>Ada Wong</h4>
                <p>Voluntaria</p>
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
                Escríbenos sin compromiso y te responderemos en menos de 48h. <br>También puedes llamarnos o visitarnos en nuestro refugio ¡nos encantará conocerte!.
            </p>
        </div>
        <a href="contacto.php" class="button aceptar">Contáctanos</a>
        <a href="lista-animales-web.php" class="button aceptar">Ver Animales para Adoptar</a>
    </div>
</section>

<?php include '../includes/footer-web.php'; ?>