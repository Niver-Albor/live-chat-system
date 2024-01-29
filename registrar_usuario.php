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
    #   Consulta los datos de los usuarios en la base de datos.
    $usuario_lista = obtenerTabla('usuario');
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
            $entrada = htmlspecialchars($entrada);
        }
        try
        {
            #   Comprueba si el nombre de usuario esta vacio.
            if(empty($formulario['nombre_usuario']))
                throw new Exception('Nombre de usuario no especificado.');
            #   Comprueba si el email esta vacio.
            if(empty($formulario['correo_usuario']))
                throw new Exception('Email del usuario no especificado.');
            #   Comprueba si la contrasena esta vacia.
            if(empty($formulario['contrasena']))
                throw new Exception('Contrasena del usuario no especificada.');
            #   Comprueba si el email coincide con algun otro en la base de datos.
            foreach($usuario_lista as $usuario)
            {
                if($usuario['correo_usuario'] == $formulario['correo_usuario'])
                {
                    throw new Exception('El email especificado ya esta asignado a otro usuario.');
                    break;
                }
            }
            #   Comprueba si la imagen ingresada es valida.
            $formulario['avatar'] = file_get_contents($_FILES['avatar']['tmp_name']);
        }
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo $error;
        }
        #   Realiza el registro en la base de datos.
        if(empty($error))
        {
            try
            {   #   Encripta la contrasena.
                $formulario['contrasena'] = password_hash($formulario['contrasena'],PASSWORD_BCRYPT);
                #   Prepara la consulta.
                $consulta = $conexionBD->prepare('INSERT INTO usuario (nombre_usuario, correo_usuario, contrasena, avatar) VALUES (:nombre, :correo, :contrasena, :img)');
                #   Remplaza los valores.
                $consulta->bindParam(':nombre',$formulario['nombre_usuario']);
                $consulta->bindParam(':correo',$formulario['correo_usuario']);
                $consulta->bindParam(':contrasena',$formulario['contrasena']);
                $consulta->bindParam(':img',$formulario['avatar']);
                #   Ejecuta la consulta.
                $resultado = $consulta->execute();
                if($resultado)
                    echo "<script>alert('Usuario registrado exitosamente');</script>";
                header('Location:index.php');
            }
            catch(PDOException $e)
            {
                $error = $e->getMessage();
                echo "$error";
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
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <div class="wrapper">
        <div class="titulo"><b>Registrar Usuario</b></div>
        <form action="" method="post" autocomplete="on" enctype="multipart/form-data">
            <!-- Nombre de usuario -->
            <div class="campo">
                <input type="text" name="nombre_usuario" required>
                <label for="nombre_usuario">Nombre de usuario</label>
            </div>
            <!-- Email -->
            <div class="campo">
                <input type="email" name="correo_usuario" required>
                <label for="correo_usuario">Email</label>
            </div>
            <!-- Contrasena -->
            <div class="campo">
                <input type="password" name="contrasena" required>
                <label for="contrasena">Contraseña</label>
            </div>
            <!-- Imagen -->
            <div class="campo">
                <button id="boton-imagen">Elegir archivo</button>
                <input type="file" name="avatar" id="archivo" class="archivo" accept="image/*" required>
            </div>
            <!-- Enviar -->
            <div class="campo">
                <input type="submit" name="submit" value="Registrarse">
            </div>
            <!-- Iniciar sesion -->
           <div class="enlace">
                <a href="index.php">Iniciar sesion</a>
           </div>
        </form>
    </div>

    <script>
        let boton_imagen = document.getElementById('boton-imagen');
        let input = document.getElementById('archivo');
        boton_imagen.addEventListener('click', function() {
            input.click();
        });
    </script>
</body>
</html>