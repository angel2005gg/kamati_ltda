<?php
require_once '../configuracion/ConexionBD.php';
require_once '../modelo/ComercialProjects.php';

class ComercialProjectsDao
{

    //atributo privado para la conexion a la base de datos 
    private $conexion;

    //Constructor para crear el objeto d la conexion a la base de datos
    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Método para la creación de un proyecto para comercial 
    public function createCotizacion($comercial)
    {
        try {
            $conn = $this->conexion->conectarBD();

            $nombre_project = $comercial->getNombre_cotizacion();
            $codigo_project = $comercial->getCodigo_cotizacion();

            $sql = "INSERT INTO cotizacioncomercial (nombre_cotizacion, codigo_cotizacion) VALUES (?, ?)";
            $statement = $conn->prepare($sql);
            $statement->bind_param('ss', $nombre_project, $codigo_project);

            $statement->execute();

            // Obtener el ID del último registro insertado
            $last_id = $conn->insert_id;
            // Guardar el ID en una variable de sesión
            session_start(); // Asegúrate de iniciar la sesión si no lo has hecho
            $_SESSION['id_cotizacion'] = $last_id;
            $statement->close();
            return $last_id; // Retorna el ID del nuevo registro

        } catch (Exception $e) {
            error_log("Error en la creación del proyecto comercial: " . $e->getMessage());
            return null; // En caso de error, retorna null
        } finally {
            $this->conexion->desconectarBD();
        }
    }

    //Método para la consulta de los proyectos 
    public function selectCotizaciones()
    {
        $cotizaciones = []; // Inicializa un arreglo vacío para almacenar las cotizaciones
        try {
            $conn = $this->conexion->conectarBD(); // Conectar a la base de datos

            // Prepara la consulta SQL
            $sql = 'SELECT id_Cotizacion, nombre_cotizacion, codigo_cotizacion, fecha_creacion FROM cotizacioncomercial';
            $result = $conn->query($sql); // Ejecuta la consulta

            // Verifica si hay resultados
            if ($result) {
                // Recorre cada fila de resultados
                while ($row = $result->fetch_assoc()) {
                    $cotizaciones[] = $row; // Agrega cada fila al arreglo de cotizaciones
                }
            }

            return $cotizaciones; // Devuelve las cotizaciones obtenidas
        } catch (Exception $e) {
            error_log("Error en la consulta de los proyectos " . $e->getMessage());
            return []; // Devuelve un arreglo vacío en caso de error
        } finally {
            $this->conexion->desconectarBD(); // Desconectar de la base de datos
        }
    }

    //Método de actualización de nombre y codigo por medio del id 
    public function actualizarDatosCotizacion($fechaActual, $nombreCotizacion, $codigoCotizacion, $nombreCliente, $dolar, $euro) {      
        try {
            session_start();
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE cotizacioncomercial 
                    SET fechaCotizacion = ?, 
                        nombre_cotizacion = ?, 
                        codigo_cotizacion = ?, 
                        nombreCliente = ?, 
                        dolarCotizacion = ?, 
                        euroCotizacion = ? 
                    WHERE id_Cotizacion = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssi', $fechaActual, $nombreCotizacion, $codigoCotizacion, $nombreCliente, $dolar, $euro, $_SESSION['id_cotizacion']);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Datos actualizados exitosamente'];
            } else {
                return ['success' => false, 'message' => 'Error al actualizar los datos'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error en la actualización: ' . $e->getMessage()];
        }
    }

    public function obtenerDatosCotizacion() {
        try {
            session_start();

            $conn = $this->conexion->conectarBD();
            $sql = "SELECT fechaCotizacion, nombre_cotizacion, codigo_cotizacion, 
                nombreCliente, dolarCotizacion, euroCotizacion 
                FROM cotizacioncomercial 
                WHERE id_Cotizacion = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $_SESSION['id_cotizacion']);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                return null;
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }
}
