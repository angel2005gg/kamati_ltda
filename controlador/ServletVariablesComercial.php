<?php

require_once '../modelo/dao/FactoresDao.php';
require_once '../modelo/Factores.php';
require_once '../modelo/dao/ViaticosDao.php';
require_once '../modelo/Viaticos.php';
require_once '../modelo/dao/CargoCotizacionDao.php';
require_once '../modelo/CargoCotizacion.php';
require_once '../modelo/Recargos.php';
require_once '../modelo/dao/RecargosDao.php';
require_once '../modelo/HorasJornada.php';
require_once '../modelo/dao/HorasJornadaDao.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu = $_POST['menu'] ?? '';

    $variablesDao = new FactoresDao();
    $variables = new Factores();
    $viaticosDao = new ViaticosDao();
    $viaticos = new Viaticos();
    $cargoCotDao = new CargoCotizacionDao();
    $cargoCot = new CargoCotizacion();
    $recargosDao = new RecargosDao();
    $recargos = new Recargos();
    $horasJornadaDao = new HorasJornadaDao();
    $horasJornada = new HorasJornada();

    if ($menu === 'variables') {
        if (isset($_POST['btn_update_num1'])) {
            $horaSemanal = ($_POST['txt_nombre_FactorMo']);

            if ($horaSemanal != null && $_POST['txt_nombre_FactorMo'] != 0) {
                $variables->setFactores($horaSemanal);
                $variables->setId_Factores($_POST['txt_hidden_btn1']);
                if ($variables != null) {
                    $variablesDao->updateFactores($variables);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num2'])) {
            $salarioMensual = ($_POST['txt_nombre_FactorO']);

            if ($salarioMensual != null && $_POST['txt_nombre_FactorO'] != 0) {
                $variables->setFactores($salarioMensual);
                $variables->setId_Factores($_POST['txt_hidden_btn2']);
                if ($variables != null) {
                    $variablesDao->updateFactores($variables);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num3'])) {
            $horasDia = ($_POST['txt_nombre_Viaticos']);

            if ($horasDia != null && $_POST['txt_nombre_Viaticos'] != 0) {
                $variables->setFactores($horasDia);
                $variables->setId_Factores($_POST['txt_hidden_btn3']);
                if ($variables != null) {
                    $variablesDao->updateFactores($variables);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num4'])) {
            $horasDia = ($_POST['txt_nombre_Poliza']);

            if ($horasDia != null && $_POST['txt_nombre_Poliza'] != 0) {
                $variables->setFactores($horasDia);
                $variables->setId_Factores($_POST['txt_hidden_btn4']);
                if ($variables != null) {
                    $variablesDao->updateFactores($variables);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num5'])) {
            $horasDia = ($_POST['txt_viatico_unos']);

            if ($horasDia != null && $_POST['txt_viatico_unos'] != 0) {
                $viaticos->setValor_diario($horasDia);
                $viaticos->setId_Viaticos($_POST['txt_hidden_btn5']);
                if ($viaticos != null) {
                    $viaticosDao->updateViaticos($viaticos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num6'])) {
            $horasDia = ($_POST['txt_viatico_doss']);

            if ($horasDia != null && $_POST['txt_viatico_doss'] != 0) {
                $viaticos->setValor_diario($horasDia);
                $viaticos->setId_Viaticos($_POST['txt_hidden_btn6']);
                if ($viaticos != null) {
                    $viaticosDao->updateViaticos($viaticos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }









            //DE AQUI PARA ABAJO SON LOS DE TARIFAS
        } else if (isset($_POST['btn_update_num7'])) {
            $horasDia = ($_POST['txt_siete']);

            if ($horasDia != null && $_POST['txt_siete'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn7']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num8'])) {
            $horasDia = ($_POST['txt_ocho']);

            if ($horasDia != null && $_POST['txt_ocho'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn8']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num9'])) {
            $horasDia = ($_POST['txt_nueve']);

            if ($horasDia != null && $_POST['txt_nueve'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn9']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num10'])) {
            $horasDia = ($_POST['txt_diez']);

            if ($horasDia != null && $_POST['txt_diez'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn10']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num11'])) {
            $horasDia = ($_POST['txt_once']);

            if ($horasDia != null && $_POST['txt_once'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn11']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num12'])) {
            $horasDia = ($_POST['txt_doce']);

            if ($horasDia != null && $_POST['txt_doce'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn12']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num13'])) {
            $horasDia = ($_POST['txt_trece']);

            if ($horasDia != null && $_POST['txt_trece'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn13']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num14'])) {
            $horasDia = ($_POST['txt_catorce']);

            if ($horasDia != null && $_POST['txt_catorce'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn14']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num15'])) {
            $horasDia = ($_POST['txt_quince']);

            if ($horasDia != null && $_POST['txt_quince'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_btn15']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else if (isset($_POST['btn_update_num16'])) {
            $horasDia = ($_POST['txt_diez_y_seis']);

            if ($horasDia != null && $_POST['txt_diez_y_seis'] != 0) {
                $cargoCot->setValor_dia($horasDia);
                $cargoCot->setId_CargoCotizacion($_POST['txt_hidden_Recargosbtn1']);
                if ($cargoCot != null) {
                    $cargoCotDao->updateValorCargos($cargoCot);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
            //DESDE AQUI SE EMPIEZA CON LOS RECARGOS
        } 
        else if (isset($_POST['btn_update_Recargos1'])) {
            $horasDia = ($_POST['txt_nombre_Recargo1']);

            if ($horasDia != null && $_POST['txt_nombre_Recargo1'] != 0) {
                $recargos->setValorRecargo($horasDia);
                $recargos->setIdRecargos($_POST['txt_hidden_Recargosbtn1']);
                if ($recargos != null) {
                    $recargosDao->updateRecargos($recargos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Recargos2'])) {
            $horasDia = ($_POST['txt_nombre_Recargo2']);

            if ($horasDia != null && $_POST['txt_nombre_Recargo2'] != 0) {
                $recargos->setValorRecargo($horasDia);
                $recargos->setIdRecargos($_POST['txt_hidden_Recargosbtn2']);
                if ($recargos != null) {
                    $recargosDao->updateRecargos($recargos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Recargos3'])) {
            $horasDia = ($_POST['txt_nombre_Recargo3']);

            if ($horasDia != null && $_POST['txt_nombre_Recargo3'] != 0) {
                $recargos->setValorRecargo($horasDia);
                $recargos->setIdRecargos($_POST['txt_hidden_Recargosbtn3']);
                if ($recargos != null) {
                    $recargosDao->updateRecargos($recargos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Recargos4'])) {
            $horasDia = ($_POST['txt_nombre_Recargo4']);

            if ($horasDia != null && $_POST['txt_nombre_Recargo4'] != 0) {
                $recargos->setValorRecargo($horasDia);
                $recargos->setIdRecargos($_POST['txt_hidden_Recargosbtn4']);
                if ($recargos != null) {
                    $recargosDao->updateRecargos($recargos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Recargos5'])) {
            $horasDia = ($_POST['txt_nombre_Recargo5']);

            if ($horasDia != null && $_POST['txt_nombre_Recargo5'] != 0) {
                $recargos->setValorRecargo($horasDia);
                $recargos->setIdRecargos($_POST['txt_hidden_Recargosbtn5']);
                if ($recargos != null) {
                    $recargosDao->updateRecargos($recargos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Recargos6'])) {
            $horasDia = ($_POST['txt_nombre_Recargo6']);

            if ($horasDia != null && $_POST['txt_nombre_Recargo6'] != 0) {
                $recargos->setValorRecargo($horasDia);
                $recargos->setIdRecargos($_POST['txt_hidden_Recargosbtn6']);
                if ($recargos != null) {
                    $recargosDao->updateRecargos($recargos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Recargos7'])) {
            $horasDia = ($_POST['txt_nombre_Recargo7']);

            if ($horasDia != null && $_POST['txt_nombre_Recargo7'] != 0) {
                $recargos->setValorRecargo($horasDia);
                $recargos->setIdRecargos($_POST['txt_hidden_Recargosbtn7']);
                if ($recargos != null) {
                    $recargosDao->updateRecargos($recargos);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Hora1'])) {
            $horasDia = ($_POST['txt_nombre_Hora1']);

            if ($horasDia != null && $_POST['txt_nombre_Hora1'] != 0) {
                $horasJornada->setHoraJornada($horasDia);
                $horasJornada->setIdHoras($_POST['txt_hidden_Horabtn1']);
                if ($horasJornada != null) {
                    $horasJornadaDao->updateHoras($horasJornada);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Hora2'])) {
            $horasDia = ($_POST['txt_nombre_Hora2']);

            if ($horasDia != null && $_POST['txt_nombre_Hora2'] != 0) {
                $horasJornada->setHoraJornada($horasDia);
                $horasJornada->setIdHoras($_POST['txt_hidden_Horabtn2']);
                if ($horasJornada != null) {
                    $horasJornadaDao->updateHoras($horasJornada);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Hora3'])) {
            $horasDia = ($_POST['txt_nombre_Hora3']);

            if ($horasDia != null && $_POST['txt_nombre_Hora3'] != 0) {
                $horasJornada->setHoraJornada($horasDia);
                $horasJornada->setIdHoras($_POST['txt_hidden_Horabtn3']);
                if ($horasJornada != null) {
                    $horasJornadaDao->updateHoras($horasJornada);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } 
        else if (isset($_POST['btn_update_Hora4'])) {
            $horasDia = ($_POST['txt_nombre_Hora4']);

            if ($horasDia != null && $_POST['txt_nombre_Hora4'] != 0) {
                $horasJornada->setHoraJornada($horasDia);
                $horasJornada->setIdHoras($_POST['txt_hidden_Horabtn4']);
                if ($horasJornada != null) {
                    $horasJornadaDao->updateHoras($horasJornada);
                    header("Location: ../vista/variablesComerialJefe.php?tipo=success&mensaje=" . urlencode('Actualización exitosa.'));
                    exit;
                } else {
                    header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            } else {
                //alert vacio
                header("Location: ../vista/variablesComerialJefe.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
        } else {
            header("Location: ../vista/variablesL.php?tipo=danger&mensaje=" . urlencode('Método no válido.'));
            exit;
        }
    } else {
        return false;
    }
} else {
    return false;
}
