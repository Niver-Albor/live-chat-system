<?php
    #   Importa el archivo donde tengo las funciones que siempre llamo.
    require 'funciones.php';
    
    session_start();

    actualizarEstado($_SESSION['usuario_ID'],0);

    session_unset();

    session_destroy();

    header('location: ../index.php');
?>