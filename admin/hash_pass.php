<?php
// Creación de un hash para crear un nuevo usuario administrador en caso de necesitarlo
echo password_hash('adminContraseña', PASSWORD_DEFAULT);
?>