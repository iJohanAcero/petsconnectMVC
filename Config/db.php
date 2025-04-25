<?php
require_once'parameters.php';
class Database {
    private $host = DB_HOST;
    private $db_name = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS; 
    public $conn;

    public function conectar() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error){
            die ("Conexion fallida".$this->conn->connect_error);

        } 
        return $this->conn;
    }
}