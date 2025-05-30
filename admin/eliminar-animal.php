<?php
declare(strict_types = 1);
require __DIR__ . '/../includes/admin-auth.php';
require_once '../models/Conexion.php';
require '../includes/functions.php';

// Obtener conexión
$pdo = Conexion::obtenerConexion();

// Obtenemos el id del animal a eliminar
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    redirect('lista-animales.php');
}

try {
    // Obtenemos la info de la imagen y de la ficha veterinaria
    $sql = "SELECT nombre, imagen_id, vet_data_id FROM animales WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$result) {
        redirect('lista-animales.php', ['error' => 'Animal no encontrado']);
    }
    
    $imagen_id = $result['imagen_id'];
    $vet_data_id = $result['vet_data_id'];

    // Eliminamos el animal
    $sql = "DELETE FROM animales WHERE id = :id";
    pdo($pdo, $sql, ['id' => $id]);

    // Eliminar la imagen asociada
    if ($imagen_id) {
        // Obtener nombre del archivo
        $sql_imagen = "SELECT imagen FROM imagenes WHERE id = :imagen_id";
        $stmt_imagen = $pdo->prepare($sql_imagen);
        $stmt_imagen->execute(['imagen_id' => $imagen_id]);
        $nombre_imagen = $stmt_imagen->fetchColumn();

        // Eliminar registro de la tabla imagenes
        $sql_delete = "DELETE FROM imagenes WHERE id = :imagen_id";
        pdo($pdo, $sql_delete, ['imagen_id' => $imagen_id]);

        // Eliminar archivo físico
        $ruta_archivo = "../uploads/" . $nombre_imagen;
        if (file_exists($ruta_archivo)) {
            unlink($ruta_archivo);
        }
    }

    // Eliminar la ficha veterinaria asociada
    if ($vet_data_id) {
        $sql_delete_vet = "DELETE FROM vet_data WHERE id = :vet_data_id";
        pdo($pdo, $sql_delete_vet, ['vet_data_id' => $vet_data_id]);
    } 

    redirect('lista-animales.php', ['deleted' => "Animal {$result['nombre']} eliminado correctamente"]);
} catch (PDOException $e) {
    redirect('lista-animales.php', ['error' => 'Error al eliminar: ' . $e->getMessage()]);
}
?>