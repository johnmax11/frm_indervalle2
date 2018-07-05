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
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/facade/Clientes/fac_anticipos.php');
	/**/
	class con_anticipos{
		private $_obj;
		public function __construct() {
			/** objeto de facadade **/
			$this->_obj = new fac_anticipos();
			$this->_obj->_request =  $_REQUEST;
			$this->_obj->_utiSsn  = new utisetVarSession();
		}
		/**/
		public function select_rowEvent(){
			$this->_obj->searchrowsPublic();
		}
		/***/
		public function create_rowEvent(){
			$this->_obj->addeditrowsPublic();
		}
		/**/
		public function delete_rowEvent(){
			$this->_obj->deleterowsPublic();
		}
		/**/
		public function select_datos_rowEvent(){
			$this->_obj->searchdatosrowsPublic();
		}
		/***************************************************************************/
		/**/
		public function select_datos_row_by_cliente_idEvent(){
			$this->_obj->searchdatosrows_by_cliente_idPublic();
		}
		/**/
		public function select_datos_entradas_salidasEvent(){
			$this->_obj->select_datos_entradas_salidasPublic();
		}
	}
}