<!DOCTYPE html>
<html lang="es-ES">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= html_escape($title) ?></title>
    <meta name="description" content="<?= html_escape($description) ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="../css/styles.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/png" href="../img/logoColor_blank.png" alt="Logotipo Camada">
  </head>

  <body>

  <header>
    <div class="container">

      <div class="logo">
        <a href="index.php"><img src="../img/logoCompleto.png" alt="Logotipo Camada - Refugio Animales"></a>
      </div>

      <button class="menu-toggle" aria-label="Abrir menú">☰</button>

      <nav>
        <ul id="menu">
          <li>
            <a href="index.php" <?= ($section == 'Inicio') ? 'class="on" aria-current="page"' : '' ?>>INICIO</a>
          </li>
          <li>
            <a href="lista-animales.php" <?= ($section == 'listaAnimales') ? 'class="on" aria-current="page"' : '' ?>>LISTA</a>
          </li>
          <li>
            <a href="agregar-animales.php" <?= ($section == 'agregarAnimales') ? 'class="on" aria-current="page"' : '' ?>>AGREGAR</a>
          </li>
          <li>
            <a href="logout.php" class="btn btn-danger">LOGOUT</a>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <script>
    const toggleBtn = document.querySelector('.menu-toggle');
    const menu = document.getElementById('menu');

    toggleBtn.addEventListener('click', () => {
      menu.classList.toggle('show');
    });
  </script>
