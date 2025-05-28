<?php
class Conexion {
    private static $pdo;

    public static function obtenerConexion() {
        if (!isset(self::$pdo)) {
            $type     = 'mysql';
            $server   = 'localhost';
            $db       = 'refugio-animales';
            $port     = '';
            $charset  = 'utf8mb4';

            $username = 'testuser';
            $password = '1234';

            $options  = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $dsn = "$type:host=$server;dbname=$db;port=$port;charset=$charset";
            try {
                self::$pdo = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), (int)$e->getCode());
            }
        }

        return self::$pdo;
    }
}
