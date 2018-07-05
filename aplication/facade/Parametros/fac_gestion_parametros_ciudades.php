<?php 
namespace MiProyecto{
    class fac_gestion_parametros_ciudades{
	function __construct(){}
		
        /*
         * action para buscar un producto por su nombre
         */
        public function buscar_ciudad_atucompletePublic(){
            try{
                /**buscamos los datoa por autocomplete*/
                $arrDR = $this->get_parametros_ciudades();
                /***/
                $arrResponse = array();
                // verificamos //
                if(count($arrDR)>0){
                    $obj_dao_parametros_ciudades = new dao_parametros_ciudades();
                    for($i=0;$i<count($arrDR);$i++){
                        $obj_dao_parametros_ciudades->new_vo();
                        $obj_dao_parametros_ciudades->_vo = $arrDR[$i];

                        $arrResponse[$i]['id'] = $obj_dao_parametros_ciudades->_vo->get_ID();
                        $arrResponse[$i]['label'] = strtoupper($obj_dao_parametros_ciudades->_vo->get_Name());
                        $arrResponse[$i]['value'] = strtoupper($obj_dao_parametros_ciudades->_vo->get_Name());
                    }
                }
                utilidades::set_response($arrResponse);
            }catch(\Exception $ex){
                return false;
            }
        }
        
        /***/
        public function get_parametros_ciudades($sidx=null, $sord=null, $start=null, $limit=null){
            try{
                /* inst obj dao */
                $obj_dao_parametros_ciudades = new dao_parametros_ciudades();
                // set datos //
                if(request::get_parameter('term')!=null){
                    $obj_dao_parametros_ciudades->_vo->set_Name("EXP||LIKE||%".ucwords(request::get_parameter('term'))."%");
                }
                $obj_dao_parametros_ciudades->_vo->set_CountryCode("COL");
                // execute //
                return $obj_dao_parametros_ciudades->select_rows()->fetch_object_vo();
            } catch (\Exception $ex) {
                return false;
            }
        }
    }
}