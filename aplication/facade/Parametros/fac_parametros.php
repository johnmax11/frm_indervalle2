<?php 
namespace MiProyecto{
    class fac_parametros{
        function __construct(){}
        
        /**
         * consulta y devuelve los datos de la tabla parametros
         * 
         * @author john jairo cortes Garcia <johnmax11@hotmail.com>
         * @return array vo
         * @param array $arrParametros
         */
        public function getParametros($arrParametros){
            try{
                $objDaoParametros = new dao_parametros();
                if(isset($arrParametros->id) && $arrParametros->id!=''){
                    $objDaoParametros->_vo->set_id($arrParametros->id);
                }
                return $objDaoParametros->select_rows()->fetch_object_vo();
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        public function setUpdateParametros($arrParametros=array()){
            try{
                $objDaoParametros = new dao_parametros();
                
                $objDaoParametros->_vo->set_id($arrParametros->id);
                if(isset($arrParametros->consecutivo_factura) && $arrParametros->consecutivo_factura!=''){
                    $objDaoParametros->_vo->set_consecutivo_factura($arrParametros->consecutivo_factura);
                }
                /***/
                $objDaoParametros->update_rows();
                
                return true;
            } catch (\Exception $ex) {
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}