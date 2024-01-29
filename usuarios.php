<?php
    #   Importar la conexion a la base de datos.
    require_once 'php/conexion_bd.php';
    #   Importa el archivo donde tengo las funciones que siempre llamo.
    require_once 'php/funciones.php';

    require_once 'php/comprobar_sesion.php';

    $usuario_lista = obtenerTabla('usuario');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/estilos.css">
    <title>Usuarios</title>
</head>
<body>
    <div class="wrapper">
        <section class="info-usuario">
            <header>
                <?php
                    echo "<div class='imagen'>
                    <img src='php/cargar_foto.php?usuario_ID=$_SESSION[usuario_ID]' alt=''>
                </div>";
                ?>
                <div class="contenido">
                    <?php
                        echo "<p>$_SESSION[nombre_usuario]</p>";
                    ?>
                    <p>En linea</p>
                </div>
                <div>
                    <button id="boton-cerrar">
                        Cerrar sesion
                    </button>
                    <button id="boton-eliminar-cuenta">
                        Eliminar Cuenta
                    </button>
                </div>
            </header>
        </section>
        <section class="catalogo" id="catalogo_usuarios">
            <?php
                foreach($usuario_lista as $usuario)
                {
                    if ($usuario['usuario_ID'] == $_SESSION['usuario_ID'])
                        continue;
                    echo "<button class='usuario-boton' value=$usuario[usuario_ID]>";
                    echo "<div class='imagen'><img src='php/cargar_foto.php?usuario_ID=$usuario[usuario_ID]' alt=''>";
                    echo "</div><div class='contenido'><p>$usuario[nombre_usuario]</p>";
                    if($usuario['enlinea'])
                        echo "<p>En linea</p>";
                    else
                        echo "<p>Desconectado</p>";
                    echo "</div></button>";
                }
            ?>
        </section>
    </div>

    <form action="chat.php" method="post" id="form-chat">
        <input type="hidden" name="oyente_ID" id="oyente" value="">
    </form>

    <script src="js/usuarios.js"></script>
</body>
</html>