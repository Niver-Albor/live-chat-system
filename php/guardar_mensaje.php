<?php
    header('Content-Type: application/json; charset=utf-8');
    require 'conexion_bd.php';
    require 'comprobar_usuario.php';
    
    session_start();

    foreach($_GET as &$entrada)
    {
        $entrada = htmlspecialchars($entrada);
    }

    $sender = $_GET['sender_usuario_ID'];
    $reciever = $_GET['reciever_usuario_ID'];
    $mensaje = $_GET['mensaje'];
    $respuesta = '';

    // Comrpueba que existan los usuarios
    // if(!comprobarUsuario($sender) || !comprobarUsuario($reciever))
    // {
    //     $respuesta = ['error' => true, 'mensaje' => 'Buen intento, Leroy.'];
    //         echo json_encode($respuesta);
    //         return 1;
    // }

    try
    {
        #   Comprueba que las IDs sean validas.
        if($sender != $_SESSION['usuario_ID'])
        {
            $respuesta = ['error' => true, 'mensaje' => 'Buen intento, Leroy ;)'];
            echo json_encode($respuesta);
            return 1;
        }
        
        $consulta = $conexionBD->prepare('INSERT INTO mensaje (mensaje, sender_usuario_ID, reciever_usuario_ID, estado) VALUES(:msj, :sender, :reciever, 0)');
        $consulta->bindParam(':msj',$mensaje);
        $consulta->bindParam(':sender',$sender);
        $consulta->bindParam(':reciever',$reciever);
        if(isset($_GET['mensaje']))
            if($consulta->execute() == false)
                $respuesta = ['error' => true];
            else
                $respuesta = [];
            
    }
    catch(PDOException $e)
    {
        $respuesta = ['error' => true, 'mensaje' => $e->getMessage()];
    }

    echo json_encode('Buah, pero que graficos chaval.');
?>