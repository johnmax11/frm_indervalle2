<?php
namespace MiProyecto{
    if(!isset($_SESSION)){
            session_start();
    }
    /*
     * variables de url y ubicacion del proyecto
     */
    $_SESSION['_SFT_NAME_'] = "demo";
    $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE'] = array(
        "PATH_APLICATION"=>$_SERVER['DOCUMENT_ROOT'].'/demo',
        "URL_SITE"=>"http://localhost/demo/aplication",
        "URL_WEB"=>"http://localhost/demo",
        "DEBUG"=>"DEV",
        "ESTILO_DEFAULT"=>"South Street"
    );
}