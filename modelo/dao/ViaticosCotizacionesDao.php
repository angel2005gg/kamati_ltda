<?php
require_once '../configuracion/ConexionBD.php';

class ViaticosCtizacionesDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Método para la insercion de datos en la tabla de viaticosCotizacion en la BD
    public function createData($viaticosCot){
        try{
            session_start(); // Asegúrate de que la sesión esté iniciada
            // Obtener el ID de cotización de la variable de sesión
            $id_Cotizacion = $_SESSION['id_cotizacion'];
            $nombre_viatico = $viaticosCot->getNombre_viatico();
            $valor_viatico = $viaticosCot->getValor_diario();
            $conn = $this->conexion->conectarBD();
            $sql = 'INSERT INTO viaticoscotizacion (id_CotizacionComercialFK, nombre_viatico, valor_diario)
            VALUES(?,?,?)';
            $statement = $conn->prepare($sql);
            $statement->bind_param('isi',$id_Cotizacion,  $nombre_viatico, $valor_viatico);
            $statement->execute();

            return $conn->insert_id;
            
            $statement->close();
        }catch(Exception $e){
            error_log("Error en la insersion de los datos ". $e->getMessage());
        }finally{
            $this->conexion->desconectarBD();
        }

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

     //Método de actualizacion de viaticos
     public function actualizarViaticos($idViaticos, $valor)
     {
         try {
             session_start();
             $conn = $this->conexion->conectarBD();
             $sql = 'UPDATE viaticoscotizacion 
             SET valor_diario = ?
             WHERE id_ViaticosCotizacion = ? AND id_CotizacionComercialFK = ?';
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
 
     //Método de consulta para traer los datos luego de la recarga de la página
     public function obtenerDatosViaticos()
     {
         try {
             session_start();    
             $conn = $this->conexion->conectarBD();
      
             // Modificar la consulta SQL para obtener el id_ViaticosCotizacion, nombre_viatico y valor_diario
             $sql = 'SELECT id_ViaticosCotizacion, nombre_viatico, valor_diario FROM viaticoscotizacion WHERE id_CotizacionComercialFK = ?';
             $statement = $conn->prepare($sql);
             $statement->bind_param('i', $_SESSION['id_cotizacion']);
             $statement->execute(); 
             $result = $statement->get_result();
      
             // Verificar si se obtuvieron resultados
             if ($result->num_rows > 0) {
                 $viaticos = [];
                 // Recorrer todos los resultados y almacenarlos en un array asociativo
                 while ($row = $result->fetch_assoc()) {
                     $viaticos[] = [
                         'id' => $row['id_ViaticosCotizacion'],  // Obtener el id del viático
                         'nombre' => $row['nombre_viatico'],    // Obtener el nombre del viático
                         'valor' => $row['valor_diario']        // Obtener el valor diario del viático
                     ];
                 }
                 return $viaticos;  // Retornar todos los viáticos
             } else {
                 return null;
             }
         } catch (Exception $e) {
             return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
         }
     }
}