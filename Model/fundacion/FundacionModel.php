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
    public function registrarFundacion($nombre_rep, $apellido_rep, $contrasena, $email, $direccion, $telefono, $nombre_fundacion, $nit_fundacion)
    {
        // Se encripta la contraseña antes de enviarla al procedimiento
        $hash = password_hash($contrasena, PASSWORD_BCRYPT);

        // Llamamos el procedimiento almacenado con los mismos parámetros que definimos
        $call = $this->db->prepare("CALL crear_fundacion(
        :rep_nombre, :rep_apellido, :rep_contrasena, :rep_email,
        :rep_direccion, :rep_telefono, :fund_nombre, :fund_nit
    )");

        // Se enlazan los parámetros
        $call->bindParam(':rep_nombre', $nombre_rep);
        $call->bindParam(':rep_apellido', $apellido_rep);
        $call->bindParam(':rep_contrasena', $hash);
        $call->bindParam(':rep_email', $email);
        $call->bindParam(':rep_direccion', $direccion);
        $call->bindParam(':rep_telefono', $telefono);
        $call->bindParam(':fund_nombre', $nombre_fundacion); // este es el nombre legal de la fundación
        $call->bindParam(':fund_nit', $nit_fundacion);

        return $call->execute();
    }



    // Obtener todas las fundaciones (dejé igual porque funciona bien)
    public function getFundacion()
    {
        $rows = [];

        // Esta consulta junta fundación + representante (usuario)
        $sql = "SELECT 
                f.nit_fundacion AS nit,
                f.nombre AS nombre_fundacion,
                u.nombre AS nombre_representante,
                u.apellido AS apellido_representante,
                u.email AS correo,
                u.telefono
            FROM t_fundacion f
            INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario";

        $statement = $this->db->prepare($sql);
        $statement->execute();

        // Trae todos los resultados como arreglos asociativos
        while ($resultado = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    // Obtener fundación por NIT (dejé igual porque funciona bien)
    public function getId($nit)
    {
        $statement = $this->db->prepare("
        SELECT 
            f.nit_fundacion,
            f.nombre AS nombre_fundacion,
            u.nombre AS nombre_representante,
            u.apellido AS apellido_representante,
            u.email,
            u.direccion,
            u.telefono
        FROM t_fundacion f
        INNER JOIN t_usuario u ON f.id_usuario = u.id_usuario
        WHERE f.nit_fundacion = :nit
    ");
        $statement->bindParam(':nit', $nit);
        $statement->execute();

        $rows = [];
        while ($resultado = $statement->fetch(PDO::FETCH_ASSOC)) {
            $rows[] = $resultado;
        }

        return $rows;
    }

    // ACTUALIZAR FUNDACIÓN - CORREGIDO (cambié los parámetros y consulta)
    public function updateFundacion($nit, $nombre, $apellido, $email, $direccion, $telefono)
    {
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
    public function delete($nit)
    {
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

    public function getNitsFundacion()
    {
        $nits = [];

        $stmt = $this->db->prepare("SELECT nit_fundacion FROM t_fundacion");
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nits[] = $row;
        }

        return $nits;
    }
}
