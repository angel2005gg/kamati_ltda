<?php
require_once '../configuracion/ConexionBD.php';
require_once '../modelo/dao/CargoDao.php';
require_once '../modelo/Usuarios.php';

class JefeAreaDao
{

    private $conexion;


    //  Metodo constructor para instanciar un objeto de la conexion a la base de datos
    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Método de registro de jefeArea

    public function registroJefeArea($usuario, $areas)
    {
        try {
            // Registrar al usuario como jefe de área
            $usuariosDao = new UsuariosDao();

            $id_user = $usuariosDao->registrarUsuario($usuario);



            if ($id_user != -1) {
                // Si se registró correctamente como usuario, registra al jefe de área
                $conn = $this->conexion->conectarBD();

                foreach ($areas as $area) {
                    $id_Jefe_Usuario = $id_user; // Utiliza el id del usuario recién registrado

                    $sql = "INSERT INTO jefe_area (id_Area_Jefe_Inmediato, id_Jefe_Usuario) VALUES (?,?)";
                    $statement = $conn->prepare($sql);
                    $statement->bind_param('ii', $area, $id_Jefe_Usuario);

                    // Ejecuta la consulta
                    if (!$statement->execute()) {
                        // Si falla la inserción para algún área, devuelve false
                        return false;
                    }
                }
                return true;
            } else {
                return false; // Si falla el registro del usuario, devuelve false
            }
        } catch (Exception $e) {
            echo error_log("Error al registrar al jefe en la área: " . $e->getMessage());
            return false;
        }
    }

    //Metodo de consulta de usuario desde el jefe area

    public function consultarJefeArea($id_area)
    {
        $conn = null;
        $statement = null;
        $result = null;
        try {
            $conn = $this->conexion->conectarBD();

            $sql = "SELECT jf.id_Jefe_Area, u.primer_nombre, u.primer_apellido 
                FROM usuarios AS u 
                INNER JOIN jefe_area AS jf ON jf.id_Jefe_Usuario = u.id_Usuarios 
                INNER JOIN area AS a ON a.id_Area = jf.id_Area_Jefe_Inmediato 
                WHERE a.id_Area = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $id_area);
            $statement->execute();
            $result = $statement->get_result();

            $jefes = array();
            while ($row = $result->fetch_assoc()) {
                $jefes[] = [
                    'id_Usuarios' => $row['id_Jefe_Area'],
                    'primer_nombre' => $row['primer_nombre'],
                    'primer_apellido' => $row['primer_apellido']
                ];
            }

            return !empty($jefes) ? $jefes : null;
        } catch (Exception $e) {
            error_log("Error al consultar jefe de área: " . $e->getMessage());
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


    //Metodo de consulta de jefe inmediato sin formato json y sin ciclo 
    public function consultarJefeSinJson($id_usuario)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT u.correo_electronico ,u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.segundo_apellido 
            FROM usuarios AS u INNER JOIN jefe_area AS jf ON u.id_Usuarios = jf.id_Jefe_Usuario 
            WHERE jf.id_Jefe_Area = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $id_usuario);
            $statement->execute();
            $result = $statement->get_result();
            $jefe = $result->fetch_assoc();

            if ($jefe != null) {
                $user = new Usuarios();

                $user->setCorreo_electronico($jefe['correo_electronico']);
                $user->setPrimer_nombre($jefe['primer_nombre']);
                $user->setSegundo_nombre($jefe['segundo_nombre']);
                $user->setPrimer_apellido($jefe['primer_apellido']);
                $user->setSegundo_apellido($jefe['segundo_apellido']);

                return $user;
            }
        } catch (Exception $e) {
        } finally {
            $this->conexion->desconectarBD();
        }
    }

    //Método de consulta de id de usuario jefe por medio del id del jefe registrado a cada ususario
    public function consultaIdJefeUser($id_jefe_area)
    {
        try {
            // Conectar a la base de datos
            $conn = $this->conexion->conectarBD();

            // Preparar la consulta SQL
            $sql = "SELECT id_Jefe_Usuario FROM jefe_area WHERE id_Jefe_Area = ?";
            $statement = $conn->prepare($sql);

            // Vincular el parámetro
            $statement->bind_param('i', $id_jefe_area);

            // Ejecutar la consulta
            $statement->execute();

            // Obtener el resultado
            $result = $statement->get_result();

            // Verificar si hay resultados
            if ($result->num_rows > 0) {
                // Obtener la fila del resultado
                $row = $result->fetch_assoc();

                // Crear un objeto JefeArea y asignar el id_Jefe_Usuario
                $jefeArea = new JefeArea();
                $jefeArea->setId_Jefe_Usuario($row['id_Jefe_Usuario']);

                // Cerrar las conexiones
                $statement->close();
                $result->close();

                // Retornar el objeto JefeArea
                return $jefeArea;
            } else {
                // Cerrar las conexiones
                $statement->close();
                $result->close();

                // Si no hay resultados, retornar false
                return false;
            }
        } catch (Exception $e) {
            // Manejar la excepción
            error_log("Error en consultaIdJefeUser: " . $e->getMessage());
            return false;
        } finally {
            // Asegurarse de cerrar la conexión a la base de datos
            $this->conexion->desconectarBD();
        }
    }
}
