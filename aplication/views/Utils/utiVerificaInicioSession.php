<?php
namespace MiProyecto{
    if(!isset($_SESSION)){
            session_start();
    }
    /**limipiamos la variable de session cache para el proceso*/
    unset($_SESSION['querys_cache']);
    if(!isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){
        require_once('utiCerrarSession.php');
    }
}

