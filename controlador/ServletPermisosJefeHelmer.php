<?php
//Inicio de session para uso de variables de session declaradas al incio de sesion del usuario
session_start();
//inluir los modelos y controladores necesarios para el manejo de los metodos
require_once '../controlador/ControladorPermisos.php';
require_once '../modelo/Permisos.php';
require_once '../modelo/dao/PermisosDao.php';
require_once '../modelo/dao/JefeAreaDao.php';
require_once '../modelo/JefeArea.php';



//Se verifica que se obtienen los datos de un metodo phost
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //Se ontiene el valor de menu y accion para saber que accion se va a realizar del formulario
    $menu = $_POST['menu'] ?? '';
    $accion = $_POST['accion'] ?? '';

    //Se solicita mediante el valor del menu
    if ($menu === 'permisosJefe') {
        switch ($accion) {
            case 'Regresar':
                header("Location: ../vista/DashboardJefeHelmer.php");
                exit;
                break;

            case 'Aprobar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    //Se verifica que el select sea post 
                    if (isset($_POST['select_remunerado'])) {
                        try {
                            $campos = [
                                'txt_date_eleboracion1', 'txt_nombre1', 'txt_identificacion1',
                                'txt_cargo1', 'txt_area1', 'txt_tipo_permiso1', 'txt_sede1',
                                'date_inicio1', 'date_fin1', 'textarea_motivo1', 'txt_tipo_tiempo1',
                                'txt_cantidad_tiempo1', 'select_remunerado'
                            ];
                            //ciclo para recorrer los campos y sus valores obtenidos en caso de que no se encuentre
                            //un valor en algún campo entonces se arrojará una alerta que mencione el campo
                            //que falta por diligenciar
                            foreach ($campos as $campo) {
                                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                                    header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud'));
                                    exit;
                                }
                            }
                            //Instancia de clase ControladorPermisos
                            $control = new ControladorPermisos();

                            //Bloque try catch para manejo de errores
                            try {
                                if (isset($_POST['txt_hidden_estado']) && ($_POST['txt_hidden_estado'] === 'Pendiente' || $_POST['txt_hidden_estado'] === 'Negado')) {
                                    header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=warning&mensaje=" . urlencode('Esta solicitud ya esta enviada a gestión humana'));
                                    exit;
                                } else if (isset($_POST['txt_hidden_estado'])) {
                                    $permisos1 = new Permisos();
                                    $permisos1->setRemuneracion($_POST['select_remunerado']);
                                    $permisos1->setEstado_permiso('Pendiente'); //Pendiente
                                    $permisos1->setId_Permisos($_POST['txt_hidden']);
                                    if ($permisos1 !== null) {
                                        $control->controlUpdatePermisosJefe($permisos1);
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=success&mensaje=" . urlencode('Se ha puesto en pendiente exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud'));
                                        exit;
                                    }
                                } else {
                                    header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud'));
                                    exit;
                                }
                            } catch (Exception $e) {
                                header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud'));
                                exit;
                            }
                        } catch (Exception $e) {
                            header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud.'));
                            exit;
                        }
                    } else {
                        header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('Primero debes de seleccionar la remuneración.'));
                        exit;
                    }
                } else {
                    header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('Método inválido'));
                    exit;
                }


                break;
            case 'Negar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    //Se verifica que el select sea post 
                    if (isset($_POST['select_remunerado'])) {
                        try {
                            $campos = [
                                'txt_date_eleboracion1', 'txt_nombre1', 'txt_identificacion1',
                                'txt_cargo1', 'txt_area1', 'txt_tipo_permiso1', 'txt_sede1',
                                'date_inicio1', 'date_fin1', 'textarea_motivo1', 'txt_tipo_tiempo1',
                                'txt_cantidad_tiempo1', 'select_remunerado'
                            ];
                            //ciclo para recorrer los campos y sus valores obtenidos en caso de que no se encuentre
                            //un valor en algún campo entonces se arrojará una alerta que mencione el campo
                            //que falta por diligenciar
                            foreach ($campos as $campo) {
                                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                                    header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                                    exit;
                                }
                            }
                            //Instancia de clase ControladorPermisos
                            $control = new ControladorPermisos();

                            //Bloque try catch para manejo de errores
                            try {
                                if (isset($_POST['txt_hidden_estado']) && ($_POST['txt_hidden_estado'] === 'Pendiente' || $_POST['txt_hidden_estado'] === 'Negado')) {
                                    header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('Esta solicitud ya ha sido enviada a gestion humana.'));
                                    exit;
                                } else if (isset($_POST['txt_hidden_estado'])) {
                                    $permisos1 = new Permisos();
                                    $permisos1->setRemuneracion($_POST['select_remunerado']);
                                    $permisos1->setEstado_permiso('Negado'); //Pendiente
                                    $permisos1->setId_Permisos($_POST['txt_hidden']);
                                    if ($permisos1 !== null) {
                                        $control->controlUpdatePermisosJefeNo($permisos1);
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=success&mensaje=" . urlencode('Se ha negado la solicitud exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha podido, no aprobar la solicitud.'));
                                        exit;
                                    }
                                }
                            } catch (Exception $e) {
                                header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha pdodio, no aprobar la solicitud'));
                                exit;
                            }
                        } catch (Exception $e) {
                            header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('No se ha pdodio, no aprobar la solicitud'));
                            exit;
                        }
                    } else {
                        header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('Primero selecciona la remuneración de la solicitud.'));
                        exit;
                    }
                } else {
                    header("Location: ../vista/consultaSolicitudesEnviadasJefeHelmer.php?tipo=danger&mensaje=" . urlencode('Método inválido'));
                    exit;
                }

                break;

            default:

                break;
        }
    }
}
