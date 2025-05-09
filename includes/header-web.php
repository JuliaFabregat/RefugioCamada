<!DOCTYPE html>
<html lang="es-ES">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?>">
    
    <!-- Google Fonts + Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    
    <!-- CSS general -->
    <link rel="stylesheet" href="../css/styles.css">
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="../img/logoColor_blank.png" alt="Logotipo Camada">
</head>

<body>
    <header>
        <div class="container header-web">
            <div class="logo">
                <a href="../web/index.php"><img src="../img/logoCompleto.png" alt="Camada Refugio"></a>
            </div>
            <button class="menu-toggle" aria-label="Abrir menú">☰</button>
            <nav>
                <ul id="menu-web">
                    <li><a href="../web/index.php">INICIO</a></li>
                    <li><a href="lista-animales-web.php">ADOPTA</a></li>
                    <li><a href="quienes-somos.php">QUIÉNES SOMOS</a></li>
                    <li><a href="contacto.php">CONTACTO</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <script>
        document.querySelector('.menu-toggle').addEventListener('click', () => {
            document.getElementById('menu-web').classList.toggle('show');
        });
    </script>