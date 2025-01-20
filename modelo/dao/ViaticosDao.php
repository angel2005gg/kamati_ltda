<?php
require_once '../configuracion/ConexionBD.php';

class ViaticosDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Método para la consulta de los viaticos para mostrarlos en la tabla
    public function consultarViaticos()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT nombre_viatico, valor_diario FROM viaticos";
            $statement = $conn->prepare($sql);
            $statement->execute();

            $result = $statement->get_result();

            // Inicializa un array para almacenar los viaticos
            $viaticos = [];

            // Verifica si hay resultados y recorre todas las filas
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $viaticos[] = $row;
                }

                if (!empty($viaticos)) {
                    return $viaticos;
                } else {
                    return false;
                }
            } else {
                return false;
            }

            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("Error en la consulta de los datos de los viaticos: " . $e->getMessage());
            return false;
        } finally {
            $this->conexion->desconectarBD();
        }
    }
    //Método para la consulta de las tarifas de viaticos 

    public function consultarViaticosId($idViaticos)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_Viaticos, nombre_viatico, valor_diario FROM viaticos WHERE id_Viaticos = ? ";
            $statement = $conn->prepare($sql);
            $statement->bind_param("i", $idViaticos);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $viaticos = $result->fetch_assoc();

                if ($viaticos != null) {
                    return $viaticos;
                } else {
                    return false;
                }
            }

            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("No se ha podido consultar los valores de los viaticos" . $e->getMessage());
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }

    //Método de actualizacion de viaticos
    public function updateViaticos($viaticos)
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Obtener los valores de los getters
            $valorViatico = $viaticos->getValor_diario();
            $idViaticos = $viaticos->getId_Viaticos();

            // Convertir coma a punto si es necesario
            if (strpos($valorViatico, ',') !== false) {
                $valorViatico = str_replace(',', '.', $valorViatico);
            }

            $sql = "UPDATE viaticos SET valor_diario = ? WHERE id_Viaticos = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('ii', $valorViatico, $idViaticos);

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
