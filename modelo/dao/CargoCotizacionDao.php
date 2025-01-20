<?php
require_once '../configuracion/ConexionBD.php';
class CargoCotizacionDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Este método de cargo de cotizaciones consulta los cargos con su id y los muestra en el select 
    //de la tabla de actividades

    public function consultarCargoCotizacion()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_CargoCotizacion, nombre_cargo_cotizacion 
            FROM cargocotizacion WHERE estado_cargo_cotizacion = 'Activo' ";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();


            if ($result->num_rows > 0) {
                $nombreCargoCotizacion = array();

                while ($row = $result->fetch_assoc()) {
                    $nombreCargoCotizacion[$row['id_CargoCotizacion']] = $row['nombre_cargo_cotizacion'];
                }

                if ($nombreCargoCotizacion != null) {
                    return $nombreCargoCotizacion;
                } else {
                    return false;
                }
            }

            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("Error en la consulta de los cargis para la contización " . $e->getMessage());
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }


    //Método de consulta del valor del día de cada cargo según su seleccón del select en la tabla
    //de actividades

    public function consultarValorDiaCargo()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT nombre_cargo_cotizacion, tarifa_cargo FROM cargocotizacion 
            WHERE estado_cargo_cotizacion = 'Activo'";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            $cargos = [];

            if($result->num_rows > 0){
                while ($row = $result->fetch_assoc()) {
                    $cargos[] = $row;
                }
    
                if (!empty($cargos)) {
                    return $cargos;
                } else {
                    return false;
                }
            } else {
                return false;
            }

            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("Se ha presentado un error en la consulta de los valores" . $e->getMessage());
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }

    //Metodo de consulta de tarifas de los cargos por el id de la tabla cargoscomercial
    public function consultarCargoId($idCargosComercial)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_CargoCotizacion, nombre_cargo_cotizacion, tarifa_cargo FROM cargocotizacion WHERE id_CargoCotizacion = ? ";
            $statement = $conn->prepare($sql);
            $statement->bind_param("i", $idCargosComercial);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $cargos = $result->fetch_assoc();

                if ($cargos != null) {
                    return $cargos;
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

    public function updateValorCargos($cargos)
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Obtener los valores de los getters
            $valorDiaCargo = $cargos->getValor_dia();
            $idCargoCot = $cargos->getId_CargoCotizacion();

            // Convertir coma a punto si es necesario
            if (strpos($valorDiaCargo, ',') !== false) {
                $valorDiaCargo = str_replace(',', '.', $valorDiaCargo);
            }

            $sql = "UPDATE cargocotizacion SET tarifa_cargo = ? WHERE id_CargoCotizacion = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('ii', $valorDiaCargo, $idCargoCot);

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
