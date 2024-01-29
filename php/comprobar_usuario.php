<?php
    require 'conexion_bd.php';

    function comprobarUsuario($ID)
    {
        global $conexionBD;
        try
        {
            $consulta = $conexionBD->prepare('SELECT EXISTS(SELECT 1 FROM usuario WHERE usuario_ID = :id)');
            $consulta->bindParam(':id',$ID);
            $consulta->execute();
            $resultado = $consulta->fetchColumn() == 1;
            return $resultado == 1;
        }
        catch(PDOException $e)
        {
            echo "Ay, papa.";
        }
        return 0;
    }