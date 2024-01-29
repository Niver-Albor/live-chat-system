<?php
    require 'conexion_bd.php';

    session_start();

    try
    {
        $userID = $_SESSION['usuario_ID'];
        $consulta = $conexionBD->prepare('DELETE FROM mensaje WHERE reciever_usuario_ID = :id OR sender_usuario_ID = :id');
        $consulta->bindParam(':id',$userID);
        $consulta->execute();
        $consulta = $conexionBD->prepare("DELETE FROM usuario WHERE usuario_ID = :id");
        $consulta->bindParam(':id',$userID);
        $consulta->execute();
    }
    catch(PDOException $e)
    {
        echo "<script>alert('Leroy, me has ganado.');</script>";
        echo $e->getMessage();
    }
    print_r($_SESSION);
    header('Location:logout.php');