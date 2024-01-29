<?php
    header('Content-Type: application/json; charset=utf-8');
    require 'conexion_bd.php';
    require 'comprobar_usuario.php';

    function revisarMensaje($senderID, $user2ID)
    {
        global $conexionBD;
        try
        {
            $consulta = $conexionBD->prepare('SELECT EXISTS(SELECT 1 FROM mensaje 
            WHERE sender_usuario_ID = :id1 AND reciever_usuario_ID = :id2 AND estado = 0)');
            $consulta->bindParam(':id1',$senderID);
            $consulta->bindParam(':id2',$user2ID);
            $consulta->execute();
            return $consulta->fetchColumn() == 1;
        }
        catch(PDOException $e)
        {
            return 0;
        }
    }

    session_start();

    $usuarioID = $_SESSION['usuario_ID'];

    if(!comprobarUsuario($usuarioID))
    {
        header('Location:logout.php');
    }

    $respuesta = [];
    $registro = [];

    try
    {
        $consulta = $conexionBD->prepare('SELECT * FROM usuario');
        $consulta->execute();
        $resultado = $consulta->fetchAll(pdo::FETCH_ASSOC);

        foreach($resultado as $fila)
        {
            if($fila['usuario_ID'] == $usuarioID)
                continue;
            $mensajePendiente = revisarMensaje($fila['usuario_ID'],$usuarioID);
            $registro = 
            [
                'usuarioID' => $fila['usuario_ID'],
                'nombreUsuario' => $fila['nombre_usuario'],
                'estado' => $fila['enlinea'],
                'pendiente' => $mensajePendiente
            ];

            $respuesta[] = $registro;
        }
    }
    catch(PDOException $e)
    {
        $respuesta = ['error' => true, 'mensaje' => $e->getMessage()];
    }
    
    echo json_encode($respuesta);

?>