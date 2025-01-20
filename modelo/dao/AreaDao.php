<?php
require_once '../configuracion/ConexionBD.php';
class AreaDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Metodo de consuulta del is del area relacionado con el cargo
    public function consultarArea($id_Cargo)
    {
        try {
            $conn = $this->conexion->conectarBD();

            // Uso de sentencia preparada
            $sql = "SELECT id_area_fk FROM cargo WHERE id_Cargo = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $id_Cargo);
            $statement->execute();

            // Manejo de resultados
            $result = $statement->get_result();
            if ($result->num_rows === 0) {
                // No se encontraron resultados, manejar apropiadamente según tu lógica de negocio
                return null;
            }

            $idArea = $result->fetch_assoc();

            // Devolver el resultado deseado
            return $idArea['id_area_fk'];
        } catch (Exception $e) {
            // Registrar la excepción para análisis futuro
            error_log('Error en consultarArea: ' . $e->getMessage());

            // Devolver un mensaje de error claro al usuario
            return null; // O un mensaje específico según tu aplicación
        } finally {
            // Liberar recursos y desconectar la base de datos
            if ($statement) {
                $statement->close();
            }
            $this->conexion->desconectarBD();
        }
    }


    //Metodo de consulta de area en selecet

    public function consultaAreasTabla(){
        try{
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_Area, nombre_area FROM area";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            if($result->num_rows> 0){
                $nombreArea = array();
                
                while ($row = $result->fetch_assoc()) {
                    $nombreArea[$row['id_Area']] = $row['nombre_area'];
                }

                if($nombreArea != null){
                    return $nombreArea;
                }else{
                    return false;
                }
            }

            $statement->close();
            $result->close();
            
        }catch(Exception $e){
            return error_log("Error en la consulta de las areas ". $e->getMessage());
        }finally{
            $conn = $this->conexion->desconectarBD();
        }
    }
}
