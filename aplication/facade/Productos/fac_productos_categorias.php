<?php
namespace MiProyecto{
    class fac_productos_categorias{
        function __construct(){}
	
        /**
         * consulta una categoria por id
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @return json
         * @param null
         */
        public function voidBuscarCategoriaProductosByIdPublic(){
            try{
                $arrDatosProdCategoria = $this->getProductosCategorias(
                            (object)array(
                                'id'=>request::get_parameter('idrow')
                            )
                        );
                
                $arrResponse = new \stdClass();
                
                if($arrDatosProdCategoria!=null){
                    for($i=0;$i<count($arrDatosProdCategoria);$i++){
                        $arrResponse->rows[$i] = new \stdClass();
                        
                        $arrResponse->rows[$i]->id = $arrDatosProdCategoria[$i]->get_id();
                        $arrResponse->rows[$i]->nombre = $arrDatosProdCategoria[$i]->get_nombre();
                    }
                }
                
                utilidades::set_response($arrResponse);
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
        
        /**
         * consulta los productos categorias
         * 
         * @author john jairo cortes garcia <johnmax11@hotmail.com>
         * @param type $arrParametros
         * @param type $sidx
         * @param type $sord
         * @param type $start
         * @param type $limit
         * @return array
         */
        private function getProductosCategorias($arrParametros=array(),$sidx=null,$sord=null, $start=null, $limit=null){
            try{
                $objDaoProductosCategorias = new dao_productos_categorias();
                
                if(isset($arrParametros->id) && $arrParametros->id!=''){
                    $objDaoProductosCategorias->_vo->set_id($arrParametros->id);
                }
                
                return $objDaoProductosCategorias->select_rows($sidx,$sord,$start,$limit)->fetch_object_vo();
            }catch(\Exception $ex){
                new ExceptionHandler($ex->getMessage());
            }
        }
    }
}
