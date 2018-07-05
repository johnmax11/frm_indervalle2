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
	class con_roles{
		private $_obj;
		public function __construct() {
			$this->_obj = new fac_seguridad_roles();
			$this->_obj->_request =  $_REQUEST;
			$this->_obj->_utiSsn  = new utisetVarSession();
		}
		/***/
		public function searchrolesEvent(){
			$this->_obj->searchrolesallAction();
		}
		/***/
		public function create_rowEvent(){
			$this->_obj->addeditrolesAction();
		}
		/***/
		public function delete_rowsEvent(){
			$this->_obj->deleterolesAction();
		}
		/***/
		public function searchdatosrowsEvent(){
			$this->_obj->searchdatosrolesAction();
		}
		/***/
		public function cargaraccesosbyrolEvent(){
			$this->_obj->cargaraccesosbyrolAction();
		}
		/***/
		public function guardarconfiguracionEvent(){
			$this->_obj->guardarconfiguracionAction();
		}
		/***/
		public function verificaraccesosrolEvent(){
			$this->_obj->verificaraccesosrolAction();
		}
	}
}