<?php
    header('Content-Type: application/json; charset=utf-8');
    require 'conexion_bd.php';

    foreach($_GET as &$entrada)
    {
        $entrada = htmlentities($entrada);
    }

    $usuario_ID = $_GET['usuario_ID'];
    $respuesta = [];

    try
    {
        $consulta = $conexionBD->prepare('SELECT nombre_usuario, enlinea FROM usuario WHERE usuario_ID = :id');
        $consulta->bindParam(':id',$usuario_ID);
        $consulta->execute();
        $resultado = $consulta->fetch(pdo::FETCH_ASSOC);

        $info = [
            'nombre_usuario' => $resultado['nombre_usuario'],
            'enlinea' => $resultado['enlinea']
        ];

        $respuesta = $info;

    }
    catch(PDOException $e)
    {
        $respuesta = ['error' => true, 'mensaje' => $e->getMessage()];
    }

    // Envia la respuesta como un JSON.
    echo json_encode($respuesta);
?>