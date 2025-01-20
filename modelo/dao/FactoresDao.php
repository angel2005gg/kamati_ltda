<?php
require_once '../configuracion/ConexionBD.php';
class FactoresDao
{

    private $conexion;


    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Método de consulta de los Factores
    public function consultarFactores($idFactores)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_Factores, factores, valorFactor FROM factorescomercial WHERE id_Factores = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param("i", $idFactores);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $factores = $result->fetch_assoc();

                if ($factores != null) {
                    return $factores;
                } else {
                    return false;
                }
            }

            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("No se ha podido consultar los factores" . $e->getMessage());
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }

    //Metodo de actualizacion de variables 
    public function updateFactores($factores)
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Obtener los valores de los getters
            $valor_Factor = $factores->getFactores();
            $id_Factor = $factores->getId_Factores();

            // Convertir coma a punto si es necesario
            if (strpos($valor_Factor, ',') !== false) {
                $valor_Factor = str_replace(',', '.', $valor_Factor);
            }

            $sql = "UPDATE factorescomercial SET valorFactor = ? WHERE id_Factores = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('di', $valor_Factor, $id_Factor);

            if ($statement->execute()) {
                return true;
            } else {
                return false;
            }
            $statement->close();
        } catch (Exception $e) {
            return error_log("Error en la actualizacion de la variable ") . $e->getMessage();
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }
}
