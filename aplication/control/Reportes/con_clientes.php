<?php
namespace MiProyecto{ 
    if(!isset($_SESSION)){
            session_start();
    }
    require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/Utils/utiVerificaInicioSession.php');
    /**
     * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
     * @version: 1.0.0
     * Fecha - 24-03-2012
     */
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5');
    /*se instancia la clase*/
    $obFilter = new InputFilter();
    /*Variable Global $_POST libre de XSS e Inyecciones SQL*/
    $_POST = $obFilter->process($_POST);
    $_GET  = $obFilter->process($_GET);
    $strPath = (isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'])?$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']:null);
    /* include de los objetos necesarios*/
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/Reportes/fac_clientes.php');
    /**/
    class con_clientes{
        private $_obj;
        public function __construct() {
            /** objeto de facadade **/
            $this->_obj = new fac_clientes();
            $this->_obj->_request =  $_REQUEST;
            $this->_obj->_utiSsn  = new utisetVarSession();
        }
        /**/
        public function search_compras_clientesEvent(){
            $this->_obj->search_compras_clientesPublic();
        }
        
        /***/
        public function search_detalles_compras_clientesEvent(){
            $this->_obj->search_detalles_compras_clientesPublic();
        }
    }
}
