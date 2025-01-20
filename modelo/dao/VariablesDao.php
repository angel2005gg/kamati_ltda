<?php
require_once '../configuracion/ConexionBD.php';
class VariablesDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Metodo de consulta de las variables que existen en la base de datos
    public function consultaVariables($id_variable)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_Variables, nombre_variables, valor_variable FROM variables WHERE id_Variables = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $id_variable);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $variables = $result->fetch_assoc();

                if ($variables != null) {
                    return $variables;
                } else {
                    return false;
                }
            }

            $statement->close();
            $result->close();
        } catch (Exception $e) {
            return error_log("Error en la consulta de las variables ") . $e->getMessage();
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }


    //Metodo de actualizacion de variables 
    public function updateVariables($variables)
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Obtener los valores de los getters
            $valor_variable = $variables->getValor_variable();
            $id_variable = $variables->getId_Variables();

            // Convertir coma a punto si es necesario
            if (strpos($valor_variable, ',') !== false) {
                $valor_variable = str_replace(',', '.', $valor_variable);
            }

            $sql = "UPDATE variables SET valor_variable = ? WHERE id_Variables = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('di', $valor_variable, $id_variable);

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
