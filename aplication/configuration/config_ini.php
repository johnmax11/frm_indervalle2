<?php
namespace MiProyecto{
    if(!isset($_SESSION)){
        session_start();
    }
    $_SESSION['_SFT_NAME_'] = "demo";
    if(!isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_USERS'])){
        $_GET['module'] = 'Usuarios';
        $_GET['action'] = 'Login';
    }
}