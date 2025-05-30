<?php
require_once 'Conexion.php';

class Animal
{
    // Propiedades
    private int $id;
    private string $nombre;
    private string $edad;
    private string $descripcion;
    private string $imagen;
    private string $estado;
    private string $genero;
    private int $especie_id;
    private int $raza_id;
    private int $vet_data_id;

    // Constructor completo
    public function __construct(
        int $id,
        string $nombre,
        string $edad,
        string $descripcion,
        string $imagen,
        string $estado,
        string $genero,
        int $especie_id,
        int $raza_id,
        int $vet_data_id
    ) {
        $this->id = $id;
        $this->setNombre($nombre);
        $this->setEdad($edad);
        $this->setDescripcion($descripcion);
        $this->setImagen($imagen);
        $this->setEstado($estado);
        $this->setGenero($genero);
        $this->setEspecieId($especie_id);
        $this->setRazaId($raza_id);
        $this->setVetDataId($vet_data_id);
    }

    // Constructor alternativo desde array
    public static function crearDesdeArray(array $data): Animal
    {
        return new Animal(
            $data['id'] ?? 0,
            $data['nombre'] ?? '',
            $data['edad'] ?? '',
            $data['descripcion'] ?? '',
            $data['imagen'] ?? '',
            $data['estado'] ?? 'Disponible',
            $data['genero'] ?? '',
            $data['especie_id'] ?? 0,
            $data['raza_id'] ?? 0,
            $data['vet_data_id'] ?? 0
        );
    }

    // Getters tipados
    public function getId(): int
    {
        return $this->id;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getEdad(): string
    {
        return $this->edad;
    }
    public function getDescripcion(): string
    {
        return $this->descripcion;
    }
    public function getImagen(): string
    {
        return $this->imagen;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }
    public function getGenero(): string
    {
        return $this->genero;
    }
    public function getEspecieId(): int
    {
        return $this->especie_id;
    }
    public function getRazaId(): int
    {
        return $this->raza_id;
    }
    public function getVetDataId(): int
    {
        return $this->vet_data_id;
    }

    // Setters tipados con validación básica
    public function setNombre(string $nombre): void
    {
        $this->nombre = trim($nombre);
    }
    public function setEdad(string $edad): void
    {
        $this->edad = trim($edad);
    }
    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = trim($descripcion);
    }
    public function setImagen(string $imagen): void
    {
        $this->imagen = trim($imagen);
    }
    public function setEstado(string $estado): void
    {
        $estadosValidos = ['Disponible', 'En proceso', 'Adoptado'];
        $this->estado = in_array($estado, $estadosValidos) ? $estado : 'Disponible';
    }
    public function setGenero(string $genero): void
    {
        $genero = strtolower($genero);
        $this->genero = in_array($genero, ['macho', 'hembra']) ? $genero : 'macho';
    }
    public function setEspecieId(int $especie_id): void
    {
        $this->especie_id = $especie_id;
    }
    public function setRazaId(int $raza_id): void
    {
        $this->raza_id = $raza_id;
    }
    public function setVetDataId(int $vet_data_id): void
    {
        $this->vet_data_id = $vet_data_id;
    }


    // Obtener todos los animales
    public static function obtenerTodos(PDO $pdo): array
    {
        $sql = "SELECT * FROM animales";
        return self::ejecutarConsulta($pdo, $sql);
    }

    // Obtener últimos 4 animales - ADMIN
    public static function obtenerUltimos(PDO $pdo, int $limite = 4): array
    {
        $sql = "SELECT 
                a.id, 
                a.nombre, 
                r.nombre AS raza,
                a.edad, 
                a.genero, 
                a.joined,
                e.especie AS especie,
                i.imagen AS image_file,
                i.alt AS image_alt
            FROM animales AS a
            JOIN especies AS e ON a.especie_id = e.id
            JOIN raza AS r ON a.raza_id = r.id
            LEFT JOIN imagenes AS i ON a.imagen_id = i.id
            ORDER BY a.joined DESC
            LIMIT :limite";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Obtener estadísticas del refugio - ADMIN
    public static function obtenerEstadisticas(PDO $pdo): array
    {
        $sql = "SELECT 
                COUNT(*) AS total,
                SUM(estado = 'Disponible') AS disponibles,
                SUM(estado = 'Adoptado') AS adoptados
            FROM animales;";
        return $pdo->query($sql)->fetch();
    }

    public static function obtenerEspecies(PDO $pdo): array
    {
        $sql = "SELECT id, especie FROM especies ORDER BY especie";
        return self::ejecutarConsulta($pdo, $sql);
    }

    public static function obtenerRazas(PDO $pdo): array
    {
        $sql = "SELECT id, nombre FROM raza ORDER BY nombre";
        return self::ejecutarConsulta($pdo, $sql);
    }

    // Obtener un animal por ID con detalles de especie, raza e imagen
    public static function obtenerPorId(PDO $pdo, int $id): ?array
    {
        try {
            $sql = "SELECT 
                        a.*,
                        r.nombre AS raza,
                        e.especie, e.descripcion AS especie_descripcion,
                        i.imagen AS image_file, i.alt AS image_alt,
                        v.microchip, v.castracion, v.vacunas, v.info_adicional
                    FROM animales a
                    JOIN especies e ON a.especie_id = e.id
                    JOIN raza r ON a.raza_id = r.id
                    LEFT JOIN imagenes i ON a.imagen_id = i.id
                    LEFT JOIN vet_data v ON a.vet_data_id = v.id
                    WHERE a.id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("Error al obtener animal por ID: " . $e->getMessage());
            return null;
        }
    }

    // Cambiar entre fichas de Animales
    public static function obtenerAnterior(PDO $pdo, int $id): ?int
    {
        $sql = "SELECT id FROM animales WHERE id < :id ORDER BY id DESC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() ?: null;
    }

    public static function obtenerSiguiente(PDO $pdo, int $id): ?int
    {
        $sql = "SELECT id FROM animales WHERE id > :id ORDER BY id ASC LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchColumn() ?: null;
    }

    // Listar animales con paginación y filtros
    public static function listarConFiltros(PDO $pdo, $especieId, string $nombre, int $pagina, int $porPagina): array
    {
        $conds = [];
        $params = [];

        if ($especieId !== '' && ctype_digit((string)$especieId)) {
            $conds[] = 'a.especie_id = :esp';
            $params['esp'] = $especieId;
        }

        if ($nombre !== '') {
            $conds[] = 'a.nombre LIKE :nombre';
            $params['nombre'] = '%' . $nombre . '%';
        }

        $where = $conds ? 'WHERE ' . implode(' AND ', $conds) : '';
        $countSql = "SELECT COUNT(*) FROM animales a $where";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $pages = (int)ceil($total / $porPagina);
        $offset = ($pagina - 1) * $porPagina;

        $sql = "
            SELECT 
                a.id, a.nombre, a.edad, a.genero,
                e.especie, r.nombre AS raza,
                i.imagen AS image_file, i.alt AS image_alt
            FROM animales a
            JOIN especies e ON a.especie_id = e.id
            JOIN raza r ON a.raza_id = r.id
            LEFT JOIN imagenes i ON a.imagen_id = i.id
            $where
            ORDER BY a.id DESC
            LIMIT :lim OFFSET :off
        ";

        $params['lim'] = $porPagina;
        $params['off'] = $offset;

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $val) {
            $stmt->bindValue(":$key", $val, is_int($val) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();
        $animales = $stmt->fetchAll();

        return ['animales' => $animales, 'total' => $total, 'pages' => $pages];
    }

    // Utilidad: ejecutar consulta y devolver array
    private static function ejecutarConsulta(PDO $pdo, string $sql, array $params = []): array
    {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener animales filtrados - ADMIN
    public static function obtenerFiltrados(PDO $pdo, array $filtros = []): array
    {
        $sql = "SELECT 
                a.id,
                a.nombre,
                a.edad,
                a.estado,
                e.especie,
                i.imagen AS image_file
            FROM animales AS a
            JOIN especies AS e ON a.especie_id = e.id
            LEFT JOIN imagenes AS i ON a.imagen_id = i.id";

        $condiciones = [];
        $params = [];

        if (!empty($filtros['especie'])) {
            $condiciones[] = "e.id = :especie_id";
            $params[':especie_id'] = $filtros['especie'];
        }

        if (!empty($filtros['nombre'])) {
            $condiciones[] = "a.nombre LIKE :nombre";
            $params[':nombre'] = '%' . $filtros['nombre'] . '%';
        }

        if (!empty($filtros['estado'])) {
            $condiciones[] = "a.estado = :estado";
            $params[':estado'] = $filtros['estado'];
        }

        if ($condiciones) {
            $sql .= ' WHERE ' . implode(' AND ', $condiciones);
        }

        $sql .= ' ORDER BY a.nombre ASC';

        $stmt = $pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
