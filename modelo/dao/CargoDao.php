<?php
include '../modelo/Cargo.php';
require_once '../configuracion/ConexionBD.php';
class CargoDao
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Metodo de consulta de cargos
    public function consultarCargo()
    {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT id_Cargo, nombre_cargo FROM cargo WHERE estado_cargo = 'Activo' ORDER BY id_Cargo";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();

        $nombre_cargo = array();

        while ($row = $result->fetch_assoc()) {
            $nombre_cargo[$row['id_Cargo']] = $row['nombre_cargo'];
        }

        if ($nombre_cargo != null) {
            return $nombre_cargo;
        } else {
            return null;
        }
    }
    //Metodo de consulta de id de los cargos para comparar
    public function idCargoExiste($id_Cargo)
    {
        try {
            // Conexión a la base de datos
            $conn = $this->conexion->conectarBD();

            // Consulta SQL para verificar la existencia del id_Cargo
            $sql = "SELECT EXISTS (SELECT 1 FROM cargo WHERE id_Cargo = ?) AS existe";
            $statement = $conn->prepare($sql);
            if (!$statement) {
                throw new Exception("Error en la preparación del statement: " . $conn->error);
            }

            // Vinculación de parámetros
            $statement->bind_param('i', $id_Cargo);

            // Ejecución de la consulta
            if (!$statement->execute()) {
                throw new Exception("Error en la ejecución del statement: " . $statement->error);
            }

            // Obtener el resultado
            $result = $statement->get_result();
            if (!$result) {
                throw new Exception("Error al obtener el resultado: " . $statement->error);
            }

            // Obtener la fila resultante
            $row = $result->fetch_assoc();
            $statement->close();
            $this->conexion->desconectarBD($conn);

            // Verificar y devolver si el id_Cargo existe
            return $row['existe'] == 1;
        } catch (Exception $e) {
            // Registro del error
            error_log("Error al verificar la existencia del cargo: " . $e->getMessage());

            // Asegurarse de que la conexión se cierre en caso de excepción
            if (isset($conn)) {
                $this->conexion->desconectarBD($conn);
            }

            return false;
        }
    }
    //Metodo de consulta de cargos para la tabla
    public function consultarCargoTabla()
    {
        $conn = $this->conexion->conectarBD();
        $sql = "SELECT c.id_Cargo, c.nombre_cargo, a.nombre_area, c.estado_cargo FROM cargo AS c INNER JOIN 
        area AS a ON c.id_area_fk = a.id_Area ORDER BY c.id_Cargo";
        $statement = $conn->prepare($sql);
        $statement->execute();
        $result = $statement->get_result();

        $data = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        if ($data != null) {
            return $data;
        } else {
            return null;
        }
    }
    //Metodo de consulta de cargos para la tabla
    public function consultarCargoTablaFiltro($nombre)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT c.id_Cargo, c.nombre_cargo, a.nombre_area, c.estado_cargo 
            FROM cargo AS c INNER JOIN 
            area AS a ON c.id_area_fk = a.id_Area 
            WHERE c.nombre_cargo LIKE ? ORDER BY c.id_Cargo";
            $statement = $conn->prepare($sql);
            $param = "%$nombre%";
            $statement->bind_param('s', $param);
            $statement->execute();
            $result = $statement->get_result();

            $data = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }

            $statement->close();
            $result->close();
            $this->conexion->desconectarBD($conn);

            return $data;
        } catch (Exception $e) {
            error_log("Falla en la consulta de cargos: " . $e->getMessage());
            return null;
        }
    }
    //Metodo de 
    public function seleccionarCargo($idCargo)
    {
        try {
            $conn = $this->conexion->conectarBD();
            if (!$conn) {
                throw new Exception('Failed to connect to the database.');
            }

            $sql = "SELECT id_Cargo, nombre_cargo, id_area_fk, estado_cargo 
            FROM cargo WHERE id_Cargo = ?";

            $statement = $conn->prepare($sql);
            if (!$statement) {
                throw new Exception('Failed to prepare the statement: ' . $conn->error);
            }

            $statement->bind_param('i', $idCargo);
            $statement->execute();
            $result = $statement->get_result();
            $cargo = $result->fetch_assoc();

            if ($cargo != null) {
                return $cargo;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            if (isset($result) && $result instanceof mysqli_result) {
                $result->close();
            }
            if (isset($statement) && $statement instanceof mysqli_stmt) {
                $statement->close();
            }
            if ($conn) {
                $this->conexion->desconectarBD();
            }
        }
    }
    //Metodo para consulta del id del area por medio del id del cargo
    public function consultarArea($id_cargo)
    {
        $conn = null;
        $statement = null;
        $result = null;
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_area_fk FROM cargo WHERE id_Cargo = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param("i", $id_cargo);
            $statement->execute();
            $result = $statement->get_result();
            $id_area = $result->fetch_assoc();

            return $id_area ? $id_area['id_area_fk'] : null;
        } catch (Exception $e) {
            error_log("Error al consultar área: " . $e->getMessage());
            return null;
        } finally {
            if ($result) {
                $result->close();
            }
            if ($statement) {
                $statement->close();
            }
            if ($conn) {
                $this->conexion->desconectarBD();
            }
        }
    }


    //Metodo insert para cargo
    public function insertCargos($cargo)
    {
        try {
            $conn = $this->conexion->conectarBD();

            // Validar entradas
            $nombre_cargo = $cargo->getNombre_cargo();
            $id_area_fk = $cargo->getId_area_fk();
            $estado_cargo = $cargo->getEstado_cargo();

            if (empty($nombre_cargo) || empty($id_area_fk) || empty($estado_cargo)) {
                throw new Exception("Entradas inválidas.");
            }

            $sql = "INSERT INTO cargo (nombre_cargo, id_area_fk, estado_cargo) VALUES (?,?,?)";
            $statement = $conn->prepare($sql);

            if (!$statement) {
                throw new Exception("Error en la preparación del statement: " . $conn->error);
            }
            $statement->bind_param('sis', $nombre_cargo, $id_area_fk, $estado_cargo);
            if (!$statement->execute()) {
                throw new Exception("Error en la ejecución del statement: " . $statement->error);
            }

            $statement->close();

            // Devolver true si la operación fue exitosa
            return true;
        } catch (Exception $e) {
            // Registro del error
            error_log("Error en la inserción del cargo: " . $e->getMessage());
            return false;
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }

    //Metodo de actualizacion de cargos
    public function updateCargos($cargo)
    {
        try {
            $conn = $this->conexion->conectarBD();

            // Validar entradas
            $nombre_cargo = $cargo->getNombre_cargo();
            $id_area_fk = $cargo->getId_area_fk();
            $estado_cargo = $cargo->getEstado_cargo();
            $idcargo = $cargo->getId_Cargo();

            if (empty($nombre_cargo) || empty($id_area_fk) || empty($estado_cargo)) {
                throw new Exception("Entradas inválidas.");
            }

            $sql = "UPDATE cargo SET  nombre_cargo = ?, id_area_fk = ?, estado_cargo = ? WHERE id_Cargo = ?";
            $statement = $conn->prepare($sql);

            if (!$statement) {
                throw new Exception("Error en la preparación del statement: " . $conn->error);
            }
            $statement->bind_param('sisi', $nombre_cargo, $id_area_fk, $estado_cargo, $idcargo);
            if (!$statement->execute()) {
                throw new Exception("Error en la ejecución del statement: " . $statement->error);
            }

            $statement->close();

            // Devolver true si la operación fue exitosa
            return true;
        } catch (Exception $e) {
            // Registro del error
            error_log("Error en la inserción del cargo: " . $e->getMessage());
            return false;
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }
}
