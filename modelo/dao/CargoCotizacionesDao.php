<?php
require_once '../configuracion/ConexionBD.php';
class CargoCotizacionesDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }



    //Método de insersion de datos en la tabla de los cargos de la cotizacion 
    public function createCargoCotizaciones($nombre_cargo_cv, $tarifa_cargo_cv)
    {
        try {
            $id_CotizacionComercialfk = $_SESSION['id_cotizacion'];
            $conn = $this->conexion->conectarBD();
            $sql = "INSERT INTO cargovalorcotizacion (id_CotizacionComercialfk, nombre_cargo_cv, tarifa_cargo_cv)
                VALUES (?,?,?)";
            $statement = $conn->prepare($sql);
            $statement->bind_param('isi', $id_CotizacionComercialfk, $nombre_cargo_cv, $tarifa_cargo_cv);
            $statement->execute();
            $statement->close();

            $ids = $conn->insert_id;

            return $ids;
            $statement->close();
        } catch (Exception $e) {
            error_log("Error en la inserción de los datos: " . $e->getMessage());
        } finally {
            $this->conexion->desconectarBD();
        }
    }

    public function registrarCargosCotizacion()
    {
        // Inicializar un array vacío para almacenar los IDs insertados
        $insertedIds = [];

        // Obtener la lista de cargos activos
        $cargos = $this->consultarValorDiaCargosCotizaciones();

        if ($cargos) {
            // Iterar sobre los cargos y registrar cada uno en la tabla
            foreach ($cargos as $cargo) {
                $nombre_cargo = $cargo['nombre_cargo_cotizacion'];
                $tarifa_cargo = $cargo['tarifa_cargo'];

                // Llamar a la función para insertar los datos
                $insertedId = $this->createCargoCotizaciones($nombre_cargo, $tarifa_cargo);
                if ($insertedId) {
                    $insertedIds[] = $insertedId; // Agregar el ID al array
                }
            }
        } else {
            error_log("ID de cotización no encontrado en la sesión.");
        }

        // Devolver el array con los IDs insertados
        return $insertedIds;
    }
    //Este método de cargo de cotizaciones consulta los cargos con su id y los muestra en el select 
    //de la tabla de actividades

    public function consultarCargoCotizacion()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_CargoCotizacion, nombre_cargo_cotizacion 
            FROM cargoCotizacion WHERE estado_cargo_cotizacion = 'Activo' ";
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

    public function consultarValorDiaCargosCotizaciones()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT nombre_cargo_cotizacion, tarifa_cargo FROM cargocotizacion 
            WHERE estado_cargo_cotizacion = 'Activo'";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            $cargos = [];

            if ($result->num_rows > 0) {
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

    //Método actualizacion de los valores de los cargos
    public function actualizarCargos($idViaticos, $valor)
    {
        try {
            session_start();
            $conn = $this->conexion->conectarBD();
            $sql = 'UPDATE cargovalorcotizacion 
             SET tarifa_cargo_cv = ?
             WHERE id_cargoValorCotizacion = ? AND id_CotizacionComercialfk = ?';
            $statement = $conn->prepare($sql);
            $statement->bind_param('dii', $valor, $idViaticos, $_SESSION['id_cotizacion']);
            if ($statement->execute()) {
                return ['success' => true, 'message' => 'Datos actualizados exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar los datos'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()];
        }
    }

    //Método de consulta de los datos de los cargos
    public function obtenerDatosCargos()
    {
        try {
            session_start();    
            $conn = $this->conexion->conectarBD();
    
            // Modificar la consulta SQL para obtener nombre_cargo_cv y tarifa_cargo_cv
            $sql = 'SELECT id_cargoValorCotizacion, nombre_cargo_cv, tarifa_cargo_cv FROM cargovalorcotizacion WHERE id_CotizacionComercialfk = ?';
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $_SESSION['id_cotizacion']);
            $statement->execute(); 
            $result = $statement->get_result();
    
            // Verificar si se obtuvieron resultados
            if ($result->num_rows > 0) {
                $cargos = [];
                // Recorrer todos los resultados y almacenarlos en un array asociativo
                while ($row = $result->fetch_assoc()) {
                    $cargos[] = [
                        
                        'id' => $row['id_cargoValorCotizacion'],  // Obtener el nombre del cargo
                        'nombre' => $row['nombre_cargo_cv'],  // Obtener el nombre del cargo
                        'tarifa' => $row['tarifa_cargo_cv']   // Obtener la tarifa del cargo
                    ];
                }
                return $cargos;  // Retornar todos los cargos
            } else {
                return null;
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
