<?php

require_once '../modelo/Usuarios.php';
require_once '../configuracion/ConexionBD.php';

class UsuariosDao
{
    //Atributo privado de la clase para instanciar el objeto de la conexion
    private $conexion;


    //Contructor que lleva la instancia de la conexion a la base de datos
    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                          REGISTRO DE LA CLASE DE USUARIOS

    //Método de registro de usuarios
    public function registrarUsuario($usuario)
    {
        try {
            // Obtener una conexión a la base de datos
            $conn = $this->conexion->conectarBD();

            // Obtener los datos del usuario
            $primerNombre = $usuario->getPrimer_nombre();
            $segundoNombre = $usuario->getSegundo_nombre();
            $primerApellido = $usuario->getPrimer_apellido();
            $segundoApellido = $usuario->getSegundo_apellido();
            $numeroIdentificacion = $usuario->getNumero_identificacion();
            $correoElectronico = $usuario->getCorreo_electronico();
            $telefonoMovil = $usuario->getNumero_telefono_movil();
            $direccion = $usuario->getDireccion_residencia();
            $sede = $usuario->getSede_laboral();
            $salt = $usuario->getSalt();
            $contrasena = $usuario->getContrasena();
            $id_cargo = $usuario->getId_Cargo_Usuario();
            $id_rol = $usuario->getId_Rol_Usuario();
            $id_jefe = $usuario->getId_jefe_area();
            $estado_usuario = $usuario->getEstado_usuario();

            // Preparar la consulta SQL para la inserción de usuarios
            $query = "INSERT INTO usuarios(primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                numero_identificacion, correo_electronico, numero_telefono_movil, direccion_residencia,
                sede_laboral, salt, contrasena, id_Cargo_Usuario, id_Rol_Usuario, id_jefe_area, estado_usuario)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $statement = $conn->prepare($query);

            // Bind de los parámetros del usuario
            $statement->bind_param(
                "sssssssssssiiis",
                $primerNombre,
                $segundoNombre,
                $primerApellido,
                $segundoApellido,
                $numeroIdentificacion,
                $correoElectronico,
                $telefonoMovil,
                $direccion,
                $sede,
                $salt,
                $contrasena,
                $id_cargo,
                $id_rol,
                $id_jefe,
                $estado_usuario
            );

            // Ejecutar la consulta
            if ($statement->execute()) {
                // Si la inserción fue exitosa, devuelve el id del usuario insertado
                return $conn->insert_id;
            } else if (!$statement->execute()) {
                return -1; // Si la inserción falló, devuelve false
            }
        } catch (Exception $e) {
            // Manejar el error
            error_log("Error al registrar usuario: " . $e->getMessage());
            return false;
        }
    }
    //                                          FIN DEL REGISTRO DE USUARIOS
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                          CONSULTAS DE LA CLASE DE USUARIOS

    //Método para consultar usuarios 
    public function consultarUsuarios()
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = 'SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.segundo_apellido, u.numero_identificacion, 
            u.correo_electronico, u.numero_telefono_movil, u.direccion_residencia, u.sede_laboral, a.nombre_area, 
            c.nombre_cargo,u.estado_usuario 
            FROM usuarios AS u INNER JOIN cargo AS c ON u.id_Cargo_Usuario = c.id_Cargo INNER JOIN
            area AS a ON c.id_area_fk = a.id_Area ORDER BY u.primer_nombre';
            $statement = $conn->prepare($sql);
            $statement->execute();
            $result = $statement->get_result();

            $data = [];

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
            }
            return $data;
        } catch (Exception $e) {
        } finally {
            $statement->close();
            $result->close();
            $conn = $this->conexion->desconectarBD();
        }
    }

    //Método de consulta por medio de filtro
    public function consultaFiltroUser($nombre)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = 'SELECT u.primer_nombre, u.segundo_nombre, u.primer_apellido, u.segundo_apellido, u.numero_identificacion, 
                    u.correo_electronico, u.numero_telefono_movil, u.direccion_residencia, u.sede_laboral, a.nombre_area, 
                    c.nombre_cargo,u.estado_usuario 
                    FROM usuarios AS u INNER JOIN cargo AS c ON u.id_Cargo_Usuario = c.id_Cargo INNER JOIN
                    area AS a ON c.id_area_fk = a.id_Area WHERE u.primer_nombre LIKE ?';

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
            return $data;
        } catch (Exception $e) {
            return $e;
        } finally {
            if (isset($statement)) {
                $statement->close();
            }
            if (isset($result)) {
                $result->close();
            }
            $conn = $this->conexion->desconectarBD();
        }
    }
    // Metodo de consulta de usuario por medio de identificacion para actualizar
    public function consultarActualizarUser($numero_identificacion)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = 'SELECT u.primer_nombre, u.id_Usuarios, u.segundo_nombre, u.primer_apellido, u.segundo_apellido, u.numero_identificacion, 
                u.correo_electronico, u.numero_telefono_movil, u.direccion_residencia, u.sede_laboral, a.nombre_area, 
                u.id_Cargo_Usuario, u.id_Rol_Usuario, u.id_jefe_area, c.nombre_cargo, u.estado_usuario 
                FROM usuarios AS u 
                INNER JOIN cargo AS c ON u.id_Cargo_Usuario = c.id_Cargo 
                INNER JOIN area AS a ON c.id_area_fk = a.id_Area 
                WHERE u.numero_identificacion = ?';
            $statement = $conn->prepare($sql);
            $statement->bind_param('s', $numero_identificacion);
            $statement->execute();
            $result = $statement->get_result();
            $user = $result->fetch_assoc();

            if ($user != null) {
                return $user;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        } finally {
            if (isset($result)) {
                $result->close();
            }
            if (isset($statement)) {
                $statement->close();
            }
            $conn = $this->conexion->desconectarBD();
        }
    }



    //Método de seleccion del id del usuario por medio de la identificacion 
    public function seleccionarIdUser($numero_identificacion)
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = 'SELECT id_Usuarios FROM usuarios WHERE numero_identificacion = ?';
            $statement = $conn->prepare($sql);
            $statement->bind_param('s', $numero_identificacion);
            $statement->execute();
            $result = $statement->get_result();
            $user = $result->fetch_assoc();
            if ($user!= null) {
                return $user['id_Usuarios'];
            }
        }catch(Exception $e){

        }
    }

    public function registroJefeArea($numero_identificacion, $areas)
{
    try {
        $id_user = $numero_identificacion;

        if ($id_user != -1) {
            // Si se registró correctamente como usuario, registra al jefe de área
            $conn = $this->conexion->conectarBD();

            foreach ($areas as $area) {
                $id_Jefe_Usuario = $id_user; // Utiliza el id del usuario recién registrado

                // Verifica si el registro ya existe
                $sql_check = "SELECT COUNT(*) as total FROM jefe_area WHERE id_Area_Jefe_Inmediato = ? AND id_Jefe_Usuario = ?";
                $statement_check = $conn->prepare($sql_check);
                $statement_check->bind_param('ii', $area, $id_Jefe_Usuario);
                $statement_check->execute();
                $result_check = $statement_check->get_result();
                $row = $result_check->fetch_assoc();

                // Si no existe el registro, lo insertamos
                if ($row['total'] == 0) {
                    $sql_insert = "INSERT INTO jefe_area (id_Area_Jefe_Inmediato, id_Jefe_Usuario) VALUES (?,?)";
                    $statement_insert = $conn->prepare($sql_insert);
                    $statement_insert->bind_param('ii', $area, $id_Jefe_Usuario);

                    // Ejecuta la consulta de inserción
                    if (!$statement_insert->execute()) {
                        // Si falla la inserción para algún área, devuelve false
                        return false;
                    }
                }

                // Cierra el statement de verificación
                $statement_check->close();
            }
            return true;
        } else {
            return false; // Si falla el registro del usuario, devuelve false
        }
    } catch (Exception $e) {
        echo error_log("Error al registrar al jefe en el área: " . $e->getMessage());
        return false;
    }
}
    
    public function consultarUserModal($id_usuario) {
    $conn = $this->conexion->conectarBD();

    try {
        $sql = "SELECT primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, numero_identificacion, correo_electronico, 
                numero_telefono_movil, direccion_residencia, sede_laboral FROM usuarios WHERE id_Usuarios = ?";
        $statement = $conn->prepare($sql);
        $statement->bind_param('i', $id_usuario);
        $statement->execute();
        $result = $statement->get_result();
        
        if ($result->num_rows > 0) {
            $usuariosModal = $result->fetch_assoc();
            if ($usuariosModal !== null) {
                $UsuariosObject = new Usuarios(); // Asegúrate de que la clase Usuarios esté importada correctamente
                
                $UsuariosObject->setPrimer_nombre($usuariosModal['primer_nombre']);
                $UsuariosObject->setSegundo_nombre($usuariosModal['segundo_nombre']);
                $UsuariosObject->setPrimer_apellido($usuariosModal['primer_apellido']);
                $UsuariosObject->setSegundo_apellido($usuariosModal['segundo_apellido']);
                $UsuariosObject->setNumero_identificacion($usuariosModal['numero_identificacion']);
                $UsuariosObject->setCorreo_electronico($usuariosModal['correo_electronico']);
                $UsuariosObject->setNumero_telefono_movil($usuariosModal['numero_telefono_movil']);
                $UsuariosObject->setDireccion_residencia($usuariosModal['direccion_residencia']);
                $UsuariosObject->setSede_laboral($usuariosModal['sede_laboral']);
                
                return $UsuariosObject;
            }
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log("Error en la consulta: " . $e->getMessage());
        return false;
    } finally {
        // Cerrar recursos y conexión en finally
        if (isset($result)) $result->close();
        if (isset($statement)) $statement->close();
        $this->conexion->desconectarBD();
    }
}
    //Método de consulta de usuarios jefes
    public function consultaJefeUser($idRol) {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT id_Usuarios, primer_nombre, primer_apellido, segundo_apellido FROM usuarios WHERE id_Rol_Usuario = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('i', $idRol);
            $statement->execute(); // Falta ejecutar la consulta
            $result = $statement->get_result();
            
            // Crear un array para almacenar los nombres completos
            $nombre_rol = [];
    
            while ($row = $result->fetch_assoc()) {
                // Concatenar primer_nombre, primer_apellido y segundo_apellido
                $nombreCompleto = $row['primer_nombre'] . ' ' . $row['primer_apellido'] . ' ' . $row['segundo_apellido'];
                $nombre_rol[$row['id_Usuarios']] = $nombreCompleto;
            }
    
            if (!empty($nombre_rol)) {
                return $nombre_rol;
            } else {
                return null;
            }
        } catch (Exception $e) {
            error_log("Error en la consulta de los jefes: " . $e->getMessage());
        } finally {
            $this->conexion->desconectarBD();
        }
    }
    
    //Método de consulta para los id de las area a los que los jefes guían
    public function consultarIdJefesArea($numero_identificacion) {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "SELECT j.id_Area_Jefe_Inmediato 
                    FROM jefe_area AS j 
                    INNER JOIN usuarios AS u ON u.id_Usuarios = j.id_Jefe_Usuario 
                    WHERE u.numero_identificacion = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('s', $numero_identificacion);
            $statement->execute();
            $result = $statement->get_result();
    
            $areas_jefe = [];
            while ($row = $result->fetch_assoc()) {
                $areas_jefe[] = $row['id_Area_Jefe_Inmediato'];
            }
    
            return $areas_jefe;  // Devolver un array con los IDs de las áreas
        } catch (Exception $e) {
            error_log("Error en la consulta de los jefes de área: " . $e->getMessage());
        } finally {
            $this->conexion->desconectarBD();
        }
    
        return null;
    }
    //                                      FIN DE LAS CONSULTAS DE LA CLASE USUARIOS
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                                      ACTUALIZACIÓN DE LOS USUARIOS REGISTRADOS

    //Método para actualizar los datos de los usarios de la base de datos
    public function actualizarUsers($usuarios)
    {
        try {
            $conn = $this->conexion->conectarBD();

            $primerNombre = $usuarios->getPrimer_nombre();
            $segundoNombre = $usuarios->getSegundo_nombre();
            $primerApellido = $usuarios->getPrimer_apellido();
            $segundoApellido = $usuarios->getSegundo_apellido();
            $numeroIdentificacion = $usuarios->getNumero_identificacion();
            $idNumero = $usuarios->getId_Usuarios();
            $correoElectronico = $usuarios->getCorreo_electronico();
            $telefonoMovil = $usuarios->getNumero_telefono_movil();
            $direccion = $usuarios->getDireccion_residencia();
            $sede = $usuarios->getSede_laboral();
            $id_cargo = $usuarios->getId_Cargo_Usuario();
            $id_Rol = $usuarios->getId_Rol_Usuario();
            $idJefearea = $usuarios->getId_jefe_area();
            $estado_usuario = $usuarios->getEstado_usuario();
            $sql = 'UPDATE usuarios SET primer_nombre = ? , segundo_nombre = ?, primer_apellido = ?, segundo_apellido = ?, 
            numero_identificacion = ?, correo_electronico = ?, numero_telefono_movil = ?, direccion_residencia = ?,
            sede_laboral = ?, id_Cargo_Usuario = ?, id_Rol_Usuario = ?, id_jefe_area = ?, estado_usuario = ? WHERE id_Usuarios = ?';
            $statement = $conn->prepare($sql);
            $statement->bind_param(
                'sssssssssiiisi',
                $primerNombre,
                $segundoNombre,
                $primerApellido,
                $segundoApellido,
                $numeroIdentificacion,
                $correoElectronico,
                $telefonoMovil,
                $direccion,
                $sede,
                $id_cargo,
                $id_Rol,
                $idJefearea,
                $estado_usuario,
                $idNumero
            );
            $statement->execute();

            if ($statement->affected_rows > 0) {
                return true;
            } else {
                return false;
            }

            $statement->close();
        } catch (Exception $e) {
            return $e;
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }

    //Método de actualización de contrasena de un usuario segun su id de usuario
    public function updatePasswordUser($salt, $contrasena, $identificacion )
    {
        try {
            $conn = $this->conexion->conectarBD();
            $sql = "UPDATE usuarios SET salt = ? , contrasena = ? WHERE numero_identificacion = ?";
            $statement = $conn->prepare($sql);
            $statement->bind_param('sss', $salt, $contrasena, $identificacion );
            $statement->execute();

            if ($statement->affected_rows > 0) {
                return true;
            } else {
                return false;
            }

            $statement->close();
        } catch (Exception $e) {
            return error_log("Error actualizando la contraseña. " . $e->getMessage());
        } finally {
            $conn = $this->conexion->desconectarBD();
        }
    }



    //                                      FIN DE LA ACTUALIZACIÓN DE LA CLASE USUARIOS
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}
