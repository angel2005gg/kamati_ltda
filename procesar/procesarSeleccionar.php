<?php
require_once '../modelo/dao/UsuariosDao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['numero_identificacion'])) {
    $numero_identificacion = $_POST['numero_identificacion'];

    // Instanciar la clase correcta
    $usuarios = new UsuariosDao();

    // Obtener los datos del usuario
    $data = $usuarios->consultarActualizarUser($numero_identificacion);

    // Obtener los IDs de las áreas a las que pertenece el jefe
    $datas = $usuarios->consultarIdJefesArea($numero_identificacion);

    

    // Combinar ambos resultados en un solo array para enviar como JSON
    $response = [
        'usuario' => $data,
        'areas_jefe' => $datas
    ];

    echo json_encode($response);

}