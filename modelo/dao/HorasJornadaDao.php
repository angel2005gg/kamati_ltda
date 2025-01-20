<?php

require_once '../configuracion/ConexionBD.php';

class HorasJornadaDao
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }
    //Método para la consulta de las horas
    public function consultaHorasJornada($idHoras)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT idHoras, nombreJornada, horaJornada FROM horasjornada WHERE idHoras = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $idHoras);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $horas = $result->fetch_assoc();

                if ($horas != null) {
                    return $horas;
                } else {
                    return false;
                }
            }
            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("No se consulsulto de manera correcta las horas de" . $e->getMessage());
        } finally {
            $this->conexion->desconectarBD();
        }
    }


    //Método para actualizacion de la hora de la jornada

    public function updateHoras($horas)
    {
        try {
            $conn = $this->conexion->conectarBD();
            // Obtener los valores de los getters
            $valorHoras = $horas->getHoraJornada();
            $idHoras = $horas->getIdHoras();

            $sql = "UPDATE horasJornada SET horajornada = ? WHERE idHoras = ?";
            $statement = $conn->prepare($sql);

            // Cambiamos 'di' a 'si' porque estamos vinculando un string (horaJornada) y un entero (idHoras)
            $statement->bind_param('si', $valorHoras, $idHoras);

            if ($statement->execute()) {
                return true;
            } else {
                return false;
            }

            $statement->close();
        } catch (Exception $e) {
            // Corregir la forma en que se maneja el error
            error_log("Error en la actualización de las horas: " . $e->getMessage());
            return false;
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }

    //Método de consulta para las horas en el servidor
    public function consultaHorasJornadaSin($idHoras)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT horaJornada FROM horasjornada WHERE idHoras = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $idHoras);
            $statement->execute();
            $result = $statement->get_result();

            if ($result->num_rows > 0) {
                $horas = $result->fetch_assoc();

                if ($horas != null) {
                    return $horas;
                } else {
                    return false;
                }
            }
            $statement->close();
            $result->close();
        } catch (Exception $e) {
            error_log("No se consulsulto de manera correcta las horas" . $e->getMessage());
        } finally {
            $this->conexion->desconectarBD();
        }
    }

    
}
