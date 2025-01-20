<?php

    require_once '../configuracion/ConexionBD.php';

class RecargosDao{

    private $conexion;

    public function __construct(){
        $this->conexion = new ConexionBD();
    }


    //Método de consulta de recargos para 
    public function consultarRecargosId($idRecargos){
        try{
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT idRecargos, nombreRecargo, valorRecargo FROM recargos WHERE idRecargos = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $idRecargos);
            $statement->execute();
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                $recargos = $result->fetch_assoc();

                if ($recargos != null) {
                    return $recargos;
                } else {
                    return false;
                }
            }

            $statement->close();
            $result->close();
        }catch(Exception $e){
            error_log("Error en la consulta de los recargos". $e->getMessage());
        }finally{
            $this->conexion->desconectarBD();
        }
    }
    public function consultarRecargosIdnew($idRecargos){
        try{
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT valorRecargo FROM recargos WHERE idRecargos = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $idRecargos);
            $statement->execute();
            $result = $statement->get_result();
            if ($result->num_rows > 0) {
                $recargos = $result->fetch_assoc();

                if ($recargos != null) {
                    return $recargos;
                } else {
                    return false;
                }
            }

            $statement->close();
            $result->close();
        }catch(Exception $e){
            error_log("Error en la consulta de los recargos". $e->getMessage());
        }finally{
            $this->conexion->desconectarBD();
        }
    }


    //Método para actualizar los recargos por medio del ID
    public function updateRecargos($recargos)
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Obtener los valores de los getters
            $valorRecargo = $recargos->getValorRecargo();
            $idRecargos = $recargos->getIdRecargos();

            // Convertir coma a punto si es necesario
            if (strpos($valorRecargo, ',') !== false) {
                $valorRecargo = str_replace(',', '.', $valorRecargo);
            }

            $sql = "UPDATE recargos SET valorRecargo = ? WHERE idRecargos = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('di', $valorRecargo, $idRecargos);

            if ($statement->execute()) {
                return true;
            } else {
                return false;
            }
            $statement->close();
        } catch (Exception $e) {
            return error_log("Error en la actualizacion del recargo") . $e->getMessage();
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }

}

?>