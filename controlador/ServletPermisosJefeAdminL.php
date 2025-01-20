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
    if ($menu === "solicitar") {
        switch ($accion) {
                //Se toma la accion de regresar al dashboard
            case "Regresar":
                header('Location: ../vista/DashboardJefeAdminL.php');
                exit;
                break;
                //Se toma la accion de enviar la solicitud
            case "Enviar":

                //Se obtiene el valor del radio seleccionado
                if (isset($_POST['radio1'])) {
                    //Se le aplica un try para manejo de errores a la accion
                    try {
                        //Se verifica si es cumpleaños o dia de la familia para verificar cuales son los campos 
                        //Cuales son los campos que debería de tener un valor al momento de enviar la solicitud
                        if ($_POST['radio1'] === 'Cumpleaños' || $_POST['radio1'] === 'DiaFamilia') {
                            //Si es cumpleaños o dia de la familia entonces en un arreglo colocamos los campos o input
                            //para los cuales validaremos si estan vacíos o no
                            $campos = [
                                'txt_date_eleboracion1', 'txt_nombre1', 'txt_identificacion1',
                                'txt_cargo1', 'txt_area1', 'txt_jefe1', 'txt_sede1',
                                'date_inicio', 'date_fin', 'textarea_motivo'
                            ];
                            //ciclo para recorrer los campos y sus valores obtenidos en caso de que no se encuentre
                            //un valor en algún campo entonces se arrojará una alerta que mencione el campo
                            //que falta por diligenciar
                            foreach ($campos as $campo) {
                                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                                    header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío'));
                                    exit;
                                }
                            }
                        } else if ($_POST['radio1'] === 'Vacaciones') {
                            //En caso de no se seleccionado el cumpleaños o el dia de la familia sino vacaciones
                            //entonces realizará unn arreglo con los campos que sean necesarios para
                            //las otras opciones de la solicitud
                            $campos = [
                                'txt_date_eleboracion1', 'txt_nombre1', 'txt_identificacion1',
                                'txt_cargo1', 'txt_area1', 'txt_jefe1', 'txt_sede1', 'inputHorasDias',
                                'date_inicio', 'date_fin', 'textarea_motivo'
                            ];
                            foreach ($campos as $campo) {
                                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                                    header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío'));
                                    exit;
                                }
                            }
                        } else {
                            //En caso de no se seleccionado el cumpleaños o el dia de la familia sino vacaciones
                            //entonces realizará unn arreglo con los campos que sean necesarios para
                            //las otras opciones de la solicitud
                            $campos = [
                                'txt_date_eleboracion1', 'txt_nombre1', 'txt_identificacion1',
                                'txt_cargo1', 'txt_area1', 'txt_jefe1', 'txt_sede1', 'inputHorasDias',
                                'date_inicio', 'date_fin', 'txt_total_horas1', 'textarea_motivo'
                            ];
                            foreach ($campos as $campo) {
                                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                                    header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío'));
                                    exit;
                                }
                            }
                        }

                        //Se instancia un objeto de de la clase ControladorPermisos();
                        $control = new ControladorPermisos();

                        //Validación de las fechas que se obtienen de los campos de texto de tipo date
                        if ((DateTime::createFromFormat('Y-m-d', $_POST["txt_date_eleboracion1"]) !== false) && (DateTime::createFromFormat('Y-m-d', $_POST["date_inicio"]) !== false) && (DateTime::createFromFormat('Y-m-d', $_POST["date_fin"]) !== false)) {
                            //Instancia de objeto de la clase Permisos();
                            $permisos = new Permisos();

                            //¡¡SE EMPIEZA A MANDAR LOS DATOS OBTENIDOS DE LOS CAMPOS DE TEXTO MEDIANTE
                            //LOS METODOS SETTERS DE LA CLASE PERMISOS
                            $permisos->setFecha_elaboracion($_POST["txt_date_eleboracion1"]);
                            $permisos->setTipo_permiso($_POST["radio1"]);
                            $permisos->setTiempo($_POST["radio2"]);
                            //Validación si es cumpleaños o dia de la familia se envía 1 dia por esa solicitud
                            if ($_POST['radio1'] === 'Cumpleaños' || $_POST['radio1'] === 'DiaFamilia') {
                                $permisos->setCantidad_tiempo(1);
                            } else {
                                //Si no es cumpleaños ni dia de la familia entonces se obtiene
                                //El valor del campo de la cantidad de horas o días
                                $permisos->setCantidad_tiempo($_POST["inputHorasDias"]);
                            }
                            $permisos->setFecha_inicio_novedad($_POST["date_inicio"]);
                            $permisos->setFecha_fin_novedad($_POST["date_fin"]);
                            //Validacion de vacaciones para settear los días compensados
                            if ($_POST["radio1"] === 'Vacaciones') {
                                $permisos->setDias_compensados($_POST['diascompensados']);
                                $permisos->setCantidad_dias_compensados($_POST['cantidad_dias']);
                            } else {
                                //Sino es vacaciones entonces los días compensados serán vacíos
                                $permisos->setDias_compensados(" ");
                                $permisos->setCantidad_dias_compensados(" ");
                            }

                            if ($_POST["txt_total_horas1"] != null || !empty($_POST["txt_total_horas1"])) {
                                $permisos->setTotal_horas_permiso($_POST["txt_total_horas1"]);
                            } else {
                                $permisos->setTotal_horas_permiso(" ");
                            }
                            $permisos->setMotivo_novedad($_POST["textarea_motivo"]);
                            $permisos->setId_Usuarios_permiso($_SESSION["idUser"]);
                            $permisos->setEstado_permiso('Enviado');

                            $jefeDao = new JefeAreaDao();
                            $perm = new JefeArea();
                            $perm = $jefeDao->consultaIdJefeUser($_SESSION['id_jefe_usuario_jefe']);

                            $permisos->setId_jefe_usuario($perm->getId_Jefe_Usuario());
                            $permisos->setId_gestionHumana1(4);
                            $permisos->setId_gestionHumana2(5);

                            //VERIFICACION POR MEDIO DE SWITCH PARA  SABER QUE TIPO DE PERMISO SE VA A ENVIAR
                            switch ($_POST['radio1']) {
                                case 'Permiso':
                                    if ($permisos !== null) {
                                        $control->controladorPermisos($permisos);
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud enviada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha enviado la solicitud de permiso.'));
                                        exit;
                                    }
                                    break;
                                case 'Licencia':
                                    if ($permisos !== null) {

                                        $control->controladorPermisos($permisos);
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud enviada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha enviado la solicitud para licencia.'));
                                        exit;
                                    }
                                    break;
                                case 'DiaFamilia':
                                    if ($permisos !== null) {
                                        $control->controladorPermisos($permisos);
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud enviada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha enviado la solicitud para día de la familia.'));
                                        exit;
                                    }
                                    break;
                                case 'Cumpleaños':
                                    if ($permisos !== null) {
                                        $control->controladorPermisos($permisos);
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud enviada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha enviado la solicitud de cumpleaños.'));
                                        exit;
                                    }
                                    break;
                                case 'Vacaciones':
                                    if ($permisos !== null) {
                                        $control->controladorPermisos($permisos);
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud enviada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha enviado tu solicitud de vacaciones.'));
                                        exit;
                                    }
                                    break;
                                default:
                                    header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Opción no valida.'));
                                    exit;
                                    break;
                            }
                        } else {
                            header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se estan cargando las fechas.'));
                            exit;
                        }
                    } catch (Exception $e) {
                        header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Método no valido.'));
                        exit;
                    }
                }else{
                    header("Location: ../vista/solicitarPermisoJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                    exit;
                }
                break;
            default:
                break;
        }
        //En caso de que el menu sea permisos entonces seguimos con diferentes acciones
    } else if ($menu === 'permisos') {
        switch ($accion) {
                //Primera acción de regreso al dashboard
            case 'Regresar':
                header("Location: ../vista/DashboardJefeAdminL.php");
                exit;
                break;
                //Accion en la que se aprueba o de coloca en pendiente 
            case 'Aprobar':
                //Se verifica que se obtenga un formulario con metodo POST 
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    //Se verifica que el select sea post 
                    if (isset($_POST['select_remunerado'])) {

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
                                header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                                exit;
                            }
                        }
                        //Instancia de clase ControladorPermisos
                        $control = new ControladorPermisos();

                        //Bloque try catch para manejo de errores
                        try {
                            //Si el usuario es mallory que es directora de gestion humana entonces 
                            //El permiso que apruebe será directamente aprobado por procesos 
                            //de la empresa
                            if ($_SESSION['idUser'] === 4) {

                                if ($_POST['txt_Remuneracion1'] !== "1") {

                                    $permisos1 = new Permisos();
                                    $permisos1->setRemuneracion($_POST['txt_Remuneracion1']);
                                    $permisos1->setEstado_permiso('Aprobado'); //No Aprobado
                                    $permisos1->setId_Permisos($_POST['txt_hidden']);
                                
                                    if ($permisos1 !== null) {
                                        $control->controlUpdatePermisos($permisos1);
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud aprobada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud.'));
                                        exit;
                                    }
                                } else if ($_POST['txt_Remuneracion1'] === "1") {
                                    $permisos1 = new Permisos();
                                    $permisos1->setRemuneracion($_POST['select_remunerado']);
                                    $permisos1->setEstado_permiso('Aprobado'); 
                                    $permisos1->setId_Permisos($_POST['txt_hidden']);
                                    $permisos1->setId_Tipo_Novedad_Permiso($_POST['select_tipoNovedad']);
                                    if ($permisos1 !== null) {
                                        $control->controlUpdatePermisos($permisos1);
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud aprobada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podico aprobar la solicitud.'));
                                        exit;
                                    }
                                } else {
                                    header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('La acción que intentas hacer no se puede ejecutar.'));
                                    exit;
                                }
                            } else {
                                header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No eres un usuario esperado para esta acción.'));
                                exit;
                            }
                            return true;
                        } catch (Exception $e) {
                            header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud.'));
                            exit;
                        }
                    } else {
                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud.'));
                        exit;
                    }
                } else {
                    header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Método no válido.'));
                    exit;
                }


                break;
            case 'No Aprobar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    //Se verifica que el select sea post 
                    if (isset($_POST['select_remunerado'])) {

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
                                header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                                exit;
                            }
                        }
                        //Instancia de clase ControladorPermisos
                        $control = new ControladorPermisos();

                        //Bloque try catch para manejo de errores
                        try {
                            //Si el usuario es mallory que es directora de gestion humana entonces 
                            //El permiso que apruebe será directamente aprobado por procesos 
                            //de la empresa
                            if ($_SESSION['idUser'] === 4) {

                                if ($_POST['txt_Remuneracion1'] !== "1") {

                                    $permisos1 = new Permisos();
                                    $permisos1->setRemuneracion($_POST['txt_Remuneracion1']);
                                    $permisos1->setEstado_permiso('No aprobado'); //No Aprobado
                                    $permisos1->setId_Permisos($_POST['txt_hidden']);
                                
                                    if ($permisos1 !== null) {


                                        $control->controlUpdatePermisosNo($permisos1);
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud no aprobada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido completar la acción.'));
                                        exit;
                                    }
                                } else if ($_POST['select_remunerado'] && ($_POST['txt_Remuneracion1']) === "1") {

                                    $permisos1 = new Permisos();
                                    $permisos1->setRemuneracion($_POST['select_remunerado']);
                                    $permisos1->setEstado_permiso('No aprobado'); //No Aprobado
                                    $permisos1->setId_Permisos($_POST['txt_hidden']);
                                    $permisos1->setId_Tipo_Novedad_Permiso($_POST['select_tipoNovedad']);
                                    if ($permisos1 !== null) {
                                        $control->controlUpdatePermisosNo($permisos1);
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud no aprobada exitosamente.'));
                                        exit;
                                    } else {
                                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                                        exit;
                                    }
                                } else {
                                    header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                                    exit;
                                }
                            } else {
                                header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                                exit;
                            }
                            header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                            exit;
                        } catch (Exception $e) {
                            header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                            exit;
                        }
                    } else {
                        header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                        exit;
                    }
                } else {
                    header("Location: ../vista/consultaSolicitudesEnviadasJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Método no válido.'));
                    exit;
                }

                break;

            default:

                break;
        }
    }else if($menu === 'pendientes'){
        switch ($accion) {
            //Primera acción de regreso al dashboard
        case 'Regresar':
            header("Location: ../vista/DashboardJefeAdminL.php");
            exit;
            break;
            //Accion en la que se aprueba o de coloca en pendiente 
        case 'Aprobar':
            //Se verifica que se obtenga un formulario con metodo POST 
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //Se verifica que el select sea post 
                if (isset($_POST['txt_Remuneracion1'])) {

                    $campos = [
                        'txt_date_eleboracion1', 'txt_nombre1', 'txt_identificacion1',
                        'txt_cargo1', 'txt_area1', 'txt_tipo_permiso1', 'txt_sede1',
                        'date_inicio1', 'date_fin1', 'textarea_motivo1', 'txt_tipo_tiempo1',
                        'txt_cantidad_tiempo1', 'txt_Remuneracion1'
                    ];
                    //ciclo para recorrer los campos y sus valores obtenidos en caso de que no se encuentre
                    //un valor en algún campo entonces se arrojará una alerta que mencione el campo
                    //que falta por diligenciar
                    foreach ($campos as $campo) {
                        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                            header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                            exit;
                        }
                    }
                    //Instancia de clase ControladorPermisos
                    $control = new ControladorPermisos();

                    //Bloque try catch para manejo de errores
                    try {
                        //Si el usuario es mallory que es directora de gestion humana entonces 
                        //El permiso que apruebe será directamente aprobado por procesos 
                        //de la empresa
                        if ($_SESSION['idUser'] === 4) {

                            if (isset($_POST['txt_Remuneracion1'])) {

                                $permisos1 = new Permisos();
                                $permisos1->setRemuneracion($_POST['txt_Remuneracion1']);
                                $permisos1->setEstado_permiso('Aprobado'); //No Aprobado
                                $permisos1->setId_Permisos($_POST['txt_hidden']);
               
                                if ($permisos1 !== null) {
                                    $control->controlUpdatePermisos($permisos1);
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud aprobada exitosamente.'));
                                    exit;
                                } else {
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud.'));
                                    exit;
                                }
                            } else if (isset($_POST['select_remunerado'])) {
                                $permisos1 = new Permisos();
                                $permisos1->setRemuneracion($_POST['select_remunerado']);
                                $permisos1->setEstado_permiso('Aprobado'); //No Aprobado
                                $permisos1->setId_Permisos($_POST['txt_hidden']);
                               
                                if ($permisos1 !== null) {
                                    $control->controlUpdatePermisos($permisos1);
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud aprobada exitosamente.'));
                                    exit;
                                } else {
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podico aprobar la solicitud.'));
                                    exit;
                                }
                            } else {
                                header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('La acción que intentas hacer no se puede ejecutar.'));
                                exit;
                            }
                        } else {
                            header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No eres un usuario esperado para esta acción.'));
                            exit;
                        }
                        return true;
                    } catch (Exception $e) {
                        header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud.'));
                        exit;
                    }
                } else {
                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido aprobar la solicitud.'));
                    exit;
                }
            } else {
                header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Método no válido.'));
                exit;
            }


            break;
        case 'No Aprobar':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                //Se verifica que el select sea post 
                if (isset($_POST['txt_Remuneracion1'])) {

                    $campos = [
                        'txt_date_eleboracion1', 'txt_nombre1', 'txt_identificacion1',
                        'txt_cargo1', 'txt_area1', 'txt_tipo_permiso1', 'txt_sede1',
                        'date_inicio1', 'date_fin1', 'textarea_motivo1', 'txt_tipo_tiempo1',
                        'txt_cantidad_tiempo1'
                    ];
                    //ciclo para recorrer los campos y sus valores obtenidos en caso de que no se encuentre
                    //un valor en algún campo entonces se arrojará una alerta que mencione el campo
                    //que falta por diligenciar
                    foreach ($campos as $campo) {
                        if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                            header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Uno de los campos esta vacío.'));
                            exit;
                        }
                    }
                    //Instancia de clase ControladorPermisos
                    $control = new ControladorPermisos();

                    //Bloque try catch para manejo de errores
                    try {
                        //Si el usuario es mallory que es directora de gestion humana entonces 
                        //El permiso que apruebe será directamente aprobado por procesos 
                        //de la empresa
                        if ($_SESSION['idUser'] === 4) {

                            if (isset($_POST['txt_Remuneracion1'])) {

                                $permisos1 = new Permisos();
                                $permisos1->setRemuneracion($_POST['txt_Remuneracion1']);
                                $permisos1->setEstado_permiso('No aprobado'); //No Aprobado
                                $permisos1->setId_Permisos($_POST['txt_hidden']);
                                
                                if ($permisos1 !== null) {


                                    $control->controlUpdatePermisosNo($permisos1);
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud no aprobada exitosamente.'));
                                    exit;
                                } else {
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No se ha podido completar la acción.'));
                            exit;
                                }
                            } else if (isset($_POST['select_remunerado'])) {

                                $permisos1 = new Permisos();
                                $permisos1->setRemuneracion($_POST['select_remunerado']);
                                $permisos1->setEstado_permiso('No aprobado'); //No Aprobado
                                $permisos1->setId_Permisos($_POST['txt_hidden']);
                                
                                if ($permisos1 !== null) {
                                    $control->controlUpdatePermisosNo($permisos1);
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=success&mensaje=" . urlencode('Solicitud no aprobada exitosamente.'));
                                    exit;
                                } else {
                                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                                    exit;
                                }
                            } else {
                                header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                                exit;
                            }
                        } else {
                            header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                            exit;
                        }
                        header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                        exit;
                    } catch (Exception $e) {
                        header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                        exit;
                    }
                } else {
                    header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('No ha podido completar la acción.'));
                    exit;
                }
            } else {
                header("Location: ../vista/consultaSolicitudesPendientesJefeAdminL.php?tipo=danger&mensaje=" . urlencode('Método no válido.'));
                exit;
            }

            break;

        default:

            break;
    }
    }
}
