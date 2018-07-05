<?php
namespace MiProyecto{
    class fac_usuarios_track{
        public $_request;
        public $_utiSsn;
	
        function __construct(){}
		
            /**/
            public function addeditrowsAction(){
                try{
                    // start transaccion //
                    $obj_dao_usuarios_track = new dao_usuarios_track();
                    $obj_dao_usuarios_track->begin();

                    // buscamos el ultimo track del usuario //
                    $obj_dao_usuarios_track->new_vo();
                    $obj_dao_usuarios_track->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
                    $arrD = $obj_dao_usuarios_track->select_rows('id','DESC',0,1)->fetch_object_vo();
                    $str_url_old = '';
                    if($arrD!=null){
                        $obj_dao_usuarios_track->_vo = $arrD[0];
                        $str_url_old = $obj_dao_usuarios_track->_vo->get_url_Track();
                    }
                    // verificamos el resultado //
                    if($str_url_old != $this->_request['url_track']){
                        $obj_dao_usuarios_track->new_vo();
                        $obj_dao_usuarios_track->_vo->set_url_track($this->_request['url_track']);
                        $obj_dao_usuarios_track->_vo->set_direccion_ip((isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']!=null?$_SERVER['HTTP_X_FORWARDED_FOR']:$_SERVER['REMOTE_ADDR']));
                        $obj_dao_usuarios_track->_vo->set_seguridad_programas_id($this->_utiSsn->get_ssn_seguridad_programa_id());

                        $bolresult = $obj_dao_usuarios_track->insert_rows();
                    }
                    $obj_dao_usuarios_track->commit();
                    //
                    utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
                }catch(Exception $e){
                    $obj_dao_usuarios_track->rollback();
                    utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
                }
                // end transaccion //
                return true;
            }
		
            /***/
            public function search_historialAction(){
			try{
				$obj_dao_usuarios_track = new dao_usuarios_track();
				
				$obj_dao_usuarios_track->new_vo();
				$obj_dao_usuarios_track->_vo->set_creado_por($this->_utiSsn->get_ssn_id_users());
				
				$arrDatos = $obj_dao_usuarios_track->select_rows('id','DESC',0,10)->fetch_object_vo();
				
				$arrResponse = array();
				for($i=0;$i<count($arrDatos);$i++){
					$obj_dao_usuarios_track->new_vo();
					$obj_dao_usuarios_track->_vo = $arrDatos[$i];
					$arrResponse['rows'][$i]['url_track'] = $obj_dao_usuarios_track->_vo->get_url_track();
					// buscamos el icono //
					$obj_dao_seguridad_programas = new dao_seguridad_programas();
					$obj_dao_seguridad_programas->new_vo();
					// set datos //
					$obj_dao_seguridad_programas->_vo->set_id($obj_dao_usuarios_track->_vo->get_seguridad_programas_id());
					// execute //
					$arrDProgr = $obj_dao_seguridad_programas->select_rows()->fetch_object_vo();
					$obj_dao_seguridad_programas->_vo = $arrDProgr[0];
					//
					$arrResponse['rows'][$i]['class_icon'] = ($arrDProgr!=null?$obj_dao_seguridad_programas->_vo->get_imagen():'');
				}
				return $arrResponse;
			}catch(Exception $e){
				utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
			}
		}
	}
}
