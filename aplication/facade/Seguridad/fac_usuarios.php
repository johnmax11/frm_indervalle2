<?php
namespace MiProyecto{
    class fac_usuarios{
        public $_request;
        public $_utiSsn;
		
        function __construct(){}
		
		/*
		 * action para buscar un producto por su nombre
		 */
		public function searchrowsAction(){
			$this->set_filters_grilla_basPrivate($page,$limit,$sidx,$sord);
			//// get the direction 
			if(!$sidx){ 
				$sidx =1; 
			}
			// set valores //
			$obj_dao_usuarios = new dao_usuarios();
			$obj_dao_usuarios->new_vo();
			$obj_dao_usuarios->_vo->set_estado('A');
			$obj_dao_usuarios->_vo->set_id(array(2));
			//// set filters ////
			if(isset($this->_request['filters'])){
				utilidades::parsear_filters($obj_dao_usuarios->_vo,$this->_request);
			}
			//// connect to the database 
			////// sacamos los registros en estado activo
			$arrDatos = $obj_dao_usuarios->select_rows()->fetch_object_vo();
			$count = count($arrDatos); 
			if( $count >0 ) { 
				$total_pages = ceil($count/$limit);
			} else {
				$total_pages = 0;
			} 
			if ($page > $total_pages) 
				$page=$total_pages; 
			$start = $limit*$page - $limit; 
			if($start<0){
				$start = 0;
			}
			//// do not put $limit*($page - 1)
			$responce = new \stdClass();
			$responce->page = $page; 
			$responce->total = $total_pages; 
			$responce->records = $count;
			$arrDatos = array();
			$arrDatos = $obj_dao_usuarios->select_rows($sidx, $sord, $start, $limit)->fetch_object_vo();
			$numRows = count($arrDatos);
			for($i=0;$i<$numRows;$i++){
				$obj_vo_usuarios = $arrDatos[$i];
				
				// sacamos el nombre del usuario //
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				$obj_dao_usuarios->_vo->set_id($obj_vo_usuarios->get_creado_por());
				// ejecutamos //
				$arrDatos_usuarios = $obj_dao_usuarios->select_rows()->fetch_object_vo();
				$obj_dao_usuarios->_vo = $arrDatos_usuarios[0];
				////////////////////////////////////////
				$responce->rows[$i]['id']=$obj_vo_usuarios->get_id();
				$responce->rows[$i]['cell']=array(
					$obj_vo_usuarios->get_id(),
					utf8_encode($obj_vo_usuarios->get_usuario()),
					utf8_encode($obj_vo_usuarios->get_nombre()),
					strtoupper($obj_dao_usuarios->_vo->get_usuario()),
					substr($obj_vo_usuarios->get_fecha_creacion(),0,10)
				);
			}
			
			echo json_encode($responce);
		}
		
		/***/
		private function set_filters_grilla_basPrivate(&$page,&$limit,&$sidx,&$sord){
			$page = (isset($this->_request['page'])?$this->_request['page']:1); 
			//// get the requested page 
			$limit = (isset($this->_request['rows'])?$this->_request['rows']:999999999);
			//// get how many rows we want to have into the grid 
			$sidx = (isset($this->_request['sidx'])?$this->_request['sidx']:'id');
			//// get index row - i.e. user click to sort 
			$sord = (isset($this->_request['sord'])?$this->_request['sord']:'ASC');
		}
		
		/**/
		public function addeditrowsAction(){
			try{
				// start transaccion //
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->begin();

				// consultamos si existe el producto //
				$obj_dao_usuarios->new_vo();
				$obj_dao_usuarios->_vo->set_id(($this->_request['hdnid']!=''?$this->_request['hdnid']:-1));
				// ejecutamos //
				$arrDatos_usuarios = $obj_dao_usuarios->select_rows()->fetch_object_vo();
				
				$obj_dao_usuarios->_vo->set_nombre(strtoupper($this->_request['txtNombre_usuario']));
				$obj_dao_usuarios->_vo->set_apellido(strtoupper($this->_request['txtApellidos_usuario']));
				$obj_dao_usuarios->_vo->set_email(strtolower($this->_request['txtEmail_usuario']));
				$obj_dao_usuarios->_vo->set_estado($this->_request['selEstado_usuario']);
				$obj_dao_usuarios->_vo->set_seguridad_roles_id($this->_request['selSeguridad_roles']);
				////////////////////////////////////////////////
				if($arrDatos_usuarios == null){
					// verificamos si el usuarios existe //
					$obj_dao_usuarios->_vo->set_usuario($this->_request['txtUsuario_usuario']);
					// execute validacion //
					$arrDatosValidacion = $obj_dao_usuarios->select_rows()->fetch_object_vo();
					if($arrDatosValidacion==null){
						$clave = utilidades::RandomString(10,true,true);
						$obj_dao_usuarios->_vo->set_clave(md5($this->_request['txtUsuario_usuario'].'_'.$clave));
						$obj_dao_usuarios->_vo->set_clave_inicial(md5($this->_request['txtUsuario_usuario'].'_'.$clave));
						// insertamos //

						$bolresult = $obj_dao_usuarios->insert_rows();
						// enviamos el email de bienvenida //
						$this->send_mail_bienvenidoPrivate($obj_dao_usuarios,$clave);
					}else{
						utilidades::set_response(array('msj'=>'El usuario que intenta registra ya existe, proceso abortado'));
						return false;
					}
				}else{
					// actualizamos //
					$obj_dao_usuarios->_vo->set_id($this->_request['hdnid']);
					$bolresult = $obj_dao_usuarios->update_rows();
				}
				$obj_dao_usuarios->commit();
				//
				utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
			}catch(Exception $e){
				$obj_dao_usuarios->rollback();
				utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
			}
			
			// end transaccion //
			return true;
		}
		/**/
		public function deleterowsAction(){
			try{
				// set datos //
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				$obj_dao_usuarios->_vo->set_id($this->_request['idrow']);
				$obj_dao_usuarios->_vo->set_estado('C');
				$result = $obj_dao_usuarios->update_rows();
			
				utilidades::set_response(array("msj"=>"Proceso terminado correctamente"));
			}catch(Exception $e){
				utilidades::set_response(array("msj"=>"Error: --->".__METHOD__.'--->'.$e->getMessage()));
			}
		}
		/**/
		public function searchdatosrowsAction(){
			try{
				$arrResponse = array();

				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				$obj_dao_usuarios->_vo->set_id($this->_request['idrow']);
				$arrResult = $obj_dao_usuarios->select_rows()->fetch_object_vo();
				$obj_dao_usuarios->_vo = $arrResult[0];

				$arrResponse['rows'][0]['id'] = $obj_dao_usuarios->_vo->get_id();
				$arrResponse['rows'][0]['usuario'] = utf8_encode($obj_dao_usuarios->_vo->get_usuario());
				$arrResponse['rows'][0]['nombre'] = utf8_encode($obj_dao_usuarios->_vo->get_nombre());
				$arrResponse['rows'][0]['apellido'] = utf8_encode($obj_dao_usuarios->_vo->get_apellido());
				$arrResponse['rows'][0]['email'] = utf8_encode($obj_dao_usuarios->_vo->get_email());
				$arrResponse['rows'][0]['estado'] = $obj_dao_usuarios->_vo->get_estado();
				$arrResponse['rows'][0]['seguridad_roles_id'] = $obj_dao_usuarios->_vo->get_seguridad_roles_id();

				utilidades::set_response($arrResponse);
			}catch(Exception $e){
				utilidades::set_response(array("msj"=>"Error: --->".__METHOD__."--->".$e->getMessage()),true);
			}
		}
		
		/**************************************************************************/
		public function searchrolesAction(){
			try{
				$obj_dao_seguridad_roles = new dao_seguridad_roles();
				$obj_dao_seguridad_roles->new_vo();
				$obj_dao_seguridad_roles->_vo->set_estado('A');
				$obj_dao_seguridad_roles->_vo->set_id(array(2));
				//// connect to the database 
				////// sacamos los registros en estado activo
				$arrDatos = $obj_dao_seguridad_roles->select_rows('nombre','ASC',0,999999999)->fetch_object_vo();
				$arrResponse = array();
				for($i=0;$i<count($arrDatos);$i++){
					$obj_dao_seguridad_roles->new_vo();
					$obj_dao_seguridad_roles->_vo = $arrDatos[$i];
					
					$arrResponse['rows'][$i]['id'] = $obj_dao_seguridad_roles->_vo->get_id();
					$arrResponse['rows'][$i]['nombre'] = $obj_dao_seguridad_roles->_vo->get_nombre();
				}
				utilidades::set_response($arrResponse);
			}catch(Exception $e){
				utilidades::set_response(array("msj"=>"Error: --->".__METHOD__."--->".$e->getMessage()),true);
			}
		}
		
		/**
		 * @return boolean validar erl nombre de usuario
		 */
		public function validar_usuarioAction(){
			try{
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				// set valores //
				$obj_dao_usuarios->_vo->set_usuario($this->_request['ajax_nombre_u']);
				// execute //
				$arrDatos = $obj_dao_usuarios->select_rows()->fetch_object_vo();
				
				$existe = true;
				if($arrDatos==null){
					$existe = false;
				}
				
				utilidades::set_response(array('existe'=>$existe));
			}catch(Exception $e){
				utilidades::set_response(array('msj'=>"Error: --->".__METHOD__.'--->'.$e->getMessage()),true);
			}
		}
		
		/**
		 * @return boolean se encarga de enviar un email de bienvenido al nuevo usuario
		 */
		private function send_mail_bienvenidoPrivate($obj_dao_usuarios,$clave){
			try{
				$obj_mail_tpl = new mail_plantillas();
				$obj_mail_tpl->new_tpl_new_usuario();

				$obj_mail_tpl->_strHtmlPlantilla = str_replace('[|NOMBRE_USUARIO|]',$obj_dao_usuarios->_vo->get_usuario(),$obj_mail_tpl->_strHtmlPlantilla);
				$obj_mail_tpl->_strHtmlPlantilla = str_replace('[|CLAVE_USUARIO|]',$clave,$obj_mail_tpl->_strHtmlPlantilla);
				$obj_mail_tpl->_strHtmlPlantilla = str_replace('[|URL_WEB|]',$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'],$obj_mail_tpl->_strHtmlPlantilla);

				// obj send email //
				$obj_send_mail = new mail_envios();
				$obj_send_mail->inicialice_mail($obj_mail_tpl->_strHtmlPlantilla);
				$obj_send_mail->set_asunto("Creado nuevo usuario");
				$obj_send_mail->_strEmail = $obj_dao_usuarios->_vo->get_email();
				$obj_send_mail->envio_mail();
			}catch(Exception $e){
				throw new Exception ('Error: ---> '.$e->getMessage());
			}
		}
		
		/**
		 * @return boolean se encarga de resetear el password del usuario y envia un
		 * mail al usuario
		 */
		public function reset_passwordAction(){
			try{
				$obj_dao_usuarios = new dao_usuarios();
				
				// set valores //
				$obj_dao_usuarios->new_vo();
				$obj_dao_usuarios->_vo->set_id($this->_request['ajax_id_u']);
				
				// execute //
				$arrDatos = $obj_dao_usuarios->select_rows()->fetch_object_vo();
				$obj_dao_usuarios->_vo = $arrDatos[0];
				$strUsuario = $obj_dao_usuarios->_vo->get_usuario();
				$strmail = $obj_dao_usuarios->_vo->get_email();

				// set valores //
				$obj_dao_usuarios->new_vo();
				$obj_dao_usuarios->_vo->set_id($this->_request['ajax_id_u']);
				$clave = utilidades::RandomString(10,true,true);
				$obj_dao_usuarios->_vo->set_clave(md5($strUsuario.'_'.$clave));
				$obj_dao_usuarios->_vo->set_clave_inicial(md5($strUsuario.'_'.$clave));
				// execute cambios //
				$obj_dao_usuarios->update_rows();
				
				$obj_dao_usuarios->_vo->set_email($strmail);
				$obj_dao_usuarios->_vo->set_usuario($strUsuario);
				// enviamos el email //
				$this->send_mail_reset_passwordPrivate($obj_dao_usuarios,$clave);
				
				utilidades::set_response(array('msj'=>"Proceso terminado correctamente, se ha enviado un email al usuario con la nueva clave"));
			}catch(Exception $e){
				utilidades::set_response(array('msj'=>"Error: --->".__METHOD__."--->".$e->getMessage()),true);
			}
		}
		
		/**
		 * @return boolean envia un email al usuario indicando el cambio
		 */
		private function send_mail_reset_passwordPrivate($obj_dao_usuarios,$clave){
			try{
				$obj_mail_tpl = new mail_plantillas();
				$obj_mail_tpl->new_tpl_reset_password();

				$obj_mail_tpl->_strHtmlPlantilla = str_replace('[|NOMBRE_USUARIO|]',$obj_dao_usuarios->_vo->get_usuario(),$obj_mail_tpl->_strHtmlPlantilla);
				$obj_mail_tpl->_strHtmlPlantilla = str_replace('[|CLAVE_USUARIO|]',$clave,$obj_mail_tpl->_strHtmlPlantilla);
				$obj_mail_tpl->_strHtmlPlantilla = str_replace('[|URL_WEB|]',$_SESSION[$_SESSION['_SFT_NAME_']]['CONFIG_SITE']['URL_WEB'],$obj_mail_tpl->_strHtmlPlantilla);

				// obj send email //
				$obj_send_mail = new mail_envios();
				$obj_send_mail->inicialice_mail($obj_mail_tpl->_strHtmlPlantilla);
				$obj_send_mail->set_asunto("Clave de ingreso reiniciada");
				$obj_send_mail->_strEmail = $obj_dao_usuarios->_vo->get_email();
				$obj_send_mail->envio_mail();
			}catch(Exception $e){
				throw new Exception ('Error: ---> '.$e->getMessage());
			}
		}
		
		/***/
		public function save_estilos(){
			try{
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				// set datos //
				$obj_dao_usuarios->_vo->set_id($this->_utiSsn->get_ssn_id_users());
				$obj_dao_usuarios->_vo->set_estilo($this->_request['a_estilo']);
				// execute //
				$obj_dao_usuarios->update_rows();
				
				$this->_utiSsn->set_ssn_estilo($this->_request['a_estilo']);
				
				utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
			}catch(Exception $e){
				utilidades::set_response(__METHOD__.' ---> '.$e->getMessage(),true);
			}
		}
	}
}
