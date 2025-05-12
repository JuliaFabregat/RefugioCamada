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
        <div class="historia-texto">
            <h2>Nuestra Historia</h2>
            <p>
                Somos una organización protectora de animales independiente, fundada en 2018 en Baena,
                que no recibe subvenciones de organismos oficiales, empresas ni partidos políticos.
                Gracias a las cuotas de nuestros socios y donaciones de simpatizantes, mantenemos
                nuestra labor sostenible y preservamos nuestra libertad de acción.
            </p>
            <p>
                Contamos con un centro veterinario abierto al público en Madrid, cuyos ingresos
                se destinan íntegramente al cuidado de animales abandonados, y un centro de
                acogida en El Espinar (Segovia).
            </p>
            <p>
                Nuestra acción se divide en dos ámbitos:
            </p>
            <ul>
                <li>
                    <strong>Acción directa:</strong> Rescate de perros y gatos víctimas de maltrato
                    o abandono, atención veterinaria completa (analíticas, tratamientos, cirugías),
                    identificación, esterilización y búsqueda de adoptantes responsables.
                </li>
                <li>
                    <strong>Acción divulgativa:</strong> Campañas en medios de comunicación para
                    educar en el respeto animal y defender derechos básicos como el libre acceso
                    de mascotas a espacios públicos, siempre promoviendo la tenencia responsable.
                </li>
            </ul>
            <p>
                Defendemos el <em>sacrificio cero</em>, entendiendo la eutanasia únicamente
                en casos de sufrimiento irreversible por enfermedad terminal, siempre con
                la dignidad y el cariño que nuestros animales merecen.
            </p>
        </div>
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