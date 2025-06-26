<?php
// Se requiere el archivo de conexión con la base de datos

require_once '../../Model/conexion.php';

class Publicacion
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Registrar nuevo producto
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

    // Método para obtener todos los productos desde la base de datos
    public function getPublicacion()
    {
        // Inicializa una variable para almacenar los resultados
        $rows = null;
        // Preparar la consulta SQL para seleccionar todos los productos
        $statement = $this->db->prepare("SELECT * FROM t_publicacion");
        // Ejecutar la consulta
        $statement->execute();

        // Iterar sobre los resultados obtenidos y almacenarlos en el array $rows
        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }

        // Devuelve todos los productos obtenidos
        return $rows;
    }

    // Obtener producto por ID
    public function getId($id)
    {
        // Inicializa una variable para almacenar el resultado
        $rows = null;

        // Preparar la consulta SQL para seleccionar un producto por ID
        $statement = $this->db->prepare("SELECT * FROM t_publicacion WHERE id_publicacion = :id");
        // Vincular el parámetro :id con el valor recibido
        $statement->bindParam(':id', $id);
        // Ejecutar la consulta
        $statement->execute();

        // Iterar sobre los resultados y almacenarlos en el array $rows
        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }

        // Devuelve el producto encontrado
        return $rows;
    }

    // Método para actualizar un producto usando su ID
    // Reemplaza el método update con este:
    public function update($id, $nombre, $tipo_producto, $descripcion, $precio, $cantidad_disponible)
    {
        $statement = $this->db->prepare("UPDATE t_publicacion 
            (titulo, contenido, imagen, fecha, nit_fundacion)
            SET (:titulo, :contenido, :imagen, :fecha, :nit_fundacion)
            WHERE id_publicacion = :id");

        $statement->bindParam(':id', $id);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':tipo_producto', $tipo_producto);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':precio', $precio);
        $statement->bindParam(':cantidad_disponible', $cantidad_disponible);

        return $statement->execute();
    }

    // Eliminar producto
    public function delete($id)
    {
        $statement = $this->db->prepare("DELETE FROM t_publicacion WHERE id_publicacion = :id");
        $statement->bindParam(':id', $id);

        return $statement->execute(); // Devuelve true o false
    }
}
