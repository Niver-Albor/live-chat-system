<?php

    require 'conexion_bd.php';
    require 'comprobar_usuario.php';

    foreach($_GET as &$entrada)
    {
        $entrada = htmlentities($entrada);
    }
    
    // Comrpueba que existan los usuarios
    if(!comprobarUsuario($_GET['usuario_ID']))
    {
        $respuesta = ['error' => true, 'mensaje' => 'Buen intento, Leroy.'];
            echo json_encode($respuesta);
            return 1;
    }
    try
    {
        $consulta = $conexionBD->prepare('SELECT avatar FROM usuario WHERE usuario_ID = :id');
        $consulta->bindParam(':id',$_GET['usuario_ID']);
        $consulta->execute();

        $row = $consulta->fetch(pdo::FETCH_ASSOC);
        header("Content-Type: image/bin");
        echo $row['avatar'];
    }
    catch(PDOException $e)
    {
        echo 'Error al buscar imagen: ' . $e->getMessage();
    }

?>