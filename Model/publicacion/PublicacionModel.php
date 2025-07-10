<?php
// Se requiere el archivo de conexión con la base de datos

require_once(__DIR__ . '/../conexion.php');

class Publicacion
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Registrar nuevo Publicacion
    public function add($titulo, $contenido, $imagen, $fecha, $nit_fundacion)
    {
        $statement = $this->db->prepare("INSERT INTO t_publicacion 
            (titulo, contenido, imagen, fecha, nit_fundacion)
            VALUES (:titulo, :contenido, :imagen, :fecha, :nit_fundacion)");

        // Vincular los parámetros con los valores recibidos
        $statement->bindParam(':titulo', $titulo);
        $statement->bindParam(':contenido', $contenido);
        $statement->bindParam(':imagen', $imagen);
        $statement->bindParam(':fecha', $fecha);
        $statement->bindParam(':nit_fundacion', $nit_fundacion);

        // ❌ Ya NO se redirige, solo se devuelve true/false
        return $statement->execute();
    }

    // Método para obtener todos los Publicacion desde la base de datos
    public function getPublicacion()
    {
        $rows = null;
        $statement = $this->db->prepare(
                "SELECT p.*, f.nombre AS nombre_fundacion 
            FROM t_publicacion p
            INNER JOIN t_fundacion f ON p.nit_fundacion = f.nit_fundacion"
        );
        $statement->execute();
        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }
        return $rows;
    }

    // Obtener Publicacion por ID
    public function getId($id)
    {
        // Inicializa una variable para almacenar el resultado
        $rows = null;

        // Preparar la consulta SQL para seleccionar un Publicacion por ID
        $statement = $this->db->prepare("SELECT * FROM t_publicacion WHERE id_publicacion = :id");
        // Vincular el parámetro :id con el valor recibido
        $statement->bindParam(':id', $id);
        // Ejecutar la consulta
        $statement->execute();

        // Iterar sobre los resultados y almacenarlos en el array $rows
        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }

        // Devuelve el Publicacion encontrado
        return $rows;
    }

    // Método para actualizar un Publicacion usando su ID
    // Reemplaza el método update con este:
    public function update($id, $titulo, $contenido)
    {
        $statement = $this->db->prepare("UPDATE t_publicacion 
        SET titulo = :titulo, contenido = :contenido
        WHERE id_publicacion = :id");

        $statement->bindParam(':id', $id);
        $statement->bindParam(':titulo', $titulo);
        $statement->bindParam(':contenido', $contenido);

        return $statement->execute();
    }

    // Eliminar Publicacion
    public function delete($id)
    {
        $statement = $this->db->prepare("DELETE FROM t_publicacion WHERE id_publicacion = :id");
        $statement->bindParam(':id', $id);

        return $statement->execute(); // Devuelve true o false
    }




    // MOSTRAR PUBLICACIONES RECIENTES EN EL INICIO
    public function getPublicacionesRecientes($limit, $offset)
    {
        $statement = $this->db->prepare(
            "SELECT p.*, f.nombre AS nombre_fundacion 
         FROM t_publicacion p
         INNER JOIN t_fundacion f ON p.nit_fundacion = f.nit_fundacion
         ORDER BY p.fecha DESC LIMIT :limit OFFSET :offset"
        );
        $statement->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $statement->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $statement->execute();

        $rows = [];
        while ($resultado = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }
        return $rows;
    }

    public function getPublicacionesPorFundacion($nit_fundacion) {
    $sql = "SELECT * FROM t_publicacion WHERE nit_fundacion = :nit";
    $statement = $this->db->prepare($sql);
    $statement->bindParam(':nit', $nit_fundacion);
    $statement->execute();
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
}
