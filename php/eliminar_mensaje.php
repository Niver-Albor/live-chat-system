<?php
    header('Content-Type: application/json; charset=utf-8');
    require 'conexion_bd.php';

    session_start();

    if($_POST['senderID'] != $_SESSION['usuario_ID'])
    {
        echo "NO TAN RAPIDO, LEROY!";
        return 1;
    }

    try
    {
        $consulta = $conexionBD->prepare('DELETE FROM mensaje WHERE mensaje_ID = :mensaje_id AND sender_usuario_ID = :usuario_id');
        $consulta->bindParam(':mensaje_id',$_POST['mensajeID']);
        $consulta->bindParam(':usuario_id',$_SESSION['usuario_ID']);
        $consulta->execute();
    }
    catch(PDOException $e)
    {
        echo "NOOOOOOoooo... .  .";
        echo $e->getMessage();
    }
?>