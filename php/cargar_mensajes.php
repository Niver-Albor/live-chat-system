<?php
    header('Content-Type: application/json; charset=utf-8');
    require 'conexion_bd.php';
    require 'comprobar_usuario.php';

    session_start();

    foreach($_GET as &$entrada)
    {
        $entrada = htmlentities($entrada);
    }

    $usuario = $_GET['usuario_ID'];
    $usuario_2 = $_GET['usuario2_ID'];

    // Comrpueba que existan los usuarios
    if(!comprobarUsuario($usuario) || !comprobarUsuario($usuario_2))
    {
        $respuesta = ['error' => true, 'mensaje' => 'Buen intento, Leroy.'];
            echo json_encode($respuesta);
            return 1;
    }

    $mensaje_lista = [];
    $respuesta = [];

    try
    {
        // Comrpueba que existan los usuarios.
        if(!comprobarUsuario($usuario) || !comprobarUsuario($usuario_2))
            throw new PDOException('No, no, no, no... Por aca no pasas, Leroy.');
        #   Comprueba que las IDs sean validas.
        if($usuario != $_SESSION['usuario_ID'] || $usuario == $usuario_2)
        {
            // $respuesta = ['error' => true, 'mensaje' => 'No, no, no, no... Por aca no pasas, Leroy.'];
            // echo json_encode($respuesta);
            // return 1;
            throw new PDOException('No, no, no, no... Por aca no pasas, Leroy.');
        }
        // PASO 1
        $consulta = $conexionBD->prepare('UPDATE mensaje SET estado = 1 WHERE sender_usuario_ID = :id1 AND reciever_usuario_ID = :id2');
        $consulta->bindParam(':id1',$usuario_2);
        $consulta->bindParam(':id2',$usuario);
        $consulta->execute();
        // PASO 2
        $consulta = $conexionBD->prepare('SELECT * FROM mensaje WHERE sender_usuario_ID = :id1 AND reciever_usuario_ID = :id2 OR sender_usuario_ID = :id2 AND reciever_usuario_ID = :id1 ORDER BY mensaje_ID ASC');
        $consulta->bindParam(':id1',$usuario);
        $consulta->bindParam(':id2',$usuario_2);
        $consulta->execute();
        $resultado = $consulta->fetchAll(pdo::FETCH_ASSOC);
        foreach($resultado as $fila)
        {
            $mensaje = [
                'mensaje_ID' => $fila['mensaje_ID'],
                'mensajeDB' => $fila['mensaje']
            ];
            if($fila['sender_usuario_ID'] == $usuario)
            $mensaje['tipo'] = 1;
            else
                $mensaje['tipo'] = 2;
            
            $respuesta[] = $mensaje;
        }
    }
    catch(PDOException $e)
    {
        $respuesta = ['error' => true, 'mensaje' => $e->getMessage()];
    }
    
    echo json_encode($respuesta);

?>