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

    public static function yaAceptada(PDO $pdo, int $animalId): bool
    {
        $sql = "SELECT COUNT(*) FROM solicitudes_adopcion 
                WHERE id_animal = :aid AND resolucion = 'Aceptada'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['aid' => $animalId]);
        return $stmt->fetchColumn() > 0;
    }

    public static function yaSolicitada(PDO $pdo, int $animalId, int $usuarioId): bool
    {
        $sql = "SELECT COUNT(*) FROM solicitudes_adopcion
                WHERE id_usuario = :uid AND id_animal = :aid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['uid' => $usuarioId, 'aid' => $animalId]);
        return $stmt->fetchColumn() > 0;
    }

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
}
