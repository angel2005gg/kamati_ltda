<?php
require_once '../configuracion/ConexionBD.php';
class FactoresCotizacionesDao
{

    private $conexion;


    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }
    public function createFactoresCotizaciones($factor_nombre, $factor_valor)
    {
        try {
            $idCotizacionComercialFKs = $_SESSION['id_cotizacion']; // Obtener el ID de la cotización de la sesión
            $conn = $this->conexion->conectarBD();
            $sql = "INSERT INTO factorescotizacion (idCotizacionComercialFKs, factor_nombre, factor_valor)
        VALUES (?, ?, ?)";
            $statement = $conn->prepare($sql);

            // Cambiar el tipo de dato de factor_valor a 'double' (d)
            $statement->bind_param('isd', $idCotizacionComercialFKs, $factor_nombre, $factor_valor);
            $statement->execute();

            // Obtener el ID del último registro insertado
            $insertedId = $conn->insert_id;

            $statement->close();

            return $insertedId; // Retornar el ID insertado
        } catch (Exception $e) {
            error_log("Error en la inserción de los datos: " . $e->getMessage());
            return null; // O retornar un valor apropiado en caso de error
        } finally {
            $this->conexion->desconectarBD();
        }
    }

    //Método de creacion de factores para la cotizacion 
    public function registrarFactoresCotizacion()
    {
        // Verifica si el ID de la cotización está en la sesión
        if (isset($_SESSION['id_cotizacion'])) {
            // Obtener la lista de factores
            $factores = $this->consultarFactoresCotizaciones();
            $insertedIds = []; // Array para almacenar los IDs insertados

            if ($factores) {
                // Iterar sobre los factores y registrar cada uno en la tabla
                foreach ($factores as $factor) {
                    $factor_nombre = $factor['factores'];
                    $factor_valor = $factor['valorFactor'];

                    // Llamar a la función para insertar los datos y almacenar el ID
                    $insertedId = $this->createFactoresCotizaciones($factor_nombre, $factor_valor);
                    if ($insertedId) {
                        $insertedIds[] = $insertedId; // Agregar el ID al array
                    }
                }
                return $insertedIds; // Retornar todos los IDs insertados
            } else {
                error_log("No se encontraron factores para registrar.");
                return []; // Cambiar a un array vacío en lugar de null para facilitar el manejo de errores
            }
        } else {
            error_log("ID de cotización no encontrado en la sesión.");
            return []; // Cambiar a un array vacío para mantener la consistencia
        }
    }


    public function consultarFactoresCotizaciones()
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Consulta para obtener los nombres y valores de los factores activos
            $sql = "SELECT factores, valorFactor FROM factorescomercial";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            $factores = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $factores[] = $row;  // Guarda cada fila en el array de factores
                }

                if (!empty($factores)) {
                    return $factores; // Devuelve los factores si existen
                } else {
                    return false;  // No hay factores
                }
            }

            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("No se ha podido consultar los factores: " . $e->getMessage());
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }
    //Método de consulta de los Factores
    public function consultarFactores($idFactores)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_Factores, factores, valorFactor FROM factorescomercial WHERE id_Factores = ? ";
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
    //Metodo de actualizacion de variables 
    public function updateFactoresCotizacion($factores)
    {
        try {
            session_start();
            $idCotizacionComercialFKs = $_SESSION['id_cotizacion'];
            $valor_Factor = $factores->getValorFactor();
            $id_Factor = $factores->getId_Factores(); // Si tienes el ID del campo específico
            $conn = $this->conexion->conectarBD();

            // Aquí actualizas el campo correspondiente
            $sql = "UPDATE factorescotizacion SET factor_valor = ? WHERE id_factorescotizacion = ? AND idCotizacionComercialFKs = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('dii', $valor_Factor, $id_Factor, $idCotizacionComercialFKs);

            if ($statement->execute()) {
                return true;
            } else {
                return false;
            }
            $statement->close();
        } catch (Exception $e) {
            error_log("Error en la actualización: " . $e->getMessage());
            return false;
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }


    public function actualizarFactoresCotizacion($idFactorCotizacion, $valor)
    {
        try {
            session_start();
            $conn = $this->conexion->conectarBD();
            $sql = 'UPDATE factorescotizacion 
            SET factor_valor = ?
            WHERE id_factorescotizacion = ? AND idCotizacionComercialFKs = ?';
            $statement = $conn->prepare($sql);
            $statement->bind_param('dii', $valor, $idFactorCotizacion, $_SESSION['id_cotizacion']);
            if ($statement->execute()) {
                return ['success' => true, 'message' => 'Datos actualizados exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar los datos'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()];
        }
    }

    //Método de consulta a la base de datos para mostrar luego de recargar el DOM 
     // Método para obtener los datos de los factores de una cotización
     public function obtenerDatosFactores()
     {
         try {
             // Iniciar sesión
             session_start();
             // Establecer conexión a la base de datos
             $conn = $this->conexion->conectarBD();
             // Consulta SQL para obtener el id, nombre y valor del factor
             $sql = 'SELECT id_factorescotizacion, factor_nombre, factor_valor FROM factorescotizacion WHERE idCotizacionComercialFKs = ?';
             $statement = $conn->prepare($sql);
             $statement->bind_param('i', $_SESSION['id_cotizacion']); // Usar el ID de la cotización en la sesión
             $statement->execute();
             $result = $statement->get_result();
     
             // Verificar si se encontraron resultados
             if ($result->num_rows > 0) {
                 $factores = [];
                 // Recorrer los resultados y almacenarlos en un array asociativo
                 while ($row = $result->fetch_assoc()) {
                     // Agregar el id_factorescotizacion junto con el nombre y valor
                     $factores[] = [
                         'id' => $row['id_factorescotizacion'],
                         'nombre' => $row['factor_nombre'],
                         'valor' => $row['factor_valor']
                     ];
                 }
                 // Devolver el array con los factores
                 return $factores;
             } else {
                 // Si no se encontraron resultados, devolver null
                 return null;
             }
         } catch (Exception $e) {
             // Capturar errores y devolver un mensaje
             return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
         }
     }
}
