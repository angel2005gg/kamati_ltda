<?php
require_once '../controlador/ControladorCotizaciones.php';
require_once '../modelo/ComercialProjects.php';
require_once '../modelo/ViaticosCotizacion.php';
require_once '../modelo/FactoresAdicionales.php';
require_once '../modelo/dao/FactoresCotizacionesDao.php';
require_once '../modelo/dao/FactorIndependienteDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $menu = $_POST['menu'] ?? '';
    $accion = $_POST['accion'] ?? '';

    if ($menu === 'crearCotizacion') {
        switch ($accion) {
            case 'crearcotizacion':
                $comercialProject = new ComercialProjects();
                $viaticosCotizacion = new ViaticosCotizacion();
                $viaticosCotizacion1 = new ViaticosCotizacion();
                $daoFactor = new FactoresCotizacionesDao();
                $factorAd1 = new FactoresAdicionales();
                $factorAd2 = new FactoresAdicionales();
                $factorAd3 = new FactoresAdicionales();
                $factorAd4 = new FactoresAdicionales();
                $controlador = new ControladorComercial();
                $factorIndependienteDao = new FactorIndependienteDao();
                $comercialProject->setNombre_cotizacion('Nueva Cotizacion');
                $comercialProject->setCodigo_cotizacion('000000000');

                if ($comercialProject) {
                    $newId = $controlador->controladorCreateProject($comercialProject);
                    $_SESSION['id_cotizacion'] = $newId;
                }

                $viaticosCotizacion->setNombre_viatico('Alimentacion');
                $viaticosCotizacion->setValor_diario(17000);
                $id1Insert = $controlador->controladorCreateDataViaticos($viaticosCotizacion);

                $viaticosCotizacion1->setNombre_viatico('Transporte');
                $viaticosCotizacion1->setValor_diario(12000);
                $id2Insert = $controlador->controladorCreateDataViaticos($viaticosCotizacion1);
                $insertedIds = $controlador->controladorCreateDataFactores();

                $factorAd1->setFactores('Siemens');
                $factorAd2->setFactores('Pilz');
                $factorAd3->setFactores('Rittal');
                $factorAd4->setFactores('PhxCnt');

                $insertIdFacAd1 = $controlador->controladorCreateDataFacAd($factorAd1);
                $insertIdFacAd2 = $controlador->controladorCreateDataFacAd($factorAd2);
                $insertIdFacAd3 = $controlador->controladorCreateDataFacAd($factorAd3);
                $insertIdFacAd4 = $controlador->controladorCreateDataFacAd($factorAd4);

                

                $idsInsertsCargos = $controlador->controladorCreateDataCargos();
                if (!empty($idsInsertsCargos)) {
                    echo '<script>';
                    foreach ($idsInsertsCargos as $index => $id) {
                        echo "sessionStorage.setItem('id_cargoValorCotizacion_{$index}', '{$id}');";
                    }
                    echo '</script>';
                } else {
                    echo '<script>console.error("No se obtuvieron IDs generados al registrar factores adicionales.");</script>';
                }
                if (!empty($id1Insert) && !empty($id2Insert)) {
                    echo '<script>';
                    echo "sessionStorage.setItem('idAlimentacion', '{$id1Insert}');";
                    echo "sessionStorage.setItem('idTransporte', '{$id2Insert}');";
                    echo '</script>';
                } else {
                    echo '<script>console.error("No se obtuvieron IDs generados al registrar factores adicionales.");</script>';
                }
                if (!empty($insertIdFacAd1) && !empty($insertIdFacAd2) && !empty($insertIdFacAd3) && !empty($insertIdFacAd4)) {
                    echo '<script>';
                    echo "sessionStorage.setItem('siemensFactor', '{$insertIdFacAd1}');";
                    echo "sessionStorage.setItem('pilzFactor', '{$insertIdFacAd2}');";
                    echo "sessionStorage.setItem('rittalFactor', '{$insertIdFacAd3}');";
                    echo "sessionStorage.setItem('pcntFactor', '{$insertIdFacAd4}');";
                    echo '</script>';
                } else {
                    echo '<script>console.error("No se obtuvieron IDs generados al registrar factores adicionales.");</script>';
                }

                if (!empty($insertedIds)) {
                    echo '<script>';
                    echo "sessionStorage.setItem('factorGlobalNameMo', '{$insertedIds[0]}');";
                    echo "sessionStorage.setItem('factorGlobalNameO', '{$insertedIds[1]}');";
                    echo "sessionStorage.setItem('factorGlobalNameV', '{$insertedIds[2]}');";
                    echo "sessionStorage.setItem('factorGlobalNameP', '{$insertedIds[3]}');";
                    echo '</script>';
                } else {
                    echo '<script>console.error("No se obtuvieron IDs generados al registrar factores.");</script>';
                }

                echo "<script>
                        window.open('../vista/cotizacionesTrabajador.php', '_blank');
                        window.location.href = '../vista/CreacionCotizacion.php';
                      </script>";
                exit;
                break;
        }
    }
}
?>