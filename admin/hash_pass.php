<?php
// Creación de un hash para crear un nuevo usuario administrador
echo password_hash('adminContraseña', PASSWORD_DEFAULT);
?>