<?php
    #   Importar la conexion a la base de datos.
    require_once 'php/conexion_bd.php';
    #   Importa el archivo donde tengo las funciones que siempre llamo.
    require_once 'php/funciones.php';

    require_once 'php/comprobar_sesion.php';
    // print_r($_POST);
    // echo '<br>';
    // print_r($_SESSION);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Document</title>
</head>
<body>
<body>
    <div class="wrapper">
        <div class="info-chat">
            <header>
                <div class="imagen" id="datos_oyente_imagen">
                    <?php
                        echo "<img src='php/cargar_foto.php?usuario_ID=$_POST[oyente_ID]' alt=''>";
                    ?>
                </div>
                <div class="info-usuario" id="datos_oyente_info">
                    <p>Nombre de usuario</p>
                    <p>En linea</p>
                </div>
                <div class="volver">
                    <button id="boton-volver">
                        Volver
                    </button>
                </div>
            </header>
        </div>
        <section class="bandeja" id="bandeja">
        </section>
        <section class="escritura">
            <form action="" method="get" id="form-mensaje">
                <input type="text" id="mensaje" name="mensaje" autocomplete="off">
                <input type="submit" class="enviar" id="boton-enviar" value="Enviar">
                <?php
                    echo "<input type='hidden' name='sender_usuario_ID' id='sender_usuario_ID' value=$_SESSION[usuario_ID]>";
                    echo "<input type='hidden' name='reciever_usuario_ID' id='reciever_usuario_ID' value=$_POST[oyente_ID]>";
                ?>
            </form>
            <!-- <button id="boton-enviar">Enviar</button> -->
        </section>
    </div>
    <script src="js/chat.js"></script>
    <script>
        var botonVolver = document.getElementById('boton-volver');
        botonVolver.addEventListener('click',function(){
            // alert("Tu ere' un lambe bicho");
            window.location.href = "usuarios.php";
        })
    </script>
</body>
</body>
</html>