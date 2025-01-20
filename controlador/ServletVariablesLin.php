<?php

require_once '../modelo/dao/VariablesDao.php';
require_once '../modelo/Variables.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $menu = $_POST['menu'] ?? '';

    $variablesDao = new VariablesDao();
    $variables = new Variables();

    if ($menu === 'variables') {
        
        if (isset($_POST['btn_update_num1'])) {
            try{
                $horaSemanal = ($_POST['txt_nombre_cargo0']);

                if($horaSemanal != null && $_POST['txt_nombre_cargo0'] != 0){
                    $variables->setValor_variable($horaSemanal);
                    $variables->setId_Variables($_POST['txt_hidden_btn1']);
                    if($variables != null){
                        $variablesDao->updateVariables($variables);
                        header("Location: ../vista/variablesLin.php?tipo=success&mensaje=" . urlencode('Actualización de horas semanales exitosa.'));
                        exit;
                    }else{
                        header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                        exit;
                    }
                }else{
                    //alert 
                    header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('No puedes registrar cero.'));
                    exit;
                }
            }catch(Exception $e){
                header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
            
        } else if (isset($_POST['btn_update_num2'])) {
            try{
                $salarioMensual = ($_POST['txt_nombre_cargo1']);

            if($salarioMensual != null && $_POST['txt_nombre_cargo1'] != 0){
                $variables->setValor_variable($salarioMensual);
                $variables->setId_Variables($_POST['txt_hidden_btn2']);
                if($variables != null){
                    $variablesDao->updateVariables($variables);
                    header("Location: ../vista/variablesLin.php?tipo=success&mensaje=" . urlencode('Actualización de salario mínimo exitosa.'));
                    exit;
                }else{
                    header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            }else{
                //alert vacio
                header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('No puedes registrar cero.'));
                exit;
            }
            }catch(Exception $e){
                header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
            

        } else if (isset($_POST['btn_update_num3'])) {
            try{
                $horasDia = ($_POST['txt_nombre_cargo2']);

            if($horasDia != null && $_POST['txt_nombre_cargo2'] != 0){
                $variables->setValor_variable($horasDia);
                $variables->setId_Variables($_POST['txt_hidden_btn3']);
                if($variables != null){
                    $variablesDao->updateVariables($variables);
                    header("Location: ../vista/variablesLin.php?tipo=success&mensaje=" . urlencode('Actualización de horas laborales exitosa.'));
                    exit;
                }else{
                    header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                    exit;
                }
            }else{
                //alert vacio
                header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('No puedes registrar cero.'));
                exit;
            }
            }catch(Exception $e){
                header("Location: ../vista/variablesLin.php?tipo=danger&mensaje=" . urlencode('Actualización fallida.'));
                exit;
            }
            
        }
    }
}