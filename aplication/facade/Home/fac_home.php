<?php
namespace MiProyecto{
    class fac_home{
        public $_request;
        public $_utiSsn;
        public $_arrResponse = array();
		
	function __construct(){}
		
		/**/
		public function getinformacionimportanteAction(){
			try{
				$arrRsp = array();

				// consultar cambio de clave //
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				// set datos //
				$obj_dao_usuarios->_vo->set_id($this->_utiSsn->get_ssn_id_users());
				// execute //
				$arrDatosUs = $obj_dao_usuarios->select_rows()->fetch_object_vo();
				$obj_dao_usuarios->_vo = $arrDatosUs[0];
				// comparamos //
				if($obj_dao_usuarios->_vo->get_clave() == $obj_dao_usuarios->_vo->get_clave_inicial()){
					// retornamos el cambio de clave //
					utilidades::set_response(array('cambio_password'=>true));
					return;
				}
				
				$obj_dao_usuarios_ingresos = new dao_usuarios_ingresos();
				$obj_dao_usuarios_ingresos->new_vo();
				$obj_dao_usuarios_ingresos->_vo->set_usuarios_id($this->_utiSsn->get_ssn_id_users());
				// execute //
				$arrResult = $obj_dao_usuarios_ingresos->select_rows('id','DESC',0,5)->fetch_object_vo();

				for($i=0;$i<count($arrResult);$i++){
					$obj_dao_usuarios_ingresos->new_vo();
					$obj_dao_usuarios_ingresos->_vo = $arrResult[$i];

					$this->_arrResponse['rows'][$i]['date_entered'] = $obj_dao_usuarios_ingresos->_vo->get_fecha_creacion();

					// buscamos el nombre de la empresa //
					$strNombreEmpresas = $this->get_nombre_empresas_by_idPrivate($obj_dao_usuarios_ingresos->_vo->get_empresas_id());
					$this->_arrResponse['rows'][$i]['nombre_empresas'] = $strNombreEmpresas;
					// buscamos el nombre de la oficina //
					$strNombreEmpresasOficinas = $this->get_nombre_empresas_oficinas_by_idPrivate($obj_dao_usuarios_ingresos->_vo->get_empresas_oficinas_id());
					$this->_arrResponse['rows'][$i]['nombre_empresas_oficinas'] = $strNombreEmpresasOficinas;
				}
				// paint response //
				utilidades::set_response($this->_arrResponse);
			}catch(Exception $e){
				utilidades::set_response(array('msj'=>"Error: --->".__METHOD__."--->".$e->getMessage()),true);
			}
		}
		
		/**
		 * @return string recibe el id de la empresa y retorn el nombre de la empresa
		 */
		private function get_nombre_empresas_by_idPrivate($idempresa){
			// buscamos el nombre de la empresa //
			$obj_dao_empresas = new dao_empresas();
			$obj_dao_empresas->new_vo();
			// set valores //
			$obj_dao_empresas->_vo->set_id($idempresa);
			// execute //
			$arrDatos = $obj_dao_empresas->select_rows()->fetch_object_vo();
			$obj_dao_empresas->_vo = $arrDatos[0];
			return $obj_dao_empresas->_vo->get_nombre();
		}
		
		/**
		 * @return string recibe el id de la empresa_oficina y retorn el nombre de la empresa_oficina
		 */
		private function get_nombre_empresas_oficinas_by_idPrivate($idoficina){
			// buscamos el nombre de la empresa //
			$obj_dao_empresas_oficinas = new dao_empresas_oficinas();
			$obj_dao_empresas_oficinas->new_vo();
			// set valores //
			$obj_dao_empresas_oficinas->_vo->set_id($idoficina);
			// execute //
			$arrDatos = $obj_dao_empresas_oficinas->select_rows()->fetch_object_vo();
			$obj_dao_empresas_oficinas->_vo = $arrDatos[0];
			return $obj_dao_empresas_oficinas->_vo->get_nombre();
		}
		
		/**
		 * @return null guarda los cambio de clave
		 */
		public function guardar_cambio_clave(){
			try{
				$obj_dao_usuarios = new dao_usuarios();
				$obj_dao_usuarios->new_vo();
				// validamos la clave del usuario actual //
				$obj_dao_usuarios->_vo->set_id($this->_utiSsn->get_ssn_id_users());
				// execute //
				$arrDUs = $obj_dao_usuarios->select_rows()->fetch_object_vo();
				$obj_dao_usuarios->_vo = $arrDUs[0];
				// validamos la clave del usuario //
				$clave_user = $obj_dao_usuarios->_vo->get_clave();
				$clave_form = md5($this->_utiSsn->get_ssn_usuario_users().'_'.$this->_request['txtClave_antigua']);
				if($clave_user == $clave_form){
					$obj_dao_usuarios->new_vo();
					// set datos //
					$obj_dao_usuarios->_vo->set_id($this->_utiSsn->get_ssn_id_users());
					$obj_dao_usuarios->_vo->set_modificado_por($this->_utiSsn->get_ssn_id_users());
					$obj_dao_usuarios->_vo->set_fecha_modificacion(utilidades::get_current_timestamp());
					$clave = ($this->_utiSsn->get_ssn_usuario_users().'_'.$this->_request['txtClave_nueva']);
					$obj_dao_usuarios->_vo->set_clave(md5($clave));
					// execute //
					$obj_dao_usuarios->update_rows($obj_dao_usuarios->_vo);
					
					utilidades::set_response(array('msj'=>"Proceso terminado correctamente"));
				}else{
					utilidades::set_response(array('msj'=>"La clave actual no coincide con la clave del usuario"),true);
				}
			}catch(Exception $e){
				utilidades::set_response(__METHOD__.' ---> '.$e->getMessage(),true);
			}
		}
	}
}
