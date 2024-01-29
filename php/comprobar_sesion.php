<?php
    session_start();
    if(isset($_SESSION['usuario_ID']))
    {
        // print_r($_SESSION);
    }
    else
    {
        header('Location:index.php');
    }

?>