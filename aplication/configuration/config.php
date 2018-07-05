<?php 
namespace MiProyecto{
    if(!isset($_SESSION)){
            session_start();
    }
    // configuracion de base de datos
    define("_USER_CX_", "root");
    define("_PASS_CX_", "");
    define("_HOST_CX_", "localhost");
    define("_BD_NAME_CX_", "modaplan_demo");
}