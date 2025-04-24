<?php

class Conexion {
    protected $db;     // Propiedad protegida para usar la conexión en clases hijas

   // Propiedades privadas de conexión
    private $driver = "mysql";
    private $host = "localhost";
    private $port = '3306';
    private $dbname = "petsconnect";
    private $user = "root";
    private $password = "";

    public function __construct() 
    {
        try {
            $this->db = new PDO(
                "{$this->driver}:host={$this->host};port={$this->port};dbname={$this->dbname}", $this->user, $this->password);
            $this->db->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error en la conexion: " . $e->getMessage());
        }
    }
    public function getConexion() {
        return $this->db;
    }
}
?>