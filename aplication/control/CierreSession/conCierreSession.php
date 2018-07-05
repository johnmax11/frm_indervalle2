<?php
namespace MiProyecto{
	if(!isset($_SESSION)){
		session_start();
		/*echo json_encode(array(
			"val_session"=>"true"
		));
		exit;*/
	}
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ERROR | ~E_WARNING);
	ini_set('display_errors',1);
	/**
	 * @author: John Jairo Cortes Garcia - johnjairo1984@gmail.com
	 * @version: 1.0.0
	 * Fecha - 28-03-2012
	 */
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/utils/inputfilter-2005-05-09/class.inputfilter.php5');
	//se instancia la clase
	$obFilter = new InputFilter();
	//Variable Global $_POST libre de XSS e Inyecciones SQL
	$_POST = $obFilter->process($_POST);
	$_GET  = $obFilter->process($_GET);

	$strPath='';
	if(isset($_GET['actionajax']) || isset($_POST['actionajax'])){
		$strPath .= $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'];
	}else{
		$strPath .= $_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'];
	}

	/// include de los objetos necesarios
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_dao/dao_cuentas_ingreso.php');
	require_once ($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['PATH_APLICATION'].'/aplication/model/cl_vo/vo_cuentas_ingreso.php');
	//
	$actionBttn = (isset($_GET['actionBttn'])?$_GET['actionBttn']:(isset($_POST['actionBttn'])?$_POST['actionBttn']:""));
	//////
	if(isset($actionBttn)){
		switch($actionBttn){
			case 'actualizarsession':
				$objDaoCuentasIngreso = new dao_cuentas_ingreso();
				$objVoCuentasIngreso = new vo_cuentas_ingreso();
				
				$bolResultado = false;
				
				$objVoCuentasIngreso->set_id_cuentas($_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_USER']['ID_CUENTA']);
				
				// consultamos el id del ult ingreso
				$objVoCuentasIngreso = $objDaoCuentasIngreso->consulta_cuenta_ingreso_by_idcuentas_last($objVoCuentasIngreso);
				if($objVoCuentasIngreso==null){
					echo json_encode(array(
						"update_session"=>"false",
						"urlcierre"=>$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_SITE']
					));
					exit;
				}
				/// actualizamos la session
				$objVoCuentasIngreso_upd = new vo_cuentas_ingreso();
				
				$objVoCuentasIngreso_upd->set_id($objVoCuentasIngreso->get_ult_id());
				// actualizamos el registro
				$bolResultado = $objDaoCuentasIngreso->update_cuentas_ingreso_last($objVoCuentasIngreso_upd);
				if($bolResultado==false){
					echo json_encode(array(
						"update_session"=>"false",
						"urlcierre"=>$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_SITE']
					));
					exit;
				}
				echo json_encode(array(
					"update_session"=>"true"
				));
				break;
		}
	}
}
