<?php
// Se requiere el archivo de conexión con la base de datos

require_once '../../Model/conexion.php';

class Productos
{
    private $db;

    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Registrar nuevo producto
    public function add($nombre, $tipo_producto, $descripcion, $precio, $cantidad_disponible)
    {
        $statement = $this->db->prepare("INSERT INTO t_producto 
            (nombre, tipo_producto, descripcion, precio, cantidad_disponible)
            VALUES (:nombre, :tipo_producto, :descripcion, :precio, :cantidad_disponible)");

        // Vincular los parámetros con los valores recibidos
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':tipo_producto', $tipo_producto);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':precio', $precio);
        $statement->bindParam(':cantidad_disponible', $cantidad_disponible);

        // ❌ Ya NO se redirige, solo se devuelve true/false
        return $statement->execute();
    }

    // Método para obtener todos los productos desde la base de datos
    public function getProducto()
    {
        // Inicializa una variable para almacenar los resultados
        $rows = null;
        // Preparar la consulta SQL para seleccionar todos los productos
        $statement = $this->db->prepare("SELECT * FROM t_producto");
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
        $statement = $this->db->prepare("SELECT * FROM t_producto WHERE id_producto = :id");
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
        $statement = $this->db->prepare("UPDATE t_producto 
            SET nombre = :nombre, 
                tipo_producto = :tipo_producto, 
                descripcion = :descripcion, 
                precio = :precio, 
                cantidad_disponible = :cantidad_disponible 
            WHERE id_producto = :id");

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
        $statement = $this->db->prepare("DELETE FROM t_producto WHERE id_producto = :id");
        $statement->bindParam(':id', $id);

        return $statement->execute(); // Devuelve true o false
    }
}
