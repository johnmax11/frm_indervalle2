<?php
namespace MiProyecto{
    /*
     * To change this template, choose Tools | Templates
     * and open the template in the editor.
     */
    session_start();
    $pathUrlWeb = $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'];
    session_unset();
    session_destroy();
    unset($_SESSION);
    header('location: '.$pathUrlWeb);
}
