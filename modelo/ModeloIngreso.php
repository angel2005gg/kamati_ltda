<?php
    require_once 'ConexionBD.php';

    class ModeloIngreso{
        private $conexion;
        //Contructor de la clase con la conexion
        public function __construct(){
            $this->conexion = new ConexionBD();
        }

        //Metodo para ingresar un dato en el sistema
        public function setDatos($nombre){
            $sql = "INSERT INTO prueba (nombre) VALUES (?)";
            $statement = $this->conexion->conn->prepare($sql);
            $statement->bind_param("s", $nombre);
            //Verificacion de que el dato si se ingresó
            if($statement->execute()){
                echo "Se ha registrado correctamente";
            }else{
                echo "No se ha registrado nada aún";
            }
            //Cerrar recursos
           
            $statement->close();
        }

    
    }
?>