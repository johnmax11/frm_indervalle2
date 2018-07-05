<?php
namespace MiProyecto{
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL | ~E_WARNING);
	ini_set('display_errors',1);
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
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/Home/fac_home.php');
	class con_home{
		private $_obj;
		public function __construct() {
			$this->_obj = new fac_home();
			$this->_obj->_request =  $_REQUEST;
			$this->_obj->_utiSsn  = new utisetVarSession();
		}
		/**
		 * 
		 */
		public function select_info_importanteEvent(){
			$this->_obj->getinformacionimportanteAction();
		}
		/**
		 * 
		 */
		public function save_cambio_passwordEvent(){
			$this->_obj->guardar_cambio_clave();
		}
	}
}
