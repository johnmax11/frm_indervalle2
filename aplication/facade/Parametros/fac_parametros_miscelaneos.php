<?php
namespace MiProyecto{
    class fac_miscelaneos{
        public $_request;
        public $_utiSsn;
		
        function __construct(){}
		
		/*
		 * action para buscar un producto por su nombre
		 */
		public function search_estilos(){
                    try{
                        $obj_dao_parametros_miscelaneos = new dao_parametros_miscelaneos();
                        // set datos //
                        $obj_dao_parametros_miscelaneos->_vo->set_tabla('estilos');
                        // execute //
                        $arrDEstilos = $obj_dao_parametros_miscelaneos->select_rows('descripcion','ASC',0,99)->fetch_object_vo();

                        $arrResponse = array();
                        for($i=0;$i<count($arrDEstilos);$i++){
                            $obj_dao_parametros_miscelaneos->new_vo();
                            $obj_dao_parametros_miscelaneos->_vo = $arrDEstilos[$i];

                            $arrResponse['rows'][$i]['id'] = $obj_dao_parametros_miscelaneos->_vo->get_descripcion();
                            $arrResponse['rows'][$i]['nombre'] = $obj_dao_parametros_miscelaneos->_vo->get_descripcion();
                        }
                        utilidades::set_response($arrResponse);
                    }catch(Exception $e){
                        utilidades::set_response(__METHOD__.' ---> '.$e->getMessage(),true);
                    }
		}
		
		/***/
		public function search_medidas(){
			try{
				$obj_dao_parametros_miscelaneos = new dao_parametros_miscelaneos();
				$obj_dao_parametros_miscelaneos->new_vo();
				// set datos //
				$obj_dao_parametros_miscelaneos->_vo->set_tabla('medida');
				// execute //
				$arrDMed = $obj_dao_parametros_miscelaneos->select_rows('descripcion','ASC',0,99)->fetch_object_vo();
				
				$arrResponse = array();
				for($i=0;$i<count($arrDMed);$i++){
					$obj_dao_parametros_miscelaneos->new_vo();
					$obj_dao_parametros_miscelaneos->_vo = $arrDMed[$i];
					
					$arrResponse['rows'][$i]['id'] = $obj_dao_parametros_miscelaneos->_vo->get_id();
					$arrResponse['rows'][$i]['nombre'] = $obj_dao_parametros_miscelaneos->_vo->get_descripcion();
				}
				
				utilidades::set_response($arrResponse);
			}catch(Exception $e){
				utilidades::set_response(__METHOD__.' ---> '.$e->getMessage(),true);
			}
		}
	}
}
