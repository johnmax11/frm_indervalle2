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
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/Seguridad/fac_usuarios.php');
	//
	class con_usuarios{
		private $_obj;
		public function __construct() {
			$this->_obj = new fac_usuarios();
			$this->_obj->_request =  $_REQUEST;
			$this->_obj->_utiSsn  = new utisetVarSession();
		}
		/***/
		public function searchrowsEvent(){
			$this->_obj->searchrowsAction();
		}
		/***/
		public function addeditrowsEvent(){
			$this->_obj->addeditrowsAction();
		}
		/***/
		public function delete_rowsEvent(){
			$this->_obj->deleterowsAction();
		}
		/***/
		public function searchdatosrowsEvent(){
			$this->_obj->searchdatosrowsAction();
		}
		/***************************************************************************/
		/***/
		public function searchrolesEvent(){
			$this->_obj->searchrolesAction();
		}
		/***/
		public function validar_usuarioEvent(){
			$this->_obj->validar_usuarioAction();
		}
		/***/
		public function reset_passwordEvent(){
			$this->_obj->reset_passwordAction();
		}
		/***/
		public function save_estilosEvent(){
			$this->_obj->save_estilos();
		}
	}
}