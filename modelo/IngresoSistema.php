<?php

session_start();
include 'Usuarios.php';
require_once '../configuracion/ConexionBD.php';
require_once '../modelo/dao/JefeAreaDao.php';
require_once '../modelo/Usuarios.php';
require_once '../modelo/Area.php';

class IngresoSistema
{
    //Atributo privado de la conexion a la base de datos
    private $conexion;

    //Método constructor con la conexion
    public function __construct()
    {
        $this->conexion = new ConexionBD();
    }

    //Método para verificar los credenciales en el inici o de sesion
    public function verificarCredenciales($identificacion, $contrasena)
    {
        
        try{

            $conn = $this->conexion->conectarBD();
            $query = "SELECT u.*, u.id_jefe_area as id_jefe_usuarios , c.nombre_cargo, a.nombre_area FROM usuarios AS u INNER JOIN cargo AS c ON c.id_Cargo = u.id_Cargo_Usuario 
            INNER JOIN area AS a on a.id_Area = c.id_area_fk WHERE u.numero_identificacion = ?";
            $statement = $conn->prepare($query);
            $statement->bind_param('s', $identificacion);
            $statement->execute();
            $result = $statement->get_result();
            $usuario = $result->fetch_assoc();
    
            // Verifica si se encontró un usuario con la identificación proporcionada
            if ($usuario) {
    
                date_default_timezone_set('America/Bogota');
                $_SESSION['fecha'] = date_default_timezone_get();
    
                $_SESSION['nombre_completo'] = $usuario['primer_nombre']. " ". $usuario['segundo_nombre'] . " " . $usuario['primer_apellido'] . " " . $usuario['segundo_apellido'];
                $_SESSION['idUser'] = $usuario['id_Usuarios'];
                $_SESSION['nombre'] = $usuario['primer_nombre'];
                $_SESSION['apellido'] = $usuario['primer_apellido'];
                $_SESSION['identificacion'] = $usuario['numero_identificacion'];
                $_SESSION['cargo'] = $usuario['nombre_cargo'];
                $_SESSION['area'] = $usuario['nombre_area'];
                $_SESSION['sede'] = $usuario['sede_laboral'];
                $_SESSION['telefono'] = $usuario['numero_telefono_movil'];
                $_SESSION['direccion'] = $usuario['direccion_residencia'];
                $_SESSION['correo_user_inicio_sesion'] = $usuario['correo_electronico'];
                $_SESSION['id_jefe_usuario_jefe'] = $usuario['id_jefe_usuarios'];
                $jefe_area = $usuario['id_jefe_area'];



                $_SESSION['id_area_fk_jefe'] = $jefe_area;
                
                $jefe = new JefeAreaDao();
                $user = new Usuarios();
    
                $user = $jefe->consultarJefeSinJson($jefe_area);
    
                $primer_Nombre = $user->getPrimer_nombre();
                $segundo_Nombre = $user->getSegundo_nombre();
                $primer_Apellido = $user->getPrimer_apellido();
                $segundo_Apellido = $user->getSegundo_apellido();
    
                $_SESSION['nombre_jefe'] = $primer_Nombre . " " . $segundo_Nombre . " " . $primer_Apellido . " " . $segundo_Apellido;
                
    
                
    
                $salt = $usuario['salt'];
                $contrasena_bd = $usuario['contrasena'];
                // Concatena la contraseña ingresada con el salt
                $contrasena_con_salt = $contrasena . $salt;
    
                // Aplica la misma función hash al resultado de la concatenación
                $hash_generado = hash("sha256", $contrasena_con_salt);
    
                // Utiliza password_verify() para comparar el nuevo hash generado con el hash almacenado
                if ($hash_generado = $contrasena_bd) {
    
                    // Si la contraseña es válida, crea un objeto Usuarios y lo devuelve
                    $usuarioObj = new Usuarios();
                    $usuarioObj->setId_Usuarios($usuario['id_Usuarios']);
                    $usuarioObj->setId_Rol_Usuario($usuario['id_Rol_Usuario']);
                    $usuarioObj->setNumero_identificacion($identificacion);
                    $usuarioObj->setContrasena($usuario['contrasena']);
                    $usuarioObj->setEstado_usuario($usuario['estado_usuario']);
                   
                    $usuarioObj->setNombre_area($usuario['nombre_area']);

                
    
                    return $usuarioObj;
                } else {
                    // Si la contraseña no es válida, devuelve null
                    return null;
                }
            } else {
                // Si no se encontró el usuario, devuelve null
                return null;
            }
        }catch(Exception $e){
            
        }finally{
                $statement->close();
                $result->close();
                $this->conexion->desconectarBD();
               
        }
    }
   
}
