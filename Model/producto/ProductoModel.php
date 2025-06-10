<?php
// Se requiere el archivo de conexión con la base de datos

require_once '../../Model/conexion.php';


// Definición de la clase Producto que hereda de la clase Conexion
class Productos
{
    private $db;
    // Constructor de la clase
    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Método para agregar un nuevo producto a la base de datos
    public function add($nombre, $tipo_producto, $descripcion, $precio, $cantidad)
    {
        // Preparar la consulta SQL para insertar un nuevo producto en la base de datos
        $statement = $this->db->prepare("INSERT INTO t_producto (nombre, tipo_producto, descripcion, precio, cantidad_disponible)
                                        VALUES (:nombre, :tipo_producto, :descripcion, :precio, :cantidad)");

        // Vincular los parámetros con los valores recibidos
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':tipo_producto', $tipo_producto);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':precio', $Precio);
        $statement->bindParam(':cantidad', $Cantidad);

        // Ejecutar la consulta
        if ($statement->execute()) {
            // Si la consulta es exitosa, redirige a la página de inicio
            header("Location: ../../view/producto/ProductoView.php");
        } else {
           //si la consulta falla, mostrar una alerta de error
            echo "<script>alert('Error al registrar.');</script>";
        }
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

    // Método para obtener un producto específico usando su ID
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
    public function update($id, $nombre, $tipo_producto, $descripcion,  $precio, $cantidad)
    {
        // Preparar la consulta SQL para actualizar el producto con el ID correspondiente
        $statement = $this->db->prepare("UPDATE t_producto 
                                    SET nombre = :nombre, tipo_producto = :tipo, descripcion = :descripcion, precio = :precio, cantidad_disponible = :cantidad 
                                    WHERE id_producto = :id");

        // Vincular los parámetros con los valores recibidos (sin cambiar el id)
        $statement->bindParam(':id', $id);  // El ID es solo para buscar el registro a actualizar
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':tipo', $tipo_producto);
        $statement->bindParam(':descripcion', $descripcion);
        $statement->bindParam(':precio', $precio);
        $statement->bindParam(':cantidad', $cantidad);

        // Ejecutar la consulta
        if ($statement->execute()) {
            // Si la actualización es exitosa, redirige a la página de inicio
            header("Location: ../../view/producto/ProductoView.php");
        } else {
            // Si la actualización falla, redirige a la página de editar para intentar de nuevo
            header("Location: ../../view/producto/edit.php");
        }
    }

    // Método para eliminar un producto usando su ID
    public function delete($Id)
    {
        // Preparar la consulta SQL para eliminar el producto con el ID correspondiente
        $statement = $this->db->prepare("DELETE FROM t_producto WHERE id_producto = :id");
        // Se usa ':id' como marcador para evitar inyecciones SQL y se enlaza con bindParam() para asignar el valor de forma segura

        $statement->bindParam(':id', $Id);

        // Ejecutar la consulta
        if ($statement->execute()) {
            // Si la eliminación es exitosa, redirige a la página de inicio
            header("Location: ../../view/producto/ProductoView.php");
        } else {
            // Si la eliminación falla, redirige a la página de eliminación para intentar de nuevo
            header("Location: ../../view/producto/delete.php");
        }
    }
}
