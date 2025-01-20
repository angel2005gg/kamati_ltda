<?php
require_once '../modelo/Area.php';
require_once '../modelo/Usuarios.php';
require_once '../modelo/dao/CargoDao.php';
require_once '../modelo/dao/AreaDao.php';
require_once '../controlador/ControladorUser.php';
require_once '../modelo/dao/JefeAreaDao.php';



$menu = $_POST['menu'] ?? '';
$accion = $_POST['accion'] ?? '';

if ($menu === "registro") {
    switch ($accion) {
        case "Regresar":
            header('Location: ../vista/DashboardJefeAdminLin.php');
            exit;

        case "Registrar":
            try {
                // Validar que se hayan enviado todos los campos del formulario
                $campos = [
                    'txt_primerNombre', 'txt_primerApellido', 'txt_segundoApellido',
                    'txt_numeroIdentificacion', 'txt_correoElectronico', 'txt_telefonoMovil',
                    'txt_direccionResidencia', 'txt_sede', 'txt_contrasena', 'txt_verificarContrasena',
                    'idcargo_area', 'txt_rolUsuario', 'selectinmediato'
                ];
                foreach ($campos as $campo) {
                    if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                        header("Location: ../vista/registroUsuarioJefeAdminLin.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vac«¿o'));
                        exit;
                    }
                }

                // Crear instancia de ControladorUser
                $controladorUser = new ControladorUser();

                if (
                    !empty($_POST['txt_primerNombre']) && !empty($_POST["txt_primerApellido"]) && !empty($_POST["txt_segundoApellido"]) &&
                    !empty($_POST["txt_numeroIdentificacion"]) && !empty($_POST["txt_correoElectronico"]) &&
                    !empty($_POST["txt_telefonoMovil"]) && !empty($_POST["txt_direccionResidencia"]) &&
                    !empty($_POST["txt_sede"]) && !empty($_POST["txt_verificarContrasena"]) &&
                    !empty($_POST["idcargo_area"]) && !empty($_POST["txt_rolUsuario"]) && !empty($_POST['selectinmediato'])
                ) {

                    // Verificar que las contraseÃ±as coincidan
                    if ($_POST['txt_contrasena'] !== $_POST['txt_verificarContrasena']) {
                        header("Location: ../vista/registroUsuarioJefeAdminLin.php?tipo=danger&mensaje=" . urlencode('La contrase«Ğas no coinciden'));
                        exit;
                    }

                    // Crear objeto Usuarios y establecer valores
                    $usuario = new Usuarios();
                    $usuario->setPrimer_nombre($_POST["txt_primerNombre"]);
                    $usuario->setSegundo_nombre($_POST["txt_segundoNombre"] ?? "");
                    $usuario->setPrimer_apellido($_POST["txt_primerApellido"]);
                    $usuario->setSegundo_apellido($_POST["txt_segundoApellido"]);
                    $usuario->setNumero_identificacion($_POST["txt_numeroIdentificacion"]);
                    $usuario->setCorreo_electronico($_POST["txt_correoElectronico"]);
                    $usuario->setNumero_telefono_movil($_POST["txt_telefonoMovil"]);
                    $usuario->setDireccion_residencia($_POST["txt_direccionResidencia"]);
                    $usuario->setSede_laboral($_POST["txt_sede"]);

                    // Generar salt y hashear contraseÃ±a
                    $salt = bin2hex(random_bytes(18));
                    $contrasena_con_salt = $_POST["txt_verificarContrasena"] . $salt;
                    $contrasena_hasheada = hash("sha256", $contrasena_con_salt);
                    $usuario->setSalt($salt);
                    $usuario->setContrasena($contrasena_hasheada);
                    $usuario->setId_Cargo_Usuario($_POST["idcargo_area"]);
                    $usuario->setId_Rol_Usuario($_POST["txt_rolUsuario"]);
                    $usuario->setId_jefe_area($_POST['selectinmediato']);
                    $usuario->setEstado_usuario('Activo');

                    // Controlar registro de usuario
                    switch ($_POST['txt_rolUsuario']) {
                        case 1:
                            $controladorUser->controlRegistrarUsuario($usuario);
                            header("Location: ../vista/registroUsuarioJefeAdminLin.php?tipo=success&mensaje=Registro%20completado%20exitosamente.");
                            exit;

                        case 2:
                            if (isset($_POST['id_cargos_seleccionados'])) {
                                $valoresSeleccionados = $_POST['id_cargos_seleccionados'];
                                $controladorUser->controlRegistrarJefe($usuario, $valoresSeleccionados);
                                header("Location: ../vista/registroUsuarioJefeAdminLin.php?tipo=success&mensaje=Registro%20completado%20exitosamente.");
                                exit;
                            }
                            break;

                        case 3:
                            $controladorUser->controlRegistrarUsuario($usuario);
                            header("Location: ../vista/registroUsuarioJefeAdminLin.php?tipo=success&mensaje=Registro%20completado%20exitosamente.");
                            exit;

                        default:
                            header("Location: ../vista/registroUsuarioJefeAdminLin.php?tipo=danger&mensaje=Registro%fallido");
                            exit;
                    }
                } else {
                    throw new Exception("Faltan campos por completar");
                }
            } catch (Exception $e) {
                header("Location: ../vista/registroUsuarioJefeAdminLin.php?tipo=danger&mensaje=" . urlencode($e->getMessage()));
                exit;
            }
            break;

        default:

            break;
    }
}
