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

    // Método para agregar una nueva fundación (dejé igual porque funciona bien)
    public function registrarFundacion($nombre, $apellido, $contrasena, $email, $direccion, $telefono, $nit_fundacion) {
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO t_usuario (nombre, apellido, contrasena, email, direccion, telefono) 
        VALUES (:nombre, :apellido, :contrasena, :email, :direccion, :telefono)");

        $stmt->bindParam(":nombre", $nombre);
        $stmt->bindParam(":apellido", $apellido);
        $stmt->bindParam(":contrasena", $hash);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":direccion", $direccion);
        $stmt->bindParam(":telefono", $telefono);

        if ($stmt->execute()) {
            $id_usuario = $this->db->lastInsertId();
            $call = $this->db->prepare("CALL crear_fundacion(:id_usuario, :nit_fundacion)");
            $call->bindParam(":id_usuario", $id_usuario, PDO::PARAM_INT);
            $call->bindParam(":nit_fundacion", $nit_fundacion, PDO::PARAM_STR);
            return $call->execute();
        }
        return false;
    }

    // Obtener todas las fundaciones (dejé igual porque funciona bien)
    public function getFundacion() {
        $rows = null;
        $statement = $this->db->prepare("SELECT * FROM t_fundacion");
        $statement->execute();

        while ($resultado = $statement->fetch()) {
            $rows[] = $resultado;
        }
        return $rows;
    }

    // Obtener fundación por NIT (dejé igual porque funciona bien)
    public function getId($nit) {
        $rows = null;
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

    // ACTUALIZAR FUNDACIÓN - CORREGIDO (cambié los parámetros y consulta)
    public function updateFundacion($nit, $nombre, $apellido, $email, $direccion, $telefono) {
        // Primero obtenemos el id_usuario asociado
        $stmt = $this->db->prepare("SELECT id_usuario FROM t_fundacion WHERE nit_fundacion = :nit");
        $stmt->bindParam(':nit', $nit);
        $stmt->execute();
        $fundacion = $stmt->fetch();

        if (!$fundacion) return false;

        $id_usuario = $fundacion['id_usuario'];

        // Actualizamos los datos en t_usuario
        $statement = $this->db->prepare("UPDATE t_usuario 
            SET nombre = :nombre, 
                apellido = :apellido, 
                email = :email, 
                direccion = :direccion, 
                telefono = :telefono 
            WHERE id_usuario = :id_usuario");

        $statement->bindParam(':id_usuario', $id_usuario);
        $statement->bindParam(':nombre', $nombre);
        $statement->bindParam(':apellido', $apellido);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':direccion', $direccion);
        $statement->bindParam(':telefono', $telefono);

        return $statement->execute();
    }

    // Eliminar fundación (dejé igual porque funciona bien)
    public function delete($nit) {
        $stmt = $this->db->prepare("SELECT id_usuario FROM t_fundacion WHERE nit_fundacion = :nit");
        $stmt->bindParam(':nit', $nit);
        $stmt->execute();
        $usuario = $stmt->fetch();

        if (!$usuario) return false;
        $id_usuario = $usuario['id_usuario'];

        $stmt = $this->db->prepare("DELETE FROM t_fundacion WHERE nit_fundacion = :nit");
        $stmt->bindParam(':nit', $nit);
        $result1 = $stmt->execute();

        $stmt = $this->db->prepare("DELETE FROM t_usuario WHERE id_usuario = :id_usuario");
        $stmt->bindParam(':id_usuario', $id_usuario);
        $result2 = $stmt->execute();

        return $result1 && $result2;
    }
}
