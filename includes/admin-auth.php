<?php
session_start();

if (
    !isset($_SESSION['user_id'])
 || !isset($_SESSION['is_admin'])
 || $_SESSION['is_admin'] !== true 
) {
    // redirigimos fuera del admin
    header('Location: ../web/index.php');
    exit;
}
