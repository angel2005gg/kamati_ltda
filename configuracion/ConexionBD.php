<?php

class ConexionBD
{
    private const NOMBRE_SERVIDOR = "localhost";
    private const NOMBRE_BASE_DATOS = "kamajgwb_intranet_Kamati_LTDA";
    private const USUARIO = "kamajgwb_intranet_Kamati_LTDA";
    private const CONTRASENA = "**#8IntrZWTLhNetJ7s9BKamaMG3tiw#**";
    public $conn;

    public function conectarBD()
    {
        // Se conecta a la base de datos
        $this->conn = new mysqli(self::NOMBRE_SERVIDOR, self::USUARIO, self::CONTRASENA, self::NOMBRE_BASE_DATOS);

        // Verificar errores en la conexión
        if ($this->conn->connect_error) {
            die("Error de conexión: " . $this->conn->connect_error);
        }

        // Configurar el conjunto de caracteres
        $this->conn->set_charset("utf8mb4");

        return $this->conn;
    }

    public function desconectarBD()
    {
        // Se valida si la conexión está activa y la cierra
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

?>