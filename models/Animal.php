<?php
require_once 'Conexion.php';

class Animal
{
    // Propiedades privadas
    private $id;
    private $nombre;
    private $edad;
    private $descripcion;
    private $imagen;
    private $estado;
    private $genero;
    private $especie_id;
    private $raza_id;
    private $vet_data_id;

    // Constructor
    public function __construct($id, $nombre, $edad, $descripcion, $imagen, $estado, $genero, $especie_id, $raza_id, $vet_data_id)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->edad = $edad;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
        $this->estado = $estado;
        $this->genero = $genero;
        $this->especie_id = $especie_id;
        $this->raza_id = $raza_id;
        $this->vet_data_id = $vet_data_id;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function getEdad()
    {
        return $this->edad;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }
    public function getImagen()
    {
        return $this->imagen;
    }
    public function getEstado()
    {
        return $this->estado;
    }
    public function getGenero()
    {
        return $this->genero;
    }
    public function getEspecieId()
    {
        return $this->especie_id;
    }
    public function getRazaId()
    {
        return $this->raza_id;
    }
    public function getVetDataId()
    {
        return $this->vet_data_id;
    }

    // Setters
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    public function setEdad($edad)
    {
        $this->edad = $edad;
    }
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }
    public function setEstado($estado)
    {
        $this->estado = $estado;
    }
    public function setGenero($genero)
    {
        $this->genero = $genero;
    }
    public function setEspecieId($especie_id)
    {
        $this->especie_id = $especie_id;
    }
    public function setRazaId($raza_id)
    {
        $this->raza_id = $raza_id;
    }
    public function setVetDataId($vet_data_id)
    {
        $this->vet_data_id = $vet_data_id;
    }

    // Obtener todos los animales
    public static function obtenerTodos()
    {
        $conexion = Conexion::obtenerConexion();
        $stmt = $conexion->prepare("SELECT * FROM animales");
        $stmt->execute();

        $resultados = [];
        while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $animal = new Animal(
                $fila['id'],
                $fila['nombre'],
                $fila['edad'],
                $fila['descripcion'],
                $fila['imagen'],
                $fila['estado'],
                $fila['genero'],
                $fila['especie_id'],
                $fila['raza_id'],
                $fila['vet_data_id']
            );
            $resultados[] = $animal;
        }

        return $resultados;
    }

    // Obtener animal por ID
    public static function obtenerPorId(PDO $pdo, int $id): ?array
    {
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
        $animal = $stmt->fetch(PDO::FETCH_ASSOC);

        return $animal ?: null;
    }

    // Obtener el ID del animal anterior y siguiente
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

    // Obtener la lista de especies
    public static function obtenerEspecies(PDO $pdo): array
    {
        $sql = "SELECT id, especie FROM especies ORDER BY especie";
        return pdo($pdo, $sql)->fetchAll();
    }

    /**
     * Obtenemos la lista de animales con filtros y paginación.
     * 
     * @param PDO $pdo
     * @param string|int|null $especieId
     * @param string $nombre
     * @param int $pagina
     * @param int $porPagina
     * @return array [animales => array, total => int]
     */

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

        // Total
        $countSql = "SELECT COUNT(*) FROM animales a $where";
        $total = (int) pdo($pdo, $countSql, $params)->fetchColumn();

        // Paginación
        $pages = (int) ceil($total / $porPagina);
        $offset = ($pagina - 1) * $porPagina;

        // Consulta datos
        $sql = "
            SELECT 
                a.id, a.nombre, a.edad, a.genero, a.joined,
                e.especie, r.nombre AS raza,
                i.imagen AS image_file, i.alt AS image_alt
            FROM animales a
            JOIN especies e ON a.especie_id = e.id
            JOIN raza r ON a.raza_id = r.id
            LEFT JOIN imagenes i ON a.imagen_id = i.id
            $where
            ORDER BY a.joined DESC
            LIMIT :lim OFFSET :off
        ";
        $params['lim'] = $porPagina;
        $params['off'] = $offset;

        $stmt = pdo($pdo, $sql, $params);
        $animales = $stmt->fetchAll();

        return ['animales' => $animales, 'total' => $total, 'pages' => $pages];
    }
}