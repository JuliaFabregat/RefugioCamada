<?php

// YA NO SE USA, SE USA LA CLASE Conexion.php PERO SE MANTIENE PARA VER LA EVOLUCION DE LA CONEXIÓN
$type     = 'mysql';
$server   = 'localhost';
$db       = 'refugio-animales';
$port     = '';
$charset  = 'utf8mb4';

$username = 'testuser';
$password = '1234';

// $username = 'adminCamada';
// $password = 'W-O5lPxxiFfntx3h';

$options  = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Conexión
$dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), $e->getCode());
}