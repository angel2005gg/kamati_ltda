<?php
require_once '../configuracion/ConexionBD.php';

class FactorIndependienteMaquinariaDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    // Método de inserción de datos en la tabla
    public function insertIdentificadorMaquinaria($nombreTablaMaquinaria, $id_hidden_table_maq, $kamatiTotalMaquinaria, $totalClienteMaquinaria)
    {
        try {
            session_start();
            $conn = $this->conexion->conectarBD();

            if (!$conn) {
                error_log('ERROR al conectar a la base de datos');
                return false;
            }

            $sql = "INSERT INTO identificadormaquinaria(id_cotizacionComercial_FK_maquinaria, nombreTablaMaquinaria, checkFactoresMaquinaria, totalKamati, totalCliente) 
                VALUES (?, ?, ?, ?, ?)";
            $statement = $conn->prepare($sql);

            if (!$statement) {
                error_log('ERROR al preparar la consulta: ' . $conn->error);
                return false;
            }

            $statement->bind_param('isidd', $_SESSION['id_cotizacion'], $nombreTablaMaquinaria, $id_hidden_table_maq, $kamatiTotalMaquinaria, $totalClienteMaquinaria);

            if ($statement->execute()) {
                return $conn->insert_id; // Devuelve el ID insertado
            } else {
                error_log('ERROR al ejecutar la consulta: ' . $statement->error);
            }

            $statement->close();
        } catch (Exception $e) {
            error_log('ERROR insertando los datos: ' . $e->getMessage());
            return false; // Devuelve false si hay un error
        }
        return false; // Devuelve false si no se insertó
    }

    //Método de creacion de factores independientes
    public function createFactoresIndependientesMaquinaria($id_IdentificadorMaquinaria_FK, $factor_nombre_indMaquinaria, $factor_valor_indMaquinaria)
    {
        $conn = null;
        $statement = null;

        try {
            // Validar parámetros
            if (empty($id_IdentificadorMaquinaria_FK) || empty($factor_nombre_indMaquinaria) || !is_numeric($factor_valor_indMaquinaria)) {
                throw new InvalidArgumentException("Parámetros inválidos proporcionados.");
            }

            // Obtener el ID de la cotización de la sesión
            $conn = $this->conexion->conectarBD();
            $sql = "INSERT INTO factoresindpendientesmaquinaria (id_IdentificadorMaquinaria_FK, nombre_ValorFactorIndependienteMaquinaria, valor_FactorIndependienteMaquinaria)
                VALUES (?, ?, ?)";
            $statement = $conn->prepare($sql);

            $statement->bind_param('isd', $id_IdentificadorMaquinaria_FK, $factor_nombre_indMaquinaria, $factor_valor_indMaquinaria);

            // Ejecutar la consulta
            if (!$statement->execute()) {
                error_log("Error al ejecutar la consulta: " . $statement->error);
                return null; // O lanzar una excepción
            }

            // Obtener el ID del último registro insertado
            $insertedId = $conn->insert_id;
            return $insertedId; // Retornar el ID insertado

        } catch (Exception $e) {
            error_log("Error en la inserción de los datos: " . $e->getMessage());
            return null; // O retornar un valor apropiado en caso de error
        } finally {
            if ($statement) {
                $statement->close(); // Cerrar la declaración si fue creada
            }
            if ($conn) {
                $this->conexion->desconectarBD(); // Asegurarse de cerrar la conexión
            }
        }
    }

    //Método de creacion de factores para la cotizacion 
    public function registrarFactoresIndependientesMaquinaria($id_Identificador, $factors)
    {
        session_start();
        if (isset($_SESSION['id_cotizacion'])) {
            error_log("ID de cotización encontrado en la sesión: " . $_SESSION['id_cotizacion']);
    
            $insertedIds = [];
            $factorNames = [];
    
            foreach ($factors as $factor) {
                $factor_nombre = $factor['name'];
                $factor_valor = $factor['value'];
    
                if (!empty($factor_nombre) && !is_null($factor_valor)) {
                    $insertedId = $this->createFactoresIndependientesMaquinaria($id_Identificador, $factor_nombre, $factor_valor);
                    error_log("ID insertado: " . $insertedId);
    
                    if ($insertedId) {
                        $insertedIds[] = $insertedId;
                        $factorNames[] = $factor_nombre;
                    } else {
                        error_log("No se pudo insertar el factor: " . $factor_nombre);
                    }
                } else {
                    error_log("Datos inválidos para el factor: nombre: " . $factor_nombre . ", valor: " . $factor_valor);
                }
            }
            return ['ids' => $insertedIds, 'names' => $factorNames];
        } else {
            error_log("ID de cotización no encontrado en la sesión.");
            return ['ids' => [], 'names' => []];
        }
    }
    public function createFactoresAdicionalesMaquinaria($id_IdentificadorMaquinaria_fk, $factor_nombre_AdcMaquinaria, $factor_valor_AdcMaquinaria)
    {
        $conn = null;
        $statement = null;

        try {
            // Validar parámetros
            if (empty($id_IdentificadorMaquinaria_fk) || empty($factor_nombre_AdcMaquinaria) || !is_numeric($factor_valor_AdcMaquinaria)) {
                throw new InvalidArgumentException("Parámetros inválidos proporcionados.");
            }

            // Obtener el ID de la cotización de la sesión
            $conn = $this->conexion->conectarBD();
            $sql = "INSERT INTO factoresadicionalesmaquinaria (id_IdentificadorMaquinaria_fk, nombre_FactorAdicionalMaquinaria, valor_FactorAdicionalMaquinaria)
                VALUES (?, ?, ?)";
            $statement = $conn->prepare($sql);

            $statement->bind_param('isd', $id_IdentificadorMaquinaria_fk, $factor_nombre_AdcMaquinaria, $factor_valor_AdcMaquinaria);

            // Ejecutar la consulta
            if (!$statement->execute()) {
                error_log("Error al ejecutar la consulta: " . $statement->error);
                return null; // O lanzar una excepción
            }

            // Obtener el ID del último registro insertado
            $insertedId = $conn->insert_id;
            return $insertedId; // Retornar el ID insertado

        } catch (Exception $e) {
            error_log("Error en la inserción de los datos: " . $e->getMessage());
            return null; // O retornar un valor apropiado en caso de error
        } finally {
            if ($statement) {
                $statement->close(); // Cerrar la declaración si fue creada
            }
            if ($conn) {
                $this->conexion->desconectarBD(); // Asegurarse de cerrar la conexión
            }
        }
    }

    //Método de creacion de factores para la cotizacion 
    public function registrarFactoresAdicionalesMaquinaria($id_Identificador, $factors)
    {
        session_start();
        if (isset($_SESSION['id_cotizacion'])) {
            error_log("ID de cotización encontrado en la sesión: " . $_SESSION['id_cotizacion']);
    
            $insertedIds = [];
            $factorNames = [];
    
            foreach ($factors as $factor) {
                $factor_nombre = $factor['name'];
                $factor_valor = $factor['value'];
    
                if (!empty($factor_nombre) && !is_null($factor_valor)) {
                    $insertedId = $this->createFactoresAdicionalesMaquinaria($id_Identificador, $factor_nombre, $factor_valor);
                    error_log("ID insertado: " . $insertedId);
    
                    if ($insertedId) {
                        $insertedIds[] = $insertedId;
                        $factorNames[] = $factor_nombre;
                    } else {
                        error_log("No se pudo insertar el factor: " . $factor_nombre);
                    }
                } else {
                    error_log("Datos inválidos para el factor: nombre: " . $factor_nombre . ", valor: " . $factor_valor);
                }
            }
            return ['ids' => $insertedIds, 'names' => $factorNames];
        } else {
            error_log("ID de cotización no encontrado en la sesión.");
            return ['ids' => [], 'names' => []];
        }
    }
    //Método de consulta de los factores independientes de la base de datos de la tabla materiales
    public function consultarFactoresIndependientesMateriales()
    {
        $conn = null;
        $statement = null;
        $result = null;

        try {
            $conn = $this->conexion->conectarBD();
            // Consulta para obtener los nombres y valores de los factores activos
            $sql = "SELECT factores, valorFactor FROM factoresComercial";
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            $factores = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $factores[] = $row;  // Guarda cada fila en el array de factores
                }
                return $factores; // Devuelve los factores si existen
            } else {
                return false;  // No hay factores
            }
        } catch (Exception $e) {
            error_log("No se ha podido consultar los factores: " . $e->getMessage());
            return null; // O puedes retornar un mensaje de error si lo prefieres
        } finally {
            if ($result) {
                $result->close(); // Cerrar el resultado si fue creado
            }
            if ($statement) {
                $statement->close(); // Cerrar la declaración si fue creada
            }
            if ($conn) {
                $this->conexion->desconectarBD(); // Asegurarse de cerrar la conexión
            }
        }
    }
}
