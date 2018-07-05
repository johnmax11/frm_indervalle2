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
    //se instancia la clase
    $obFilter = new InputFilter();
    //Variable Global $_POST libre de XSS e Inyecciones SQL
    $_POST = $obFilter->process($_POST);
    $_GET  = $obFilter->process($_GET);

    $strPath = (isset($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'])?$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION']:null);
    /// include de los objetos necesarios
    require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/Seguridad/fac_seguridad_roles.php');
    //
    $actionBttn = (isset($_GET['bttnAction'])?$_GET['bttnAction']:(isset($_POST['bttnAction'])?$_POST['bttnAction']:""));

    if(isset($actionBttn)){
        $objFac_roles = new fac_seguridad_roles();
        $objFac_roles->_request =  isset($_POST['bttnAction']) ? $_POST : $_GET;
        $objFac_roles->_utiSsn  = new utisetVarSession();
        switch($actionBttn){
            case "verificaraccesosrol":
                $objFac_roles->verificaraccesosrolAction(isset($_POST['verifica_from_index'])?$_POST['verifica_from_index']:false);
                break;
        }
    }
}