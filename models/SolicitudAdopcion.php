<?php

declare(strict_types=1);

class SolicitudAdopcion
{
    private int $idUsuario;
    private int $idAnimal;
    private string $fecha;
    private string $resolucion;

    public function __construct(int $idUsuario, int $idAnimal, string $resolucion = 'En proceso')
    {
        $this->idUsuario = $idUsuario;
        $this->idAnimal = $idAnimal;
        $this->fecha = date('Y-m-d H:i:s');
        $this->resolucion = $resolucion;
    }

    // Verifica si ya hay una solicitud aceptada para un animal
    public static function yaAceptada(PDO $pdo, int $animalId): bool
    {
        $sql = "SELECT COUNT(*) FROM solicitudes_adopcion 
                WHERE id_animal = :aid AND resolucion = 'Aceptada'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['aid' => $animalId]);
        return $stmt->fetchColumn() > 0;
    }

    // Verifica si el usuario ya ha solicitado adoptar un animal
    public static function yaSolicitada(PDO $pdo, int $animalId, int $usuarioId): bool
    {
        $sql = "SELECT COUNT(*) FROM solicitudes_adopcion
                WHERE id_usuario = :uid AND id_animal = :aid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uid' => $usuarioId, 'aid' => $animalId]);
        return $stmt->fetchColumn() > 0;
    }

    // Guardar una nueva solicitud de adopción
    public function guardar(PDO $pdo): void
    {
        $sql = "INSERT INTO solicitudes_adopcion (id_usuario, id_animal, fecha, resolucion)
                VALUES (:uid, :aid, :fecha, :resol)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'uid'    => $this->idUsuario,
            'aid'    => $this->idAnimal,
            'fecha'  => $this->fecha,
            'resol'  => $this->resolucion
        ]);
    }

    // Obtener todas las solicitudes de adopción con usuario y animal
    public static function obtenerPorUsuario(PDO $pdo, int $userId): array
    {
        $sql = "SELECT s.id, s.fecha, s.resolucion,
                       a.nombre AS animal_nombre, a.id AS animal_id
                FROM solicitudes_adopcion s
                JOIN animales a ON s.id_animal = a.id
                WHERE s.id_usuario = :uid
                ORDER BY s.fecha DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener todas las solicitudes de adopción con detalles de usuario y animal
    public static function obtenerTodas(PDO $pdo): array
    {
        $sql = "SELECT s.id, s.fecha, s.resolucion,
                   u.nombre AS usuario_nombre, u.apellidos AS usuario_apellidos, u.email AS usuario_email,
                   a.nombre AS animal_nombre
            FROM solicitudes_adopcion s
            JOIN usuarios u   ON s.id_usuario = u.id
            JOIN animales a   ON s.id_animal  = a.id
            ORDER BY s.fecha DESC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener el ID del animal asociado a una solicitud
    public static function obtenerAnimalId(PDO $pdo, int $solicitudId): ?int
    {
        $sql = "SELECT id_animal FROM solicitudes_adopcion WHERE id = :id LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $solicitudId]);
        return $stmt->fetchColumn() ?: null;
    }

    // Aceptar una solicitud de adopción
    public static function aceptar(PDO $pdo, int $solicitudId): void
    {
        $animalId = self::obtenerAnimalId($pdo, $solicitudId);
        if ($animalId === null) return;

        if (self::yaAceptada($pdo, $animalId)) {
            header('Location: solicitudes.php?error=Ya hay una solicitud aceptada para este animal');
            exit;
        }

        $pdo->beginTransaction();

        // Denegar todas las demás solicitudes para este animal
        $sql1 = "UPDATE solicitudes_adopcion SET resolucion = 'Denegada' WHERE id_animal = :aid";
        $sql2 = "UPDATE solicitudes_adopcion SET resolucion = 'Aceptada' WHERE id = :id";
        $sql3 = "UPDATE animales SET estado = 'Adoptado' WHERE id = :aid";

        $pdo->prepare($sql1)->execute(['aid' => $animalId]);
        $pdo->prepare($sql2)->execute(['id' => $solicitudId]);
        $pdo->prepare($sql3)->execute(['aid' => $animalId]);

        $pdo->commit();
    }

    // Denegar una solicitud de adopción
    // Si es la única, cambiar el estado del animal a "Disponible"
    public static function denegar(PDO $pdo, int $solicitudId): void
    {
        $animalId = self::obtenerAnimalId($pdo, $solicitudId);
        if ($animalId === null) return;

        $pdo->beginTransaction();

        // Denegar solo esta
        $sql = "UPDATE solicitudes_adopcion SET resolucion = 'Denegada' WHERE id = :id";
        $pdo->prepare($sql)->execute(['id' => $solicitudId]);

        // Verificar si quedan solicitudes aceptadas o en proceso
        $sqlCheck = "SELECT COUNT(*) FROM solicitudes_adopcion
                 WHERE id_animal = :aid AND resolucion != 'Denegada'";
        $count = $pdo->prepare($sqlCheck);
        $count->execute(['aid' => $animalId]);

        if ((int)$count->fetchColumn() === 0) {
            $sqlUpdate = "UPDATE animales SET estado = 'Disponible' WHERE id = :aid";
            $pdo->prepare($sqlUpdate)->execute(['aid' => $animalId]);
        }

        $pdo->commit();
    }
}
