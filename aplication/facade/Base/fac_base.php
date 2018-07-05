<?php
namespace MiProyecto{
    class fac_base{
        public $_request;
        public $_utiSsn;
        public $_files;
        
        function __construct(){}
		
        /*****************************************************************/
        /***/
        public function create_rowPublic(){
            try{
                /***/
                $n_tabla = strtolower($this->_request['module']).'_'.strtolower($this->_request['action_base']);
                eval('$obj_base = new '.__NAMESPACE__.'\dao_'.$n_tabla.'();');
                $obj_base->_vo->set_nombre(strtoupper($this->_request['txtNombre_'.strtolower($this->_request['action_base'])]));
                /***insertar registro**/
                $obj_base->insert_rows();

                utilidades::set_response(array('msj'=>'Proceso terminado correctamente'));
            }catch(\Exception $e){
                utilidades::set_response(array('msj'=>'Error: ---> '.__METHOD__.'--->'.$e->getMessage()),true);
            }
        }
		
    }
}
