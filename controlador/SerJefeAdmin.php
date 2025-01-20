<?php
require_once '../controlador/ControladorUser.php';
require_once '../modelo/Usuarios.php';
require_once '../modelo/dao/UsuariosDao.php';
$menu = $_POST['menu'] ?? '';
$accion = $_POST['accion'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($menu === 'actualizar') {
        switch ($accion) {
            case 'Actualizar':
                try {
                    $campos = [
                        'txt_primerNombre', 'txt_primerApellido', 'txt_segundoApellido',
                        'txt_numeroIdentificacion', 'txt_correoElectronico', 'txt_telefonoMovil',
                        'txt_direccionResidencia', 'txt_sede', 'idcargo_area', 'select_estado_user',
                        'inputID_hidden'
                    ];
                    foreach ($campos as $campo) {
                        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                            header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                            exit;
                        }
                    }

                    $users = new Usuarios();
                    $users->setPrimer_nombre($_POST["txt_primerNombre"]);
                    if (!empty($_POST['txt_segundoNombre'])) {
                        $users->setSegundo_nombre($_POST["txt_segundoNombre"]);
                    } else {
                        $users->setSegundo_nombre('');
                    }
                    $users->setPrimer_apellido($_POST["txt_primerApellido"]);
                    $users->setSegundo_apellido($_POST["txt_segundoApellido"]);
                    $users->setNumero_identificacion($_POST["txt_numeroIdentificacion"]);
                    $users->setCorreo_electronico($_POST["txt_correoElectronico"]);
                    $users->setNumero_telefono_movil($_POST["txt_telefonoMovil"]);
                    $users->setDireccion_residencia($_POST["txt_direccionResidencia"]);
                    $users->setSede_laboral($_POST["txt_sede"]);
                    $users->setId_Cargo_Usuario($_POST["idcargo_area"]);
                    $users->setId_jefe_area($_POST["select_JefesUsuario"]);
                    $users->setId_Rol_Usuario($_POST["select_TipoRolUsuario"]);
                    $users->setEstado_usuario($_POST['select_estado_user']);
                    $users->setId_Usuarios($_POST["inputID_hidden"]);


                    if ($users !== null) {

                        $control = new ControladorUser();
                        $control->controlUpdateUser($users);
                        $UsersDao = new UsuariosDao();
                        $valoresSeleccionados = $_POST['id_cargos_seleccionados'] ?? [];
                        $UsersDao->registroJefeArea($_POST['inputID_hidden'], $valoresSeleccionados);
                        header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                        exit;
                    } else {
                        header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actulización fallida.'));
                        exit;
                    }
                } catch (Exception $e) {
                    header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
                break;

                case 'Cambiar':
                    try {
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if ($_POST['hiddenContrasenaVerificacion'] === "1") {
        
                                if ($_POST['txt_contrasena'] !== $_POST['txt_verificarContrasena']) {
                                    header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Las contraseñas no coinciden.'));
                                    exit;
                                } else if (empty($_POST['txt_contrasena']) && empty($_POST['txt_verificarContrasena'])) {
                                    header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('No puedes actualizar una contraseña vacía.'));
                                    exit;
                                } else {
                                    $control = new ControladorUser();
                                    $salt = bin2hex(random_bytes(18));
                                    $contrasena_con_salt = $_POST["txt_verificarContrasena"] . $salt;
                                    $contrasena_hasheada = hash("sha256", $contrasena_con_salt);
                                    $contrasena = $contrasena_hasheada;
                                    $saltVerificado = $salt;
                                    $identificacion = $_POST['txt_numeroIdentificacion'];
                                    $control->controlUpdateContraseña($saltVerificado, $contrasena_hasheada, $identificacion);
                                    header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=success&mensaje=" . urlencode('Actualización de contraseña exitosa.'));
                                    exit;
                                }
                            } else if ($_POST['hiddenContrasenaVerificacion'] === "0") {
                                header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización de contraseña fallida.'));
                                exit;
                            }
                        } else {
                            header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización de contraseña fallida.'));
                            exit;
                        }
                    } catch (Exception $e) {
                        header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Actualización de contraseña fallida.'));
                        exit;
                    }
                    break;


            default:
                //none
                break;
        }
    } else {
        header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Método inválido.'));
        exit;
    }
} else {
    header("Location: ../vista/ConsultaUsuariosJefeAdmin.php?tipo=danger&mensaje=" . urlencode('Método inválido.'));
    exit;
}
