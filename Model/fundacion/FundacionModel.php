<?php

require_once '../../Model/conexion.php';

class Fundacion

{
    private $db;
    // Constructor de la clase
    public function __construct()
    {
        $this->db = (new Conexion())->getConexion();
    }

    // Método para agregar un nueva fundacion a la base de datos
    public function registrarFundacion($nombre, $apellido, $contrasena, $email, $direccion, $telefono, $nit_fundacion)
    {
        // Encriptar la contraseña
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);

        // Preparar la consulta para insertar en t_usuario
        $stmt = $this->db->prepare("INSERT INTO t_usuario (nombre, apellido, contrasena, email, direccion, telefono) 
        VALUES (:nombre, :apellido, :contrasena, :email, :direccion, :telefono)");

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellido", $apellido);
        $stmt->bindParam(":contrasena", $hash);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":telefono", $telefono);

        // Si se insertó correctamente el usuario
        if ($stmt->execute()) {
            $id_usuario = $this->db->lastInsertId();

            // Llamar al procedimiento para registrar fundación
            $call = $this->db->prepare("CALL crear_fundacion(:id_usuario, :nit_fundacion)");
            $call->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $call->bindParam(":nit_fundacion", $nit_fundacion, PDO::PARAM_STR);
            $call->execute();

            return true;
        }

        return false;
    }


    public function getFundacion()
    {
        // Inicializa una variable para almacenar los resultados
        $rows = null;
        // Preparar la consulta SQL para seleccionar todos los productos
        $statement = $this->db->prepare("SELECT * FROM t_fundacion");
        // Ejecutar la consulta
        $statement->execute();

        // Iterar sobre los resultados obtenidos y almacenarlos en el array $rows
        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }

        // Devuelve todos los productos obtenidos
        return $rows;
    }

    // Obtener producto por nit
    public function getId($nit)
    {
        // Inicializa una variable para almacenar el resultado
        $rows = null;

        // Preparar la consulta SQL para seleccionar un producto por nit
        $statement = $this->db->prepare("SELECT * FROM t_fundacion WHERE nit_fundacion = :nit");
        // Vincular el parámetro :nit con el valor recibido
        $statement->bindParam(':nit', $nit);
        // Ejecutar la consulta
        $statement->execute();

        // Iterar sobre los resultados y almacenarlos en el array $rows
        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }

        // Devuelve el producto encontrado
        return $rows;
    }

    public function updateFundacion($id, $nombre, $tipo_producto, $descripcion, $precio, $cantidad_disponible)
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

    public function delete($nit)
    {
        $statement = $this->db->prepare("DELETE FROM t_fundacion WHERE nit_fundacion = :nit");
        $statement->bindParam(':nit', $nit);

        return $statement->execute(); // Devuelve true o false
    }
}
