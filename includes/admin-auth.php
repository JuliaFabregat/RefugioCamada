<?php
session_start();

if (
    !isset($_SESSION['usuario_id'])
 || !isset($_SESSION['usuario_admin'])
 || $_SESSION['usuario_admin'] !== true 
) {
    // redirigimos fuera del admin
    header('Location: ../web/index.php');
    exit;
}
