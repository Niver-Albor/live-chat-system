<?php

    $nombre_db = 'chat_local_v1';
    $usuario = 'root';
    $contrasena = '';
    $error = '';
    try
    {
        $conexionBD = new PDO("mysql:host=localhost; dbname=$nombre_db", $usuario, $contrasena);
    }
    catch(PDOException $e)
    {
        echo '<script>alert(' . $e->getMessage() . ');</script>';
    }
?>