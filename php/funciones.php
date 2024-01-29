<?php

    require 'conexion_bd.php';

    function obtenerTabla($nombre)
    {
        #   Accede a las variables globales.
        global $conexionBD;

        try
        {
            #   Prepara la consulta segun el nombre.
            switch($nombre)
            {
                case 'usuario':
                    $consulta = $conexionBD->prepare('SELECT * FROM usuario;');
                    break;
                case 'mensaje':
                    $consulta = $conexionBD->prepare('SELECT * FROM mensaje;');
                    break;
                default:
                    throw new PDOException('El nombre especificado no coincide con ninguna tabla en la base de datos.');
                    break;
            }
            #   Ejecuta la consulta.
            $consulta->execute();
            #   Devuelve el resultado de la consulta como un arreglo asociativo.
            return $consulta->fetchAll(pdo::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            echo '<script>alert(' . $e->getMessage() . ');</script>';
        }
    }

    function obtenerUsuarioPorEmail($email)
    {
        #   Accede a las variables globales.
        global $conexionBD;

        try
        {
            #   Prepara la consulta segun el nombre.
            $consulta = $conexionBD->prepare('SELECT * FROM usuario WHERE correo_usuario = :email');
            #   Remplaza los valores.
            $consulta->bindParam(':email',$email);
            #   Ejecuta la consulta.
            $consulta->execute();
            #   Devuelve el resultado de la consulta como un arreglo asociativo.
            return $consulta->fetch(pdo::FETCH_ASSOC);
        }
        catch(PDOException $e)
        {
            echo '<script>alert(' . $e->getMessage() . ');</script>';
        }
    }

    function actualizarEstado ($ID, $estadoNuevo)
    {
        #   Acceder a las variables globales.
        global $conexionBD;
        try
        {
            #   Prepara la consulta SQL.
            $consulta = $conexionBD->prepare('UPDATE usuario SET enlinea = :estado WHERE usuario_ID = :id');
            #   Remplaza los valores.
            $consulta->bindParam(':estado',$estadoNuevo);
            $consulta->bindParam(':id',$ID);
            #   Ejecuta la consulta.
            $consulta->execute();
        }
        catch(PDOException $e)
        {
            echo '<script>alert(' . $e->getMessage() . ');</script>';
        }
    }
?>