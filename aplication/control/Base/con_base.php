<?php 
namespace MiProyecto{
	/**
	 * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
	 * @version: 2.0.0
	 * Fecha - 16-08-2014
	 */
	 
	if(!isset($_SESSION)){
		session_start();
	}
	
	/**verificamos inicio de session*/
	require_once($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/views/Utils/utiVerificaInicioSession.php');
	
	/**verificamos limpieza de datos**/
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5');
	/*se instancia la clase*/
	$obFilter = new InputFilter();
	/*Variable Global $_POST libre de XSS e Inyecciones SQL*/
	$_POST = $obFilter->process($_POST);
	$_GET  = $obFilter->process($_GET);
	
	/* include de los objetos necesarios*/
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/'.substr(dirname(__FILE__),strrpos(dirname(__FILE__),DIRECTORY_SEPARATOR)+1).'/fac_base.php');
	/**/
	class con_base{
		private $_obj;
		public function __construct() {
			/** objeto de facade **/
			$this->_obj = new fac_base();
			$this->_obj->_request =  $_REQUEST;
			$this->_obj->_files = $_FILES;
			$this->_obj->_utiSsn  = new utisetVarSession();
		}
		
		/***/
		public function select_rowEvent(){
			$this->_obj->select_rowPublic();
		}
		
		/***/
		public function create_rowEvent(){
			$this->_obj->create_rowPublic();
		}
		
		/***/
		public function update_rowEvent(){
			$this->_obj->create_rowPublic();
		}
		
		/***/
		public function delete_rowEvent(){
			$this->_obj->delete_rowPublic();
		}
		
		/***/
		public function select_row_by_idEvent(){
			$this->_obj->select_by_idPublic();
		}
		/***************************************************************************/
		
	}
}