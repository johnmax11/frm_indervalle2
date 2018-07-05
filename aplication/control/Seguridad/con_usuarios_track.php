<?php
namespace MiProyecto{
	if(!isset($_SESSION)){
		session_start();
	}
	require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/Utils/utiVerificaInicioSession.php');
	require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utiSetVarSession.php');
	require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/utilidades.php');
	if(isset($_REQUEST['url_track'])){
		$str_url = explode('?',$_REQUEST['url_track']);
		$str_url = explode('&',$str_url[1]);
		for($i=0;$i<count($str_url);$i++){
			$arrD = explode('=',$str_url[$i]);
			if($arrD[0]=='module'){
				$_REQUEST_aux['module'] = $arrD[1];
			}
			if($arrD[0]=='action'){
				$_REQUEST_aux['action'] = $arrD[1];
			}
		}
		if(!utilidades::verifica_rol_acceso_in_array(new utisetVarSession(),$_REQUEST_aux,'visible')){
			if(utilidades::isAjax()){
				utilidades::set_response(array('msj'=>"Acceso denegado a este recurso"),true);
			}else{
				header('location: '.$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'].'/index.php?module=Home&action=Home');
			}
			exit;
		}
	}
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
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/Seguridad/fac_usuarios_track.php');
	//
	class con_usuarios_track{
		private $_obj;
		public function __construct() {
			$this->_obj = new fac_usuarios_track();
			$this->_obj->_request =  $_REQUEST;
			$this->_obj->_utiSsn  = new utisetVarSession();
		}
		/***/
		public function addeditrowsEvent(){
			$this->_obj->addeditrowsAction();
		}
		
		public function search_historialEvent(){
			 return $this->_obj->search_historialAction();
		}
	}
}