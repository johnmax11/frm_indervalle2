<?php
namespace MiProyecto{
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL | ~E_WARNING);
    ini_set('display_errors',1);
    unset($_SESSION);
    session_start();
    $_SESSION = '';
    $_SERVER['DOCUMENT_ROOT'] = (dirname(dirname(dirname(__FILE__))));
    require_once($_SERVER['DOCUMENT_ROOT'].'/aplication/configuration/config.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/aplication/configuration/config_ssn.php');
    require_once($_SERVER['DOCUMENT_ROOT'].'/aplication/utils/utilidades.php');
    $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'] = $_SERVER['DOCUMENT_ROOT'].'';

    /**
     * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 1.0.0
     * Fecha - 24-03-2012
     */
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5');
    //se instancia la clase
    $obFilter = new InputFilter();
    //Variable Global $_POST libre de XSS e Inyecciones SQL
    $_POST = $obFilter->process($_POST);
    $_GET  = $obFilter->process($_GET);

    $strPath=$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'];
    /// include de los objetos necesarios
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/Cron/fac_cron.php');
    ////////
    class cron{
        private $_obj;
        public function __construct() {
            $this->_obj = new fac_cron();
            $this->_obj->_request =  $_REQUEST;
        }

        /***/
        public function publicar_foto_facebookEvent(){
            $this->_obj->publicar_foto_facebookPublic();
        }
    }
}