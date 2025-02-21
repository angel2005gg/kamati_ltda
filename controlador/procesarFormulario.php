<?php
require_once '../modelo/IngresoSistema.php';

class Login
{
    private $ingresoModelo;

    public function __construct()
    {
        $this->ingresoModelo = new IngresoSistema();
    }

    public function login($identificacion, $contrasena)
    {
        
        header('Content-Type: application/json');


        if (empty($identificacion) || empty($contrasena)) {
            echo json_encode(['status' => 'error', 'message' => 'Los campos de identificación y contraseña no pueden estar vacíos.']);
            exit;
        } else {
            $usuario = $this->ingresoModelo->verificarCredenciales($identificacion, $contrasena);
        }
        
        if ($usuario) {
            $_SESSION['user'] = $usuario;


            $redirectUrl = '';
            switch ($usuario->getId_Rol_Usuario()) {
                case 1:
                    if ($usuario->getEstado_usuario() === 'Inactivo') {
                        echo json_encode(['status' => 'error', 'message' => 'No puedes ingresar porque eres un usuario inactivo.']);
                        exit;
                    } else if ($usuario->getEstado_usuario() === 'Activo') {
                        if( $usuario->getId_Usuarios() === 5){
                            $redirectUrl = 'vista/DashboardJefeAdminLin.php';
                            
                        }else {
                            $redirectUrl = 'vista/DashboardAdmin.php';
                        }
                    }
                    break;
                case 2:
                    if ($usuario->getEstado_usuario() === 'Inactivo') {
                        echo json_encode(['status' => 'error', 'message' => 'No puedes ingresar porque eres un usuario inactivo.']);
                        exit;
                    } else if ($usuario->getEstado_usuario() === 'Activo') {
                        if ($usuario->getId_Usuarios() === 4) {
                            $redirectUrl = 'vista/DashboardJefeAdminL.php';
                        } else if ($usuario->getId_Usuarios() === 9) {
                            $redirectUrl = 'vista/DashboardJefeAdmin.php';
                        } else if ($usuario->getId_Usuarios() === 1) {
                            $redirectUrl = 'vista/DashboardJefeHelmer.php';
                        } else if($usuario->getId_Usuarios() === 11){
                            $redirectUrl = 'vista/DashboardJefeCielo.php';

                        }else if($usuario->getNombre_area() === 'Comercial'){
                            if($usuario->getId_Usuarios() === 7){
                                $redirectUrl = 'vista/DashboardJefeComercial.php';
                            }else if($usuario->getId_Usuarios() === 10){
                                $redirectUrl = 'vista/DashboardJefeComercial.php';
                            
                            }else{
                                $redirectUrl = 'vista/DashboardJefe.php';
                            }
                        } 
                        else {
                            $redirectUrl = 'vista/DashboardJefe.php';
                        }
                    }

                    break;
                case 3:
                    if ($usuario->getEstado_usuario() === 'Inactivo') {
                        echo json_encode(['status' => 'error', 'message' => 'No puedes ingresar porque eres un usuario inactivo.']);
                        exit;
                    } else if ($usuario->getEstado_usuario() === 'Activo') {
                        
                        if($usuario->getNombre_area() === 'Comercial'){
                            if($usuario->getId_Usuarios() === 84){
                                $redirectUrl = 'vista/DashboardJefeComercial.php';
                            }else{
                            $redirectUrl = 'vista/DashboardTrabajadorComercial.php';
                            }

                            
                        }else if($usuario->getId_Usuarios() === 47){
                            
                            $redirectUrl = 'vista/DashboardTrabajadorLuz.php';
                        }else{
                            $redirectUrl = 'vista/DashboardTrabajador.php';
                        }
                    }
                    break;
                default:
                    echo json_encode(['status' => 'error', 'message' => 'Rol de usuario no reconocido.']);
                    exit;
            }

            echo json_encode(['status' => 'success', 'redirect' => $redirectUrl]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Credenciales incorrectas. Inténtelo de nuevo.']);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = new Login();
    if (isset($_POST['txt_identificacion']) && isset($_POST['txt_contrasena'])) {
        $login->login($_POST['txt_identificacion'], $_POST['txt_contrasena']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Los datos de identificación y contraseña son requeridos.']);
    }
}

?>