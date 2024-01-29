<?php
    #   Importar la conexion a la base de datos.
    require_once 'php/conexion_bd.php';
    #   Importa el archivo donde tengo las funciones que siempre llamo.
    require_once 'php/funciones.php';

    session_start();
    if(isset($_SESSION['usuario_ID']))
        header('Location:usuarios.php');

    //////////////////////////////////          SESSION         ////////////////////////////////////////////////
    // require 'php/comprobar_sesion.php';
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ############################################################################################################
    #           CONSULTAS A LA BASE DE DATOS.
    ############################################################################################################
    /////////////////////////////////       Funciones       ////////////////////////////////////////////////////
    ############################################################################################################
    #           SI SE ENVIO UN FORMULARIO.
    ############################################################################################################
    ////////////////////////////////////          POST         /////////////////////////////////////////////////
    if(isset($_POST['submit']))
    {
        $formulario = $_POST;
        #   Prepara los datos para ser procesados.
        foreach($formulario as &$entrada)
        {
            if(!is_string($entrada))
                continue;
            $entrada = htmlentities($entrada);
        }
        try
        {
            #   Comprueba si el email esta vacio.
            if(empty($formulario['correo_usuario']))
                throw new Exception('Email del usuario no especificado.');
            #   Comprueba si la contrasena esta vacia.
            if(empty($formulario['contrasena']))
                throw new Exception('Contrasena del usuario no especificada.');
        }
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo $error;
        }
        #   Cosulta que los datos ingresados coincidan con un usuario.
        if(empty($error))
        {
            try
            {   #   Busca un registro con el que coincida el email.
                $registro = obtenerUsuarioPorEmail($formulario['correo_usuario']);
                if(empty($registro['usuario_ID']))
                    throw new PDOException('No se encontraron usuarios.');
                if(password_verify($formulario['contrasena'],$registro['contrasena']))
                {
                    $_SESSION = $registro;
                    $_SESSION['avatar'] = '';
                    echo "<script>alert('Sesion iniciada correctamente.');</script>";
                    actualizarEstado($_SESSION['usuario_ID'],1);
                    header('Location:usuarios.php');
                }

            }
            catch(PDOException $e)
            {
                $error = $e->getMessage();
                echo $error;
            }
        }
    }
    ////////////////////////////////////          GET         //////////////////////////////////////////////////
    ############################################################################################################
    ############################################################################################################
    #           FUNCIONES.
    ############################################################################################################
    ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Iniciar Sesión</title>
</head>
<body>
    <div class="wrapper">
        <div class="titulo"><b>Undefined Chat</b></div>
        <form action="" method="post" autocomplete="off">
            <!-- Email de Usuario -->
            <div class="campo">
                <input type="email" name="correo_usuario" required>
                <label for="correo_usuario">Email</label>
            </div>
            <!-- Contrasena -->
            <div class="campo">
                <input type="password" name="contrasena" required>
                <label for="contrasena">Contraseña</label>
            </div>
            <!-- Enviar -->
           <div class="campo">
                <input type="submit" name="submit" value="Iniciar sesión">
           </div>
           <!-- Registrarse -->
           <div class="enlace">
                <a href="registrar_usuario.php">Registrarse</a>
           </div>
        </form>
    </div>
</body>
</html>