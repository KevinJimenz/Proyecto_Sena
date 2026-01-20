<?php

class Conexion
{
    private $host = "localhost";
    private $dbname = "registro_horas";
    private $user = "root";
    private $password = "";
    private $conexion;

    public function conectar()
    {
        try {
            if ($this->conexion === null) {
                $this->conexion = new PDO("mysql:host={$this->host}; dbname={$this->dbname}", $this->user, $this->password);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $this->conexion;
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
    }

    public function desconectar()
    {
        try {
            if ($this->conexion !== null) {
                $this->conexion = null;
            }
        } catch (PDOException $e) {
            echo "Error al desconectar la base de datos: " . $e->getMessage();
        }
    }

    // Metodo que prepara la consulta para luego ser ejecutada
    public function prepare($sql)
    {
        $this->conectar();
        return $this->conexion->prepare($sql);
    }
}
